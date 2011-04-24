<?php
/**
 * Reads Yaml files using Horde_Yaml (based on Spyc and using the Syck extension if enabled)
 *
 * Section extensions in the Yaml format are denoted with '<':
 * <example>
 * section:
 *  - contents
 * another section < section:
 *  - contents
 * </example>
 *
 * @category    Xi
 * @package     Xi_Config
 * @subpackage  Xi_Config_Reader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Config_Reader_Yaml extends Xi_Config_Reader
{
    /**
     * @param string path to yaml file
     * @param null|string section to extract
     * @param null|boolean whether to interpret the file as PHP before parsing
     * @return array
     * @throws Xi_Config_Reader_Exception
     */
    public function __construct($filename, $section = null, $interpret = false)
    {
        if (!is_readable($filename)) {
            throw new Xi_Config_Reader_Exception('Configuration file ' . $filename . ' could not be read');
        }

        if (true == $interpret) {
            ob_start();
            include $filename;
            $input = ob_get_clean();
        } else {
            $input = file_get_contents($filename);
        }

        $yaml = array();
        try {
            $yaml = Horde_Yaml::load($input);
        } catch (Horde_Yaml_Exception $e) {
            throw new Xi_Config_Reader_Exception($filename .': '.$e->getMessage());
        }

        parent::__construct($yaml, $section);
    }

    public static function read($file, $section = null, $interpret = false)
    {
        return (array) new self($file, $section, $interpret);
    }
}

