<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Registry extends Xi_Factory
{
    public function create($target, $args)
    {
        $registry = Zend_Registry::getInstance();
        if (!$registry instanceof Xi_Locator) {
            throw new Xi_Factory_Exception('Cannot access registry; not yet initialized');
        }
        return $registry->getResource($target, $args);
    }

    public function mapCreationArguments($args)
    {
        return array($this->getOption('registry'), $args);
    }

    public function getDefaultArguments()
    {
        return null;
    }
}


