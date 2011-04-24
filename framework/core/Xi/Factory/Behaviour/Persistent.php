<?php
/**
 * Caches the values of factory accesses into a Xi_Storage
 *
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Behaviour_Persistent extends Xi_Factory_Behaviour_Cached
{
    /**
     * @var Xi_Storage
     */
    protected $_storage;

    /**
     * @var array
     */
    protected $_initialData;

    /**
     * @param Xi_Factory_Interface
     * @param Xi_Storage
     * @return void
     */
    public function __construct($factory = null, $storage)
    {
        parent::__construct($factory);
        $this->_storage = $storage;
        $this->_data    = $storage->isEmpty() ? array() : $storage->read();
        $this->_initialData = array_keys($this->_data);
    }

    public function __destruct()
    {
        if (array_keys($this->_data) !== $this->_initialData) {
            $this->_storage->write($this->_data);
        }
    }
}