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
}
