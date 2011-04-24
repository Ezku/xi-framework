<?php
/**
 * A plain ini file reader that supports extending sections similar to
 * Zend_Config_Ini.
 * 
 * <example>
 * [one]
 * foo = bar
 * 
 * [two : one]
 * ; extends from 'one'
 * </example>
 * 
 * @category    Xi
 * @package     Xi_Config
 * @subpackage  Xi_Config_Reader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */

class Xi_Config_Reader_Ini extends Xi_Config_Reader
{
    protected $_extensionOperator = ':';
    
    public function __construct($file, $extractSection = null)
    {
        if (!is_readable($file)) {
            throw new Xi_Config_Reader_Exception('Configuration file ' . $file . ' could not be read');
        }
        
        $ini = parse_ini_file($file, true);
        
        parent::__construct($ini, $extractSection);
    }
    
    public static function read($file, $section = null)
    {
        return (array) new self($file, $section);
    }
}

