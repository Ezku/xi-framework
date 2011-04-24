<?php
/**
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_State_Grammar_Rule_Action_Entry extends Xi_State_Grammar_Rule_Action
{
    protected $_before;

    public function before($state)
    {
        $this->_before = $state;
        return $this;
    }

    public function apply($fsm)
    {
        $fsm->getState($this->_before)->attachEntryListener($this->getListener($this->_listener));
        $this->_before = null;
    }
}
