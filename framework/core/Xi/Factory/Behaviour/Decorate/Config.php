<?php
class Xi_Factory_Behaviour_Decorate_Config extends Xi_Factory_Behaviour_Abstract
{
    public function get($args = null)
    {
        $value = $this->_factory->get($args);

        if (is_array($value) || $value instanceof ArrayObject) {
            $value = new Xi_Config((array) $value);
        } elseif (is_object($value) && method_exists($value, 'toArray')) {
            $value = new Xi_Config($value->toArray());
        }

        return $value;
    }
}
