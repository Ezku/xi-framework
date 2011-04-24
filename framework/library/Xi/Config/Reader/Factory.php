<?php
/**
 * @category    Xi
 * @package     Xi_Config
 * @subpackage  Xi_Config_Reader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Config_Reader_Factory extends Xi_Factory
{
    public function create($filename, $namespace = null, $interpret = true)
    {
        if (null === $namespace) {
            $namespace = Xi_Environment::get();
        }

        $file = trim($this->getOption('directory'), '\\/')
                . DIRECTORY_SEPARATOR
                . $filename
                . $this->getOption('extension', '.php');

        if (!is_readable($file)) {
            return array();
        }

        $data = Xi_Config_Reader::read($file, $namespace, $interpret);

        if (!is_array($data)) {
            return array();
        }

        return $data;
    }
}

