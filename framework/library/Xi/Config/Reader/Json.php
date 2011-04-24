<?php
/**
 * Reads Json files using Zend_Json. Supports section extension:
 * 
 * <example>
 * {
 *    "one":       { "foo": "bar" },
 *    "two < one": { }
 * }
 * </example>
 *    
 * 
 * @category    Xi
 * @package     Xi_Config
 * @subpackage  Xi_Config_Reader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Reader_Json extends Xi_Config_Reader
{
    public function __construct($filename, $section = null)
    {
        if (!Zend_Loader::isReadable($filename)) {
            throw new Xi_Config_Reader_Exception('Configuration file '.$filename.' was not readable');
        }
        
        $input = file_get_contents($filename);
        $json = (array) Zend_Json::decode($input);
        
        parent::__construct($json, $section);
    }
    
    public static function read($file, $section = null)
    {
        return (array) new self($file, $section);
    }
}

