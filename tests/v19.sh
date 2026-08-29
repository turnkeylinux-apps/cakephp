#!/bin/bash
set -Eeuo pipefail
umask 077

result=${TKL_TEST_RESULT:?TKL_TEST_RESULT is required}
db_password=${TKL_TEST_DB_PASS:?TKL_TEST_DB_PASS is required}
app=/var/www/cakephp
page=/tmp/tkl-cakephp-page.$$
show=/tmp/tkl-cakephp-show.$$
update=/tmp/tkl-cakephp-update.$$
trap 'rm -f -- "$page" "$show" "$update"' EXIT

# AUTO_RUN is a harness shortcut; interactive firstboot normally closes this
# temporary fence itself before handing HTTPS to the appliance web server.
systemctl stop turnkey-init-fence.service 2>/dev/null || true

systemctl --quiet is-active apache2.service mariadb.service postfix.service \
    multi-user.target
apache2ctl -t
apache2ctl -M 2>/dev/null | grep -q ' rewrite_module '
grep -Fxq 'VERSION_CODENAME=trixie' /etc/os-release
grep -Eq '^turnkey-cakephp-19\.0' /etc/turnkey_version

turnkey-composer --working-dir="$app" validate \
    --no-check-publish --no-interaction
turnkey-composer --working-dir="$app" check-platform-reqs --no-dev
turnkey-composer --working-dir="$app" show cakephp/cakephp \
    --format=json >"$show"
cake_version=$(python3 - "$show" <<'PY'
import json
import sys

with open(sys.argv[1], encoding="utf-8") as source:
    package = json.load(source)
print(package["versions"][0].lstrip("v"))
PY
)
[[ $cake_version == 5.4.* ]]
test -s "$app/composer.lock"
runuser --user=www-data -- "$app/bin/cake" migrations status \
    --no-interaction >/dev/null

curl --insecure --fail --silent --show-error https://localhost/ >"$page"
grep -Fq 'TurnKey CakePHP release notes' "$page"

test "$(stat -c '%U:%G:%a' "$app/config/app_local.php")" = \
    'root:www-data:640'
runuser --user=www-data -- test ! -w "$app/config/app_local.php"
runuser --user=www-data -- test -w "$app/logs"
runuser --user=www-data -- test -w "$app/tmp"
test -z "$(find "$app/config" "$app/src" "$app/templates" \
    -type f -perm /022 -print -quit)"

token="cakephp-v19-$$"
MYSQL_PWD=$db_password mariadb --user=root cakephp <<SQL
CREATE TABLE turnkey_acceptance (value VARCHAR(80) NOT NULL);
INSERT INTO turnkey_acceptance VALUES ('$token');
SQL
MYSQL_PWD=$db_password mariadb --user=root --batch --skip-column-names \
    cakephp --execute 'SELECT value FROM turnkey_acceptance LIMIT 1' |
    grep -Fxq "$token"
MYSQL_PWD=$db_password mariadb --user=root cakephp \
    --execute 'DROP TABLE turnkey_acceptance'

before=$(sha256sum "$app/composer.lock")
turnkey-composer --working-dir="$app" update --dry-run --no-interaction \
    --no-dev >"$update" 2>&1
after=$(sha256sum "$app/composer.lock")
test "$after" = "$before"

php_version=$(php --version | head -n 1)
cat >"$result" <<EOF
package_source=Official cakephp/app 5.4.0 skeleton pinned at build; production dependencies recorded in the generated Composer lock; PHP, MariaDB, Apache and Composer from Debian Trixie
installed_version=CakePHP $cake_version; $php_version; mariadb-server $(dpkg-query -W -f='${Version}' mariadb-server)
runtime_checks=normal init and service health; Apache configuration and HTTPS sample page; CakePHP Composer/platform validation and migration status; MariaDB create-read-drop persistence; root-owned application/configuration with only logs and tmp writable by www-data
updater_command=turnkey-composer update --dry-run --no-interaction --no-dev
updater_result=Composer completed a non-mutating dependency resolution check and the installed lock digest remained unchanged
updater_channel=https://packagist.org/packages/cakephp/cakephp within the pinned cakephp/app 5.4.0 constraint
integrity_evidence=application skeleton release is pinned; generated Composer lock is present and validates installed production platform requirements; executable PHP, configuration and templates are not group/world writable
EOF
