<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Factory_Reference extends Xi_Factory
{
    public function create($target, $args)
    {
        return $this->_locator->getResource($target, $args);
    }

    public function mapCreationArguments($args)
    {
        return array($this->getOption('locate'), $args);
    }

    public function getDefaultArguments()
    {
        return null;
    }
}

