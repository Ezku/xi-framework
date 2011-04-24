<?php
Xi_Loader::loadClass('Xi_Config');

/**
 * Configuration container using a Yaml reader.
 * 
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Yaml extends Xi_Config
{
    /**
     * @param string filename
     * @param null|string section
     * @param null|boolean allow modifications
     * @param null|boolean whether to interpret the file as PHP before parsing
     * @throws Xi_Config_Exception
     */
    public function __construct($filename, $section = null, $allowModifications = false, $interpret = false)
    {
        $array = Xi_Config_Reader_Yaml::read($filename, $section, $interpret);
        parent::__construct($array, $allowModifications);
    }
}

