<?php
/**
 * Describes an object that can provide a Zend_Filter_Interface instance
 * 
 * @category    Xi
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
interface Xi_Filter_Aggregate
{
    /**
     * @return Zend_Filter_Interface
     */
    public function getFilter();
}

