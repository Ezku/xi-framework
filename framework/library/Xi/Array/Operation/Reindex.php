<?php
/**
 * Given a filter, transform a traversable value into an array whose indices
 * are the filtered values of the index' contents.
 * 
 * @category    Xi
 * @package     Xi_Array
 * @subpackage  Xi_Array_Operation
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Array_Operation_Reindex extends Xi_Filter_Outer
{
    /**
     * @param Traversable
     * @return array
     */
    public function execute($array)
    {
        $result = array();
        foreach ($array as $value) {
            $result[$this->filter($value)] = $value;
        }
        return $result;
    }
}
