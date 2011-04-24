<?php
/**
 * Retrieves the value of an index on an array
 *
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Operation_Index extends Xi_Filter_Operation_Abstract
{
    public function filter($value)
    {
        $index = $this->getArgument();
        if (!isset($value[$index])) {
            return $this->_default;
        }

        return $value[$index];
    }
}

