<?php
/**
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Event_Listener_Interface
{
    /**
     * @param Xi_Event
     * @return mixed
     */
    public function invoke($event);
}
