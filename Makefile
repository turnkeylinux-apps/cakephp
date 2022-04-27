COMMON_OVERLAYS = tkl-webcp
PHP_EXTRA_PINS=libpcre2-8-0
PHP_VERSION=8.1
include $(FAB_PATH)/common/mk/turnkey/lamp.mk
include $(FAB_PATH)/common/mk/turnkey/composer.mk
include $(FAB_PATH)/common/mk/turnkey.mk
