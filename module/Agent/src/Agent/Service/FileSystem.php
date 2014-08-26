<?php
/**
 * FileSystem.php
 *
 * @author  zenzumai
 */
namespace Agent\Service;

/**
 * Class FileSystem
 *
 * @package Agent\Service
 */
class FileSystem
{
    public static function mkdirp($dir, $mode = 0777, $recursive = true)
    {
        if (empty($dir))
            return false;

        if (is_dir($dir) || $dir === '/')
            return true;

        if (self::mkdirp(dirname($dir), $mode, $recursive)){
            mkdir($dir, $mode);
            chmod($dir, $mode);
            return $dir;
        }

        return false;
    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     * @param       string   $source    Source path
     * @param       string   $dest      Destination path
     * @param       string   $permissions New folder creation permissions
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

        if(substr($source, -1)==='/')
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
                    if (filetype($dir . "/" . $object) == "dir") self::rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}

