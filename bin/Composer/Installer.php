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

use Composer\Script\Event;

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

    /**
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
    final public static function moveComponentsDownloadedByComposer()
    {
        $elem = [
            [
                "source" => "vendor/components/font-awesome/",
                "dest" => "public/components/libs/font-awesome/"
            ],
            [
                "source" =>  "vendor/components/jquery/",
                "dest" => "public/components/libs/jquery/"
            ],
            [
                "source" =>  "vendor/daneden/animate.css/",
                "dest" => "public/components/libs/animate.css/"
            ],
            [
                "source" =>  "vendor/iumio/framework-assets/bootstrap/",
                "dest" => "public/components/libs/bootstrap"
            ],
            [
                "source" =>  "vendor/iumio/framework-assets/dwr/",
                "dest" => "public/components/libs/dwr"
            ],
            [
                "source" =>  "vendor/iumio/framework-assets/iumio-framework/",
                "dest" => "public/components/libs/iumio-framework/"
            ],
            [
                "source" =>  "vendor/iumio/framework-assets/iumio-manager",
                "dest" => "public/components/libs/iumio-manager"
            ],
            [
                "source" =>  "vendor/iumio/framework-assets/mercure",
                "dest" => "public/components/libs/mercure"
            ],
            [
                "source" =>  "vendor/iumio/framework-assets/skel",
                "dest" => "public/components/libs/skel"
            ],
            [
                "source" =>  "vendor/iumio/framework-installer/",
                "dest" => "public/setup/"
            ]
        ];

        foreach ($elem as $one) {
            if (iSM::exist(self::$base_dir.$one["source"])) {
                iSM::move(
                    self::$base_dir .$one["source"],
                    self::$base_dir . $one["dest"]
                );
            }
        }
    }


    /**
     * Remove components dir in root directory
     * @throws \Exception
     */
    final public static function removeComponentsDir()
    {
        $dir = [
            "vendor/daneden/animate.css/",
            "vendor/iumio/framework-assets/bootstrap/",
            "vendor/iumio/framework-assets/dwr/",
            "vendor/components/font-awesome/",
            "vendor/components/jquery/",
            "vendor/iumio/framework-assets/iumio-framework",
            "vendor/iumio/framework-assets/iumio-manager",
            "vendor/iumio/framework-assets/mercure",
            "vendor/iumio/framework-assets/skel",
            "vendor/iumio/framework-installer/",
            "public/components/libs/"
        ];

        foreach ($dir as $one) {
            if (iSM::exist(self::$base_dir . $one)) {
                iSM::delete(self::$base_dir . $one, "directory");
            }
        }
    }

    /**
     * Remove uncessary file
     * @throws \Exception
     */
    final public static function removeUncessaryFiles()
    {
        $remove = ["README.md", "CHANGELOG.md", "LICENSE"];
        $date = new \DateTime();
        $year = $date->format("Y");
        foreach ($remove as $one) {
            if (iSM::exist(self::$base_dir.$one)) {
                $file = file_get_contents(self::$base_dir . $one);
                if (false !== strpos($file, "INITIALIUMIOFW") ||
                    ("LICENSE" === $one &&
                        false !== strpos($file, "Copyright (c) $year RAFINA DANY - iumio"))) {
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
    final private static function changeComposerProprietary()
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
    final public static function createPersonalizedReadme()
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
    }
}
