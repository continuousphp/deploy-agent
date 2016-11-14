<?php
/**
 * TarGzExtractor.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      TarGzExtractor.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\Archive\Extractor;

use Doctrine\Instantiator\Exception\UnexpectedValueException;

/**
 * TarGzExtractor
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class TarGzExtractor implements ExtractorInterface
{
    /**
     * @param \SplFileInfo $archive
     *
     * @return bool
     * @throws \Exception
     */
    public function extract(\SplFileInfo $archive, \SplFileInfo $destination)
    {
        //if (preg_match('/^win/i', PHP_OS)) {
            $targz = new \PharData($archive->getPathname());
            /** @var \PharData $tar */
            $tar = $targz->decompress();
            $tar->extractTo($destination->getPathname());
            unlink(str_replace('.tar.gz', '.tar', $archive->getPathname()));
        /*} else {
            exec('tar xzf ' . $archive->getPathname() . ' -C ' . $destination->getPathname());
            unlink($archive->getPathname());
        }*/

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
        $pathname = $archive->getPathname();
        try {
            $targz = new \PharData($pathname);
        } catch (UnexpectedValueException $e) {
        }
        
        $result   = new ValidationResult();

        if (isset($targz) && $targz instanceof \PharData) {
            $result->setValid(true);
        } else {
            $result->setMessage('Can\'t open file:' . $pathname);
        }
        
        return $result;
    }
}
