<?php
/**
 * @category    Xi
 * @package     Xi_Event
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Xi_Event_Listener_Interface
{
    /**
     * @param Xi_Event
     * @return mixed
     */
    public function invoke($event);
}
