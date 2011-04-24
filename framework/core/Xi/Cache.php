<?php
/**
 * @category    Xi
 * @package     Xi_Cache
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Cache
{
    /**
     * Load data contained in the cache
     * 
     * @return mixed
     * @throws Xi_Cache_Exception
     */
    public function load();
    
    /**
     * Write data into the cache
     * 
     * @return boolean success
     */
    public function write($data);
    
    /**
     * Cache exists and is not outdated
     * 
     * @return boolean true
     */
    public function isValid();
}

