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


namespace iumioFramework\Tests;

use PHPUnit\Framework\TestCase;
use iumioFramework\Composer\Server;

/**
 * Class ServerTest
 * @package iumioFramework\Tests
 */
class ServerTest extends TestCase
{

    /**
     * Test create element
     * @throws \Exception
     */
    public function testCreate() {

        $file = "iumio.testing.txt";
        $dir = "iumio.testing.dir";
        if (true === file_exists($file)) {
            unlink($file);
        }
        if (true === is_dir($dir)) {
            rmdir($dir);
        }

        // Create a file
        $this->assertEquals(true, Server::create($file, 'file'));
        $this->assertEquals(true, Server::exist($file));

        // Create a directory
        $this->assertEquals(true, Server::create($dir, 'directory'));
        $this->assertEquals(true, Server::exist($dir));
    }

    /**
     * Test delete element
     * @throws \Exception
     */
    public function testDelete() {

        // Delete a file
        $file = "iumio.testing.txt";
        $this->assertEquals(true, Server::delete($file, 'file'));
        $this->assertEquals(false, Server::exist($file));

        // Delete a directory
        $dir = "iumio.testing.dir";
        $this->assertEquals(true, Server::delete($dir, 'directory'));
        $this->assertEquals(false, Server::exist($dir));

    }


    /**
     * Test move element
     * @throws \Exception
     */
    public function testMove() {

        $this->testCreate();
        $base = getcwd().DIRECTORY_SEPARATOR;

        $dirbase = $base."test.move.dir";
        $this->rrmdir("test.move.dir");
        if (true === file_exists("$dirbase/test.txt")) {
            unlink("$dirbase/test.txt");
        }
        if (true === file_exists("$dirbase/test2.txt")) {
            unlink("$dirbase/test2.txt");
        }
        if (true === file_exists("$dirbase/testing2")) {
            unlink("$dirbase/test2.txt");
        }
        mkdir($dirbase);


        // Move a file (not symlink)
        $file = $base."iumio.testing.txt";
        $this->assertEquals(true, Server::move($file, "$dirbase/test.txt", false));
        $this->assertEquals(true, Server::exist("$dirbase/test.txt"));

        // Move a file (symlink)
        $file = $base."iumio.testing2.txt";
        touch($file);
        $this->assertEquals(true, Server::move($file, "$dirbase/test2.txt", true));
        $this->assertEquals(true, Server::exist("$dirbase/test2.txt"));

        // Move a directory (not symlink)
        $dir = $base."iumio.testing.dir";
        $this->assertEquals(true, Server::move($dir, "$dirbase/iumio.testing.dir/", false));
        $this->assertEquals(true, Server::exist("$dirbase/iumio.testing.dir/"));

        $dir = $base."iumio.testing.dir2";
        mkdir($dir);
        // Move a directory (symlink)
        $this->assertEquals(true, Server::move($dir, "$dirbase/iumio.testing.dir2", true));
        $this->assertEquals(true, Server::exist("$dirbase/iumio.testing.dir2/"));
        Server::delete($dirbase, "directory");

    }

    /**
     * @param string $dir
     */
    public function rrmdir(string $dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        $this->rrmdir($dir . "/" . $object);
                    }
                    else {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}