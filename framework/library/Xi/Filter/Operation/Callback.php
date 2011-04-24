<?php
/**
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Operation_Callback extends Xi_Filter_Operation_Abstract
{
    public function __construct($argument = array(), $default = null)
    {
        parent::__construct($argument, $default);
    }

    public function filter($value)
    {
        if (!is_callable($value)) {
            return $this->_default;
        }

        return call_user_func_array($value, $this->getArgument());
    }

    public function getArgument()
    {
        return is_array($this->_argument) ? $this->_argument : array($this->_argument);
    }
}

