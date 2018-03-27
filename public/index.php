<?php

/**
 *
 *  * This is an iumio Framework component
 *  *
 *  * (c) RAFINA DANY <dany.rafina@iumio.com>
 *  *
 *  * iumio Framework, an iumio component [https://iumio.com]
 *  *
 *  * To get more information about licence, please check the licence file
 *
 */

// Require the Framework Environment Dispatcher to set specific environment
$frameworkEnv = require_once realpath(__DIR__
    . '/../vendor/iumio/iumio-framework/Core/Requirement/Environment/FrameworkEnvironmentDispatcher.php');

\iumioFramework\Core\Requirement\Environment\FrameworkEnvironmentDispatcher::dispatch();
