<?php
/**
 * @category    Xi
 * @package     Xi_Array
 * @subpackage  Xi_Array_Operation
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Array_Operation_Flatten
{
    /**
     * Flatten an array
     * 
     * @param array $array
     * @return array
     */
    public function execute($array)
    {
        $result = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                foreach ($this->execute($value) as $value) {
                    $result[] = $value;
                }
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }
}
