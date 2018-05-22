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


    /** After update
     * @throws \Exception
     */
    public static function postUpdate()
    {
        self::removeComponentsDir();
        self::moveComponentsDownloadedByComposer();
        self::removeUncessaryFiles();
        self::createPersonalizedReadme();
        self::changeComposerProprietary();
    }


    /** After install
     * @throws \Exception
     */
    public static function postInstall()
    {
        self::removeComponentsDir();
        self::moveComponentsDownloadedByComposer();
        self::removeUncessaryFiles();
        self::createPersonalizedReadme();
        self::changeComposerProprietary();
    }



    /**
     * Move some components downloaded by composer to the correct location
     * @throws \Exception
     */
    final public static function moveComponentsDownloadedByComposer():void
    {
        if (iSM::exist(self::$base_dir."vendor/components/font-awesome/")) {
            iSM::move(
                self::$base_dir . "vendor/components/font-awesome/",
                self::$base_dir . "public/components/libs/font-awesome/"
            );
        }
        if (iSM::exist(self::$base_dir . "vendor/components/jquery/")) {
            iSM::move(
                self::$base_dir . "vendor/components/jquery/",
                self::$base_dir . "public/components/libs/jquery/"
            );
        }
        if (iSM::exist(self::$base_dir . "vendor/daneden/animate.css/")) {
            iSM::move(
                self::$base_dir . "vendor/daneden/animate.css/",
                self::$base_dir . "public/components/libs/animate.css/"
            );
        }

        self::moveFrameworkComponents();
        self::moveFrameworkAssets();
    }


    /**
     * Move some components downloaded by composer to the correct location : framework assets
     * @throws \Exception
     */
    final public static function moveFrameworkAssets():void
    {
        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/bootstrap/")) {
            // Move bootstrap assets to public libs directory
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-assets/bootstrap/",
                self::$base_dir . "public/components/libs/bootstrap"
            );
        }
        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/dwr/")) {
            // Move dwr-util assets to public libs directory
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-assets/dwr/",
                self::$base_dir . "public/components/libs/dwr"
            );
        }
        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/skel")) {
            // Move SKEL assets to public libs directory
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-assets/skel",
                self::$base_dir . "public/components/libs/skel"
            );
        }
    }


    /** Move the framework components
     * @throws \Exception
     */
    final public static function moveFrameworkComponents():void
    {

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/iumio-framework/")) {
            // Move framework assets to public libs directory
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-assets/iumio-framework/",
                self::$base_dir . "public/components/libs/iumio-framework/"
            );
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/iumio-manager")) {
            // Move manager assets to public libs directory
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-assets/iumio-manager",
                self::$base_dir . "public/components/libs/iumio-manager"
            );
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/mercure")) {
            // Move mercure assets to public libs directory
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-assets/mercure",
                self::$base_dir . "public/components/libs/mercure"
            );
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-installer/")) {
            // Move installer to public and rename it to setup
            iSM::move(
                self::$base_dir . "vendor/iumio/framework-installer/",
                self::$base_dir . "public/setup/"
            );
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

        if (iSM::exist(self::$base_dir."vendor/components/font-awesome/")) {
            // remove font-awesome assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/font-awesome/", "directory");
        }
        if (iSM::exist(self::$base_dir . "vendor/components/jquery/")) {
            // remove jquery assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/jquery/", "directory");
        }

        if (!iSM::exist(self::$base_dir . "public/components/libs/")) {
            // Create libs directory
            iSM::create(self::$base_dir . "public/components/libs/", "directory");
        }

        self::removeFrameworkComponents();
    }

    /** Remove framework components
     * @throws \Exception
     */
    public static function removeFrameworkComponents():void
    {
        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-installer/")) {
            // remove setup directory
            iSM::delete(self::$base_dir . "public/setup", "directory");
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
    }

    /** Remove framework assets
     * @throws \Exception
     */
    public static function removeFrameworkAssets():void
    {
        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/bootstrap/")) {
            // remove bootstrap assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/bootstrap/", "directory");
        }

        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/dwr/")) {
            // remove dwr assets to public directory
            iSM::delete(self::$base_dir . "public/components/libs/dwr/", "directory");
        }
        if (iSM::exist(self::$base_dir . "vendor/iumio/framework-assets/skel")) {
            // remove SKEL assets to public libs directory
            iSM::delete(self::$base_dir . "public/components/libs/skel", "directory");
        }
    }

    /**
     * Remove uncessary file
     * @throws \Exception
     */
    final public static function removeUncessaryFiles():void
    {
        $remove = ["README.md", "CHANGELOG.md", "LICENSE"];
        $date = new \DateTime();
        $year = $date->format("Y");
        foreach ($remove as $one) {
            if (iSM::exist(self::$base_dir.$one)) {
                $file = file_get_contents(self::$base_dir . $one);
                if (false !== strpos($file, "INITIALIUMIOFW") ||
                    ("LICENSE" === $one &&
                        false !== strpos($file, "Copyright (c) $year Dany Rafina <dany.rafina@iumio.com>"))) {
                    iSM::delete(self::$base_dir . $one, "file");
                }
            }
        }
    }


    /** Get only the directory name
     * @param string $path Path to directory
     * @return string The dir name
     */
    final private static function getDirName(string $path):string
    {
        $page_name = realpath($path);
        $each_page_name = explode('/', $page_name);
        return (end($each_page_name));
    }

    /** Change the composer.json Proprietary
     * @throws \Exception
     */
    final private static function changeComposerProprietary():void
    {
        if (iSM::exist(self::$base_dir . "composer.json-dist")) {
            $file = file_get_contents(self::$base_dir . "composer.json-dist");
            if (false !== strpos($file, "{{ projectname }}")) {
                $pname = strtolower(self::getDirName(self::$base_dir));
                $file = str_replace("{{ projectname }}", $pname."/".$pname, $file);
                file_put_contents(self::$base_dir . "composer.json-dist", $file);
                iSM::delete(self::$base_dir . "composer.json", "file");
                iSM::move(self::$base_dir . "composer.json-dist", self::$base_dir . "composer.json");
            }
        }
    }

    /** Create a readme with project name and creation date
     * @throws \Exception
     */
    final public static function createPersonalizedReadme():int
    {
        $sentence = "\n----------------------------------\nMy iumio Framework project created ";
        if (!iSM::exist(self::$base_dir."README.md")) {
            $pname = self::getDirName(self::$base_dir);
            if (1 === iSM::create(self::$base_dir."README.md", "file")) {
                $date = new \DateTime();
                $date = $date->format("Y-m-d H:i:s");
                $sentence = ucfirst($pname)." ".$sentence.$date;
                file_put_contents(self::$base_dir."README.md", $sentence);
                return (1);
            }
            throw new \Exception("Cannot create README.md");
        }
        return (0);
    }
}
