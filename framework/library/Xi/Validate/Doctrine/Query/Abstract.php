<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Validate_Doctrine_Query_Abstract extends Zend_Validate_Abstract
{
    /**
     * @var Doctrine_Query
     */
    protected $_query;
    
    /**
     * @param Doctrine_Query $query
     */
    public function __construct(Doctrine_Query $query)
    {
        $this->_query = $query;
    }
    
    /**
     * @return Doctrine_Query
     */
    public function getQuery()
    {
        return $this->_query;
    }
}