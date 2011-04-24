<?php
/**
 * @category    Xi
 * @package     Xi_Array
 * @subpackage  Xi_Array_Operation
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Array_Operation_Select
{
    /**
     * @var string|array
     */
    protected $_fields;
    
    /**
     * @param string|array $fields
     */
    public function __construct($fields)
    {
        $this->_fields = $fields;
    }
    
    /**
     * Select field or fields from data
     *
     * @param Traversable|Traversable<array|ArrayAccess> $array
     * @return array
     */
    public function execute($array)
    {
        $result = array();

        $key = $this->_fields;
        
        if (is_array($key)) {
            foreach ($array as $v) {
                if (is_array($v) || $v instanceof ArrayAccess) {
                    $r = array();
                    foreach ($key as $k) {
                        if (isset($v[$k])) {
                             $r[$k] = $v[$k];
                        }
                    }
                    $result[] = $r;
                }
            }
        }

        foreach ($array as $v) {
            if (isset($v[$key])) {
                $result[] = $v[$key];
            }
        }

        return $result;
    }
}
