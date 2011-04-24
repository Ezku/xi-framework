<?php
/**
 * Describes an object to which listeners can be attached
 * 
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
interface Xi_Event_Subject_Interface
{
    /**
     * Attach event listener to observable subject
     *
     * @param Xi_Event_Listener_Interface
     * @return Xi_Event_Subject_Interface
     */
    public function attach($listener);
}
