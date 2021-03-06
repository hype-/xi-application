<?php

/**
 * Xi
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category   Xi
 * @package    Application
 * @subpackage Resource
 * @license    http://www.opensource.org/licenses/BSD-3-Clause New BSD License
 */

namespace Xi\Application\Resource;

use Zend_Application_Resource_ResourceAbstract as ResourceAbstract,
    Zend_Exception,
    MongoConnectionException;

/**
 * Mongo application resource
 *
 * @category   Xi
 * @package    Application
 * @subpackage Resource
 * @author     pekkis
 * @todo       Multiple mongoloids
 */
class Mongo extends ResourceAbstract
{
    protected $_options = array(
		'hostname' => '127.0.0.1',
		'port' => '27017',
		'username' => null,
		'password' => null,
		'databasename' => null,
		'connect'  => true,
    );

    /**
     * @return MongoDb
     */
    public function init()
    {
        $options = $this->getOptions();
        if($options['username'] && $options['password']) {
            $dns = "mongodb://{$options['username']}:{$options['password']}@{$options['hostname']}:{$options['port']}/{$options['databasename']}";
        } else {
            $dns = "mongodb://{$options['hostname']}:{$options['port']}/{$options['databasename']}";
        }
        try {
            $mongo = new \Mongo($dns, array('connect' => $options['connect']));

            // @todo: refuctor this kludgering
            if(isset($options['databasename'])) {
                $mongo = $mongo->{$options['databasename']};    
            }
            
            return $mongo;
            
        } catch (MongoConnectionException $e) {
            throw new Zend_Exception($e->getMessage());
        }

        return $mongo;

    }




}
