<?php
Xi_Loader::loadClass('Xi_Factory');

/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory_Class extends Xi_Factory
{
    public function create($class, $args)
    {
        return Xi_Class::create($class, $args);
    }

    public function mapCreationArguments($args)
    {
        return array($this->getOption('class'), $args);
    }
}

