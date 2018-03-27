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

namespace iumioFramework\Composer;
use iumioFramework\Composer\Server as iSM;

use  Composer\Script\Event;


/**
 * Class Installer
 * @package iumioFramework\Composer
 * @category Framework
 * @licence  MIT License
 * @link https://framework.iumio.com
 * @author   RAFINA Dany <dany.rafina@iumio.com>
 */
class Installer
{
    static public $base_dir = __DIR__.'/../../';

    static public $base_dir_new = __DIR__.'/../../../';


    /**
     * @param Event $event
     * @throws \Exception
     */
    public static function postUpdate(Event $event)
    {
        $composer = $event->getComposer();
        self::do();
    }



    /**
     * Move some components downloaded by composer to the correct location
     * @throws \Exception
     */
    final public static function moveComponentsDownloadedByComposer() {
        if (iSM::exist(self::$base_dir."vendor/components/font-awesome/")) {
        iSM::move(self::$base_dir . "vendor/components/font-awesome/",
            self::$base_dir . "public/components/libs/font-awesome/");
        }
        if (iSM::exist(self::$base_dir . "vendor/components/jquery/")) {
            iSM::move(self::$base_dir . "vendor/components/jquery/",
                self::$base_dir . "public/components/libs/jquery/");
        }

        if (iSM::exist(self::$base_dir . "vendor/daneden/animate.css/")) {
            iSM::move(self::$base_dir . "vendor/daneden/animate.css/",
                self::$base_dir . "public/components/libs/animate.css/");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/bootstrap/")) {
            // Move bootstrap assets to public libs directory
            iSM::move(self::$base_dir . "vendor/iumio/framework-assets/bootstrap/",
                self::$base_dir . "public/components/libs/bootstrap");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/dwr/")) {
            // Move dwr-util assets to public libs directory
            iSM::move(self::$base_dir . "vendor/iumio/framework-assets/dwr/",
                self::$base_dir . "public/components/libs/dwr");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/iumio-framework/")) {
            // Move framework assets to public libs directory
            iSM::move(self::$base_dir . "vendor/iumio/framework-assets/iumio-framework/",
                self::$base_dir . "public/components/libs/iumio-framework/");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/iumio-manager")) {
            // Move manager assets to public libs directory
            iSM::move(self::$base_dir . "vendor/iumio/framework-assets/iumio-manager",
                self::$base_dir . "public/components/libs/iumio-manager");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/mercure")) {
            // Move mercure assets to public libs directory
            iSM::move(self::$base_dir . "vendor/iumio/framework-assets/mercure",
                self::$base_dir . "public/components/libs/mercure");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/skel")) {
            // Move SKEL assets to public libs directory
            iSM::move(self::$base_dir . "vendor/iumio/framework-assets/skel",
                self::$base_dir . "public/components/libs/skel");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-installer/")) {
            // Move installer to public and rename it to setup
            iSM::move(self::$base_dir . "vendor/iumio/framework-installer/",
                self::$base_dir . "public/setup/");
        }
    }


    /**
     * Remove components dir in root directory
     * @throws \Exception
     */
    final public static function removeComponentsDir()
    {
        if (iSM::exist(self::$base_dir . "vendor/daneden/animate.css/")) {
            // remove animate.css assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/animate.css/", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/bootstrap/")) {
            // remove bootstrap assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/bootstrap/", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/dwr/")) {
            // remove dwr assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/dwr/", "directory");
        }

        if (iSM::exist(self::$base_dir."vendor/components/font-awesome/")) {
            // remove font-awesome assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/font-awesome/", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/components/jquery/")) {
            // remove jquery assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/jquery/", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/iumio-framework")) {
            // remove framework assets to public libs directory
            iSM::delete(self::$base_dir . "public/components/libs/iumio-framework", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/iumio-manager")) {
            // remove manager assets to public libs directory
            iSM::delete(self::$base_dir . "public/components/libs/iumio-manager", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/mercure")) {
            // remove mercure assets to public libs directory
            iSM::delete(self::$base_dir . "public/components/libs/mercure", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/skel")) {
            // remove SKEL assets to public libs directory
            iSM::delete(self::$base_dir . "public/components/libs/skel", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-installer/")) {
            // remove setup directory
            iSM::delete(self::$base_dir . "public/setup", "directory");
        }

        if (!iSM::exist(self::$base_dir . "public/components/libs/")) {
            // Create libs directory
            iSM::create(self::$base_dir . "public/components/libs/", "directory");
        }
    }


    /**
     * Init installer
     * @throws \Exception
     */
    final public static function do() {
        self::removeComponentsDir();
        self::moveComponentsDownloadedByComposer();
    }
}



