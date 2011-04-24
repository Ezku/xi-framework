<?php
/**
 * @category    Xi
 * @package     Xi_Cache
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Cache_File implements Xi_Cache
{
    protected $_file;
    protected $_doSerialize;
    protected $_timeToLive;

    /**
     * @param string path to file
     * @param null|boolean disable/enable automatic data serialization
     * @param null|int time to live (false to disable cache expiration)
     */
    public function __construct($file, $doSerialize = false, $timeToLive = 3600)
    {
        $this->_file = $file;
        $this->_doSerialize = $doSerialize;
        $this->_timeToLive = $timeToLive;
    }

    public function isValid()
    {
        return is_readable($this->_file)
               && (false === $this->_timeToLive || ((time() - filemtime($this->_file)) < $this->_timeToLive));
    }

    public function load()
    {
        $data = file_get_contents($this->_file);
        if ($this->_doSerialize) {
            $data = unserialize($data);
        }
        return $data;
    }

    public function write($data)
    {
        if ($this->_doSerialize) {
            $data = serialize($data);
        }
        return file_put_contents($this->_file, $data);
    }

    public function getFilename()
    {
        return $this->_file;
    }
}

