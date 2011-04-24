<?php
/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
interface Xi_Factory_Interface
{
    /**
     * Retrieve factory resource.
     *
     * @param null|array optional retrieval arguments
     */
    public function get($args = null);
}
