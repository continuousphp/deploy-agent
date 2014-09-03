<?php
/**
 * EntityAbstract.php
 *
 * @date        09/03/14
 * @file        EntityAbstract.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent\Entity;

use Zend\Stdlib\ArraySerializableInterface;

/**
 * Entity Abstract
 *
 * @package     CphpAgent
 * @subpackage  Entity
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
abstract class EntityAbstract implements ArraySerializableInterface
{
    /**
     * @param array $data data to merge
     *
     * @throws Exception
     * @return $this|void
     */
    public function exchangeArray(array $data)
    {
        if (is_object($data)) {
            if ($data instanceof \ArrayObject) {
                $data = $data->getArrayCopy();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } elseif (!$data instanceof \Iterator) {
                throw new Exception('Model should be instanciated with an array or an iterable object.');
            }
        } else if (!is_array($data)) {
            throw new Exception('Model should be instanciated with an array or an iterable object.');
        }

        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            $this->$method($value);
        }

        return $this;
    }
}
