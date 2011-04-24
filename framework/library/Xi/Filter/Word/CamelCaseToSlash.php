<?php
/**
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Word_CamelCaseToSlash extends Zend_Filter_Word_CamelCaseToSeparator
{
    public function __construct()
    {
        parent::__construct('/');
    }
}

