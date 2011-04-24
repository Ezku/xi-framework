<?php
/**
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_Word_SlashToCamelCase extends Zend_Filter_Word_SeparatorToCamelCase
{
    public function __construct()
    {
        parent::__construct('/');
    }
}

