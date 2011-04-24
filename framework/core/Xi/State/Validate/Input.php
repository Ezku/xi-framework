<?php
/**
 * Allows inner validator to validate based on the state machine's current
 * input. {@link isValid()} takes a Xi_Event and passes the input retrieved from
 * the event's context to the inner validator.
 *
 * @category    Xi
 * @package     Xi_State
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_State_Validate_Input extends Xi_Validate_Outer
{
    public function isValid($value)
    {
        if (!$value instanceof Xi_Event) {
            return false;
        }
        $value = $value->getContext();
        return ($value instanceof Xi_State_Machine) && parent::isValid($value->getInput());
    }
}
