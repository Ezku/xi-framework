<?php
/**
 * @category    Xi
 * @package     Xi_Scheduler
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Scheduler_Startup extends Xi_Scheduler
{
    public function __construct($registry)
    {
        $this->_registry = $registry;
    }

    public function getRegistry()
    {
        return $this->_registry;
    }

    public function __sleep()
    {
        return array('_registry', '_jobs');
    }
}