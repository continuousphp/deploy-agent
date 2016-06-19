<?php
/**
 * ZipExtractor.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ZipExtractor.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\Archive\Extractor;

/**
 * ZipExtractor
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class ZipExtractor implements ExtractorInterface
{
    /**
     * @param \SplFileInfo $directory
     *
     * @return bool
     * @throws \Exception
     */
    public function extract(\SplFileInfo $directory, \SplFileInfo $destination)
    {
        $zip = new \ZipArchive;
        if (! $zip->open($directory->getPathname()) || ! $zip->extractTo($destination->getPathname())) {
            throw new \Exception($zip->getStatusString());
        }
        $zip->close();
        return true;
    }

    /**
     * Check if the file must be accepted, or not
     *
     * @param \SplFileInfo $archive
     * @return bool
     */
    public function validate(\SplFileInfo $archive)
    {
        $zip      = new \ZipArchive;
        $pathname = $archive->getPathname();
        $result   = new ValidationResult();

        $open = $zip->open($pathname, \ZipArchive::CHECKCONS);
        if (true === $open) {
            $result->setValid(true);
        } else {
            switch ((int) $open) {
                case \ZipArchive::ER_NOZIP:
                    $result->setMessage('Not a zip archive:' . $pathname);
                    break;
                case \ZipArchive::ER_INCONS:
                    $result->setMessage('Zip archive inconsistent:' . $pathname);
                    break;
                case \ZipArchive::ER_CRC:
                    $result->setMessage('CRC error:' . $pathname);
                    break;
                case \ZipArchive::ER_OPEN:
                    $result->setMessage('Can\'t open file:' . $pathname);
                    break;
            }
        }
        return $result;
    }
}
