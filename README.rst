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
   
   - Installed from upstream source code to /var/www/cakephp

     **Security note**: Updates to CakePHP may require supervision so
     they **ARE NOT** configured to install automatically. See `CakePHP
     documentation`_ for upgrading.

- Includes TurnKey Web Control panel with links to useful references,
  relevant path information, and CakePHP checks (convenience).
- SSL support out of the box.
- `Adminer`_ administration frontend for MySQL (listening on port
  12322 - uses SSL).
- Postfix MTA (bound to localhost) to allow sending of email (e.g.,
  password recovery).
- Webmin modules for configuring Apache2, PHP, MySQL and Postfix.

Credentials *(passwords set at first boot)*
-------------------------------------------

-  Webmin, SSH, MySQL: username **root**
-  Adminer: username **adminer**


.. _CakePHP: https://cakephp.org/
.. _TurnKey Core: https://www.turnkeylinux.org/core
.. _CakePHP documentation: https://book.cakephp.org/3.0/en/upgrade-tool.html
.. _Adminer: https://www.adminer.org/
