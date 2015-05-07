<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Validation\Validation;

if (Configure::read('debug') == 0):
	throw new NotFoundException();
endif;
?>

<h1>TurnKey CakePHP</h1>

<div id="container-1">
    <ul>
        <li><a href="#cp"><span>Control Panel</span></a></li>
        <li><a href="#about"><span>About</span></a></li>
        <li><a href="#checks"><span>Checks</span></a></li>
    </ul>

    <div id="cp">
        <div class="fragment-content">
            <div>
                <a href="https://<?php print
                $_SERVER{'HTTP_HOST'}; ?>:12320"><img
                src="images/shell.png"/>Web Shell</a>
            </div>
            <div>
                <a href="https://<?php print
                $_SERVER{'HTTP_HOST'}; ?>:12321"><img
                src="img/webmin.png"/>Webmin</a>
            </div>
            <div>
                <a href="https://<?php print
                $_SERVER{'HTTP_HOST'}; ?>:12322"><img
                src="img/phpmyadmin.png"/>PHPMyAdmin</a>
            </div>
            <div></div>
            <div></div>

            <h2>Resources and references</h2>
            <ul>
                <li><a href="http://www.cakefoundation.org">
                Cake Software Foundation</a></li>
                <li>
                    <a href="http://www.cakephp.org">
                    CakePHP website</a>,
                    <a href="http://book.cakephp.org">
                    documentation</a> and 
                    <a href="http://api.cakephp.org/3.0/">
                    API</a>
                </li>
                <li><a href="http://book.cakephp.org/3.0/en/tutorials-and-examples/blog/blog.html">
                CakePHP 15 minute blog tutorial</a></li>
                <li><a href="http://www.turnkeylinux.org/cakephp">
                TurnKey CakePHP release notes</a></li>
            </ul>
        </div>
    </div>
    <div id="about">
        <div class="fragment-content">
            <h2>The page you're looking at is dynamically generated</h2>
            <br/>

            <h3>Routing configuration</h3>
            <pre>/var/www/cakephp/config/routes.php</pre>

            <h3>View Layout</h3>
            <pre>/var/www/cakephp/src/Template/Layout/default.ctp</pre>

            <h3>View Page</h3>
            <pre>/var/www/cakephp/src/Template/Pages/home.ctp</pre>
    
            <h3>Webroot (css, js, images)</h3>
            <pre>/var/www/cakephp/webroot/</pre>

            <br/>
            <h3>And the cherry: MySQL preconfigured to get you started</h3>
            <pre>/var/www/cakephp/config/app.php</pre>
        </div>
    </div>
    <div id="checks">
        <div class="fragment-content">

        <?php
        if (Configure::read('debug')):
            Debugger::checkSecurityKeys();
        endif;
        ?>
        <p id="url-rewriting-warning" style="background-color:#e32; color:#fff;">
        <?php echo __d('cake_dev', 'URL rewriting is not properly configured on your server.'); ?>
            1) <a target="_blank" href="http://book.cakephp.org/3.0/en/installation/advanced-installation.html#apache-and-mod-rewrite-and-htaccess" style="color:#fff;">Help me configure it</a>
            2) <a target="_blank" href="http://book.cakephp.org/3.0/en/development/configuration.html#cakephp-core-configuration" style="color:#fff;">I don't / can't use URL rewriting</a>
        </p>
        <p>
        <?php
            if (version_compare(PHP_VERSION, '5.4.16', '>=')):
                echo '<span class="notice success">';
                    echo __d('cake_dev', 'Your version of PHP is 5.4.16 or higher.');
                echo '</span>';
            else:
                echo '<span class="notice">';
                    echo __d('cake_dev', 'Your version of PHP is too low. You need PHP 5.4.16 or higher to use CakePHP.');
                echo '</span>';
            endif;
        ?>
        </p>
        <p>
            <?php
                if (is_writable(TMP)):
                    echo '<span class="notice success">';
                        echo __d('cake_dev', 'Your tmp directory is writable.');
                    echo '</span>';
                else:
                    echo '<span class="notice">';
                        echo __d('cake_dev', 'Your tmp directory is NOT writable.');
                    echo '</span>';
                endif;
            ?>
        </p>
        <p>
            <?php
                $settings = Cache::config('_cake_core_');
                if (!empty($settings)): ?>
                    <span class="notice success">The <em><?= $settings['className'] ?>Engine</em> is being used for core caching. To change the config edit /var/www/cakephp/config/app.php</span>
                <?php else: ?>
                    <span class="notice">Your cache is NOT working. Please check the settings in /var/www/cakephp/config/app.php</span>
                <?php endif; ?>
        </p>
        <p>
           <?php
               try {
                   $connection = ConnectionManager::get('default');
                   $connected = $connection->connect();
               } catch (Exception $connectionError) {
                   $connected = false;
                   $errorMsg = $connectionError->getMessage();
                   if (method_exists($connectionError, 'getAttributes')):
                       $attributes = $connectionError->getAttributes();
                       if (isset($errorMsg['message'])):
                           $errorMsg .= '<br />' . $attributes['message'];
                       endif;
                   endif;
               }
           ?>
           <?php if ($connected): ?>
               <span class="notice success">CakePHP is able to connect to the database.</span>
           <?php else: ?>
               <span class="notice">CakePHP is NOT able to connect to the database.<br /><br /><?= $errorMsg ?></span>
           <?php endif; ?>
        </p>
        <?php
            if (!Validation::alphaNumeric('cakephp')) {
                echo '<p><span class="notice">';
                    echo __d('cake_dev', 'PCRE has not been compiled with Unicode support.');
                    echo '<br/>';
                    echo __d('cake_dev', 'Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
                echo '</span></p>';
            }
        ?>
        </div>
    </div>
</div>
