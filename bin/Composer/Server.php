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

declare(strict_types=1);
namespace iumioFramework\Composer;

/**
 * Class Server
 * @package iumioFramework
 * @category iumioFramework\Composer
 * @licence  MIT License
 * @link https://framework.iumio.com
 * @author   RAFINA Dany <dany.rafina@iumio.com>
 */
class Server
{
    public const EXECUTABLE = 0;
    public const READABLE = 1;
    public const WRITABLE = 2;

    /** Create an element on the server
     * @param string $path Element Path
     * @param string $type Element type
     * @return bool Result
     * @throws \Exception Generate Error
     */
    public static function create(string $path, string $type): bool
    {
        try {
            if ("directory" === $type && !is_dir($path)) {
                return (mkdir($path));
            } elseif ("file" === $type && !is_file($path)) {
                return (touch($path));
            }
        } catch (\Exception $exception) {
            throw new \Exception("Server Error : Cannot create $type element => " . $exception);
        }

        return (false);
    }

    /** Move an element on the server
     * @param string $path Element Path
     * @param string $dest Move to
     * @param bool $symlink Is symlink
     * @return bool Result
     * @throws \Exception Generate Error
     */
    public static function move(string $path, string $dest, bool $symlink = false): bool
    {
        try {
            if (true === is_file($path)) {
                return ((true === $symlink)? symlink($path, $dest) : rename($path, $dest));
            } elseif ($symlink && is_dir($path)) {
                return (symlink($path, $dest));
            }
            return (self::recursiveMoveDir($path, $dest));
        } catch (\Exception $exception) {
            throw new \Exception("Server Error : Cannot move $path to $dest => " . $exception);
        }
    }

    /**
     * Recursively move files from one directory to another
     * @param string $src element source
     * @param string $dest element destination
     * @return bool
     */
    public static function recursiveMoveDir(string $src, string $dest):bool
    {

        if (false === is_dir($src)) {
            return (false);
        } elseif (false === is_dir($dest) && !mkdir($dest)) {
            return (false);
        }

        $elem = new \DirectoryIterator($src);
        foreach ($elem as $file) {
            if ($file->isFile()) {
                rename($file->getRealPath(), "$dest/" . $file->getFilename());
            } elseif (!$file->isDot() && $file->isDir()) {
                self::recursiveMoveDir($file->getRealPath(), "$dest/$file");
                if (file_exists($file->getRealPath())) {
                    unlink($file->getRealPath());
                } else {
                    rmdir($file->getRealPath());
                }
            }
        }
        return (rmdir($src));
    }


    /** Copy an element on the server
     * @param string $path Element Path
     * @param string $dest Move to
     * @param string $type Element type
     * @param bool $symlink Is symlink
     * @return bool Result
     * @throws \Exception Generate Error
     */
    public static function copy(string $path, string $dest, string $type, bool $symlink = false): bool
    {
        try {
            if (false !== $symlink) {
                return (@symlink($path, $dest));
            } elseif (false === $symlink && "directory" === $type) {
                return (self::recursiveCopy($path, $dest));
            } elseif ($symlink == false && $type == "file") {
                return (copy($path, $dest));
            } else {
                throw new \Exception("Server Error on Copy: Element type is not recognized");
            }
        } catch (\Exception $exception) {
            throw new \Exception("Server Error : Cannot move $path to $dest => " . $exception);
        }
    }

    /** Check if an element existed on the server
     * @param string $path Element Path
     * @return bool If element exist
     */
    public static function exist(string $path): bool
    {
        return (((true === is_link($path))? true : file_exists($path)));
    }

    /** Delete an element on the server
     * @param string $path Element Path
     * @param string $type Element type
     * @return bool Result
     * @throws \Exception Generate Error
     */
    public static function delete(string $path, string $type): bool
    {
        try {
            if ("directory" === $type && is_link($path)) {
                return (unlink($path));
            } elseif ("directory" === $type && is_dir($path)) {
                if (!self::isDirEmpty($path)) {
                    $elem = function (string $dir) use ($path, &$elem) {
                        if (is_dir($dir)) {
                            $objects = scandir($dir);
                            foreach ($objects as $object) {
                                if ($object != "." && $object != "..") {
                                    if (filetype($dir . "/" . $object) == "dir") {
                                        $elem($dir . "/" . $object);
                                    } else {
                                        if (false === unlink($dir . "/" . $object)) {
                                            return (false);
                                        }
                                    }
                                }
                            }
                            reset($objects);
                            return rmdir($dir);
                        }
                        return (false);
                    };
                    $elem($path);
                } else {
                    return (rmdir($path));
                }
            } elseif ("file" === $type) {
                return (unlink($path));
            }
        } catch (\Exception $exception) {
            throw new \Exception("Server Error : Cannot delete $type element => " . $exception);
        }

        return (false);
    }


    /** Copy directory recursivly
     * @param string $src directory source
     * @param string $dst directory destination
     * @return bool
     */
    private static function recursiveCopy(string $src, string $dst): bool
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    if (false === copy($src . '/' . $file, $dst . '/' . $file)) {
                        return (false);
                    }
                }
            }
        }
        closedir($dir);
        return (true);
    }


    /**
     * Check if element is executable/Readable/writable
     * @param string $path Element path
     * @param int $mode Mode (Server::EXECUTABLE, Server::READABLE, Server::WRITABLE)
     * @return bool Is element is executable/Readable/writable or not
     * @throws \Exception
     */
    public static function checkIs(string $path, int $mode): bool
    {
        if (0 === $mode) {
            return (is_executable($path));
        } elseif (1 === $mode) {
            return (is_readable($path));
        } elseif (2 === $mode) {
            return (is_writable($path));
        }
        throw new \Exception("Server Error : Undefined Check is mode. Please set a existing mode");
    }

    /** Check if this dir is empty
     * @param string $dir dir path
     * @return bool
     * @throws \Exception
     */
    public static function isDirEmpty(string $dir):bool
    {
        if (is_readable($dir)) {
            return (2 == count(scandir($dir)));
        }
        throw new \Exception("Cannot determine if $dir dir is empty or not");
    }
}
