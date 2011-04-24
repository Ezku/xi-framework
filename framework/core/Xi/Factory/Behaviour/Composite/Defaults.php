<?php
Xi_Loader::loadClass('Xi_Factory_Behaviour_Composite');

/**
 * Assuming two factories that each provide array data, use the primary one for
 * default values that can be overridden from the secondary one
 *
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Behaviour_Composite_Defaults extends Xi_Factory_Behaviour_Composite
{
    public function get($args = null)
    {
        $primary   = $this->_primary->get($args);
        $secondary = $this->_secondary->get($args);

        return Xi_Array_Operation_Merge::create($primary, $secondary)->execute();
    }
}
