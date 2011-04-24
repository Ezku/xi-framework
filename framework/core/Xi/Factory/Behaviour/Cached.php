<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Behaviour_Cached extends Xi_Factory_Behaviour_Abstract
{
    /**
     * @var array cache contents
     */
    protected $_data;

    protected function _makeId($factory, $args)
    {
        return __CLASS__.'::'.get_class($factory).'::'.md5(serialize($args));
    }

    public function get($args = null)
    {
        $id = $this->_makeId($this->_factory, $args);
        if (isset($this->_data[$id])) {
            return $this->_data[$id];
        }
        return $this->_data[$id] = $this->_factory->get($args);
    }
}