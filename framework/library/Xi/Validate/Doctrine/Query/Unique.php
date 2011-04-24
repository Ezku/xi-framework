<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Doctrine_Query_Unique extends Xi_Validate_Doctrine_Query_Abstract
{
    const NOT_UNIQUE = 'not unique';

    protected $_messageTemplates = array(
        self::NOT_UNIQUE => "This name is already in use"
    );
    
    protected $_exclude = array();
    
    /**
     * @param Doctrine_Query $query
     * @param mixed|array $exclude optional
     */
    public function __construct(Doctrine_Query $query, $exclude = array())
    {
        parent::__construct($query);
        $this->_exclude = (array) $exclude;
    }
    
    /**
     * Checks that the row returned by the query either is empty or has an id
     * provided in the exclusion list
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $result = $this->getQuery()->fetchOne(array($value));
        if (!empty($result)) {
            $id = $result->identifier();
            
            if (count($id) === 1) {
                $id = array_pop($id);
            }
            
            if (!in_array($id, $this->_exclude)) {
                $this->_error(self::NOT_UNIQUE);
                return false;
            }
        }
        return true;
    }
}