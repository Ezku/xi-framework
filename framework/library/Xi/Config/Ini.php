<?php
/**
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Ini extends Xi_Config
{
    /**
     * @param string filename
     * @param null|string section
     * @param null|boolean allow modifications
     * @throws Xi_Config_Exception
     */
    public function __construct($filename, $section = null, $allowModifications = false)
    {
        $array = Xi_Config_Reader_Ini::read($filename, $section);
        parent::__construct($array, $allowModifications);
    }
}

