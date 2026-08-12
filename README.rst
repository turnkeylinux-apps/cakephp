CakePHP - Rapid development PHP framework
=========================================

`CakePHP`_ is a PHP web application framework that is modeled after Ruby
on Rails. It makes building web applications simpler, faster and require
less code. It features easy configuration, rapid prototyping with code
generation and scaffolding, clean MVC conventions, and security measures
against common web attack techniques.

This appliance includes all the standard features in `TurnKey Core`_,
and on top of that:

- CakePHP configurations:
   
    - `CakePHP skeleton app`_ installed to /var/www/cakephp using composer.
    - Customized TurnKey CakePHP landing page with helpful info and links.
    - Preconfigured MySQL (MariaDB) DB, DB user and CakePHP app DB connection.
      (CakePHP DB user password randomized on firstboot).

     **Security note**: Updates to CakePHP may require supervision so
     they **ARE NOT** configured to install automatically. See `CakePHP
     migration/upgrade documentation`_ for details.

- SSL support out of the box.
- `Adminer`_ administration frontend for MySQL/MariaDB (listening on port
  12322 - uses SSL).
- Postfix MTA (bound to localhost) to allow sending of email (e.g.,
  password recovery).
- Webmin modules for configuring Apache2, PHP, MySQL and Postfix.

Credentials *(passwords set at first boot)*
-------------------------------------------

-  Webmin, SSH, MySQL: username **root**
-  Adminer: username **adminer**


.. _CakePHP: https://cakephp.org/
.. _CakePHP skeleton app: https://github.com/cakephp/app#cakephp-application-skeleton
.. _TurnKey Core: https://www.turnkeylinux.org/core
.. _CakePHP documentation: https://book.cakephp.org/5.x/appendices/migration-guides.html
.. _Adminer: https://www.adminer.org/
