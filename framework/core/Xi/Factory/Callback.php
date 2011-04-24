<?php
Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Callback extends Xi_Factory
{
    public function create($callback, $args)
    {
        if (!is_callable($callback)) {
            throw new Xi_Factory_Exception('Could not execute callback because it is not valid');
        }
        return call_user_func_array($callback,
                                    $args);
    }

    public function mapCreationArguments($args)
    {
        return array($this->getOption('callback'), $args);
    }
}

