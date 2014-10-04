<?php
/**
 * FileSystem.php
 *
 * @author  zenzumai
 */
namespace CphpAgent\Service;


/**
 * Class FileSystem
 *
 * @package CphpAgent\Service
 */
class FileSystem
{
    const SYMLINK_FILE = 0;
    const SYMLINK_DIR = 1;
    const SYMLINK_JUNCTION = 2;
    const OS_UNIX = 'unix';
    const OS_WINDOWS = 'win';

    public static function mkdirp($dir, $mode = 0777, $recursive = true)
    {
        if (empty($dir))
            return false;

        if (is_dir($dir) || $dir === '/')
            return true;

        if (self::mkdirp(dirname($dir), $mode, $recursive)) {
            mkdir($dir, $mode);
            chmod($dir, $mode);
            return $dir;
        }

        return false;
    }

    /**
     * Create link for Windows or Unix
     *
     * @param $source
     * @param $destination
     * @param string $os
     * @return bool|string
     */
    public static function link($source, $destination, $os = self::OS_UNIX)
    {
        if ($os == self::OS_WINDOWS)
            $result = self::mklink($source, $destination);
        else
            $result = symlink($source, $destination);

        return $result;
    }

    /**
     * Create Windows symbolic links
     *
     * @param $target
     * @param $link
     * @param int $flag
     * @return string
     */
    private static function mklink($target, $link, $flag = self::SYMLINK_DIR)
    {
        switch ($flag) {
            case self::SYMLINK_DIR:
                $pswitch = '/d';
                break;
            case self::SYMLINK_JUNCTION:
                $pswitch = '/j';
                break;
            case self::SYMLINK_FILE:
            default:
                $pswitch = '';
                break;
        }
        // Change / to \ because it will break otherwise.
        $target = str_replace('/', '\\', $target);
        $link = str_replace('/', '\\', $link);
        return exec('mklink ' . $pswitch . ' "' . $link . '" "' . $target . '"');
    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     * @param       string $source Source path
     * @param       string $dest Destination path
     * @param       int|string $permissions New folder creation permissions
     * @return      bool     Returns true on success, false on failure
     */
    public static function xcopy($source, $dest, $permissions = 0755)
    {

        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            self::mkdirp($dest);
        }

        if (substr($source, -1) === '/')
            $source = substr($source, 0, -1);
        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::xcopy("$source/$entry", "$dest/$entry");
        }

        // Clean up
        $dir->close();
        return true;
    }

    /**
     * Delete recursively a folder and its contents
     * @param string $dir path to the folder
     */
    public static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        self::rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}

