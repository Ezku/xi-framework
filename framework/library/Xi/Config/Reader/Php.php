<?php
Xi_Loader::loadClass('Xi_Config_Reader');

/**
 * @category    Xi
 * @package     Xi_Config
 * @subpackage  Xi_Config_Reader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Reader_Php extends Xi_Config_Reader
{
    public function __construct($filename, $section = null)
    {
        if (!Zend_Loader::isReadable($filename)) {
            throw new Xi_Config_Reader_Exception('Configuration file '.$filename.' was not readable');
        }
        $data = include $filename;
        parent::__construct($data, $section);
    }
}

