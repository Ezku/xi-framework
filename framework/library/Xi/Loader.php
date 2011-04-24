<?php
/**
 * @category    Xi
 * @package     Xi_Loader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Loader
{
    const FILE_EXTENSION = '.php';

    /**
     * @var array
     */
    protected static $_directories = array('' => array());

    /**
     * Add a directory to be used with autoloading. Optionally provide a prefix
     * for classes inside this directory.
     *
     * @param array|string directory
     * @param null|string class name prefix
     * @return void
     */
    public static function addDirectory($directories, $prefix = null)
    {
        $prefix = ($prefix === (string) $prefix) ? trim($prefix, '\\/_') : '';
        foreach ((array) $directories as $dir) {
            if (!isset(self::$_directories[$prefix])) {
                self::$_directories[$prefix] = array();
            }
            self::$_directories[$prefix][] = $dir;
        }
    }

    /**
     * @return array
     */
    public static function getDirectories()
    {
        $dirs = self::$_directories;
        $dirs[''] = array_merge(empty($dirs['']) ? array() : $dirs[''], explode(PATH_SEPARATOR, get_include_path()));
        return $dirs;
    }

    /**
     * Attempt to autoload a given class or interface: a file name is acquired
     * by naively transforming underscores into directory separators and
     * appending the .php suffix.
     *
     * The file is searched for in every directory in the include path.
     *
     * @param string class or interface name
     * @return boolean whether class or interface was successfully loaded
     */
    public static function autoload($class)
    {
        try {
            $defaultFilename = DIRECTORY_SEPARATOR
                               . str_replace('_', DIRECTORY_SEPARATOR, $class)
                               . self::FILE_EXTENSION;
            foreach (self::getDirectories() as $prefix => $dirs) {
                /**
                 * Check for class prefix
                 */
                if (strlen($prefix)) {
                    /**
                     * Skip if prefix does not match
                     */
                    if (0 !== strpos ($class, $prefix)) {
                        continue;
                    }
                    $filename = DIRECTORY_SEPARATOR
                              . str_replace('_', DIRECTORY_SEPARATOR, str_replace($prefix, '', $class))
                              . self::FILE_EXTENSION;
                } else {
                    $filename = $defaultFilename;
                }

                /**
                 * Scan directories
                 */
                foreach ($dirs as $dir) {
                    if (file_exists($dir . $filename)) {
                        require_once $dir . $filename;
                        if (class_exists($class, false) || interface_exists($class, false)) {
                            return true;
                        }
                        break;
                    }
                }
            }
        } catch (Exception $e) {
            /**
             * Swallow exceptions to avoid fatal errors
             */
        }
        return false;
    }

    /**
     * Load a given class: a file name is acquired by naively transforming
     * underscores into directory separators and appending the .php suffix.
     *
     * The file is searched for in every directory in the include path.
     *
     * @param string class name
     * @param boolean allow class to be autoloaded before attempt
     * @return true
     * @throws Xi_Exception if the class could not be loaded
     */
    public static function loadClass($class, $allowAutoload = false)
    {
        if (class_exists($class, (boolean) $allowAutoload)) {
            return true;
        }

        $defaultFilename = DIRECTORY_SEPARATOR
                           . str_replace('_', DIRECTORY_SEPARATOR, $class)
                           . self::FILE_EXTENSION;
        foreach (self::getDirectories() as $prefix => $dirs) {
            /**
             * Check for class prefix
             */
            if (strlen($prefix)) {
                /**
                 * Skip if prefix does not match
                 */
                if (0 !== strpos ($class, $prefix)) {
                    continue;
                }
                $filename = DIRECTORY_SEPARATOR
                          . str_replace('_', DIRECTORY_SEPARATOR, str_replace($prefix, '', $class))
                          . self::FILE_EXTENSION;
            } else {
                $filename = $defaultFilename;
            }

            /**
             * Scan directories
             */
            foreach ($dirs as $dir) {
                if (file_exists($dir . $filename)) {
                    require_once $dir . $filename;
                    if (class_exists($class, false)) {
                        return true;
                    }
                    break;
                }
            }
        }

        throw new Xi_Exception('Class ' . $class . ' could not be loaded.');
    }

    /**
     * Attempt to find definition for class in a set of root paths.
     *
     * @param string class name
     * @param string|array paths to look in
     * @return boolean class was found
     */
    public static function findClass($class, $paths)
    {
        if (!is_string($class)) {
            return false;
        }

        if (class_exists($class, true)) {
            return true;
        }

        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . self::FILE_EXTENSION;

        foreach ((array) $paths as $path) {
            if (!is_readable($path . DIRECTORY_SEPARATOR . $file)) {
                continue;
            }
            include_once $path . DIRECTORY_SEPARATOR . $file;
            if (class_exists($class, false)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load a given interface: a file name is acquired by naively transforming
     * underscores into directory separators and appending the .php suffix.
     *
     * The file is searched for in every directory in the include path.
     *
     * @param string interface name
     * @param boolean allow interface to be autoloaded before attempt
     * @return true
     * @throws Xi_Exception if the interface could not be loaded
     */
    public static function loadInterface($interface, $allowAutoload = false)
    {
        if (interface_exists($interface, (boolean) $allowAutoload)) {
            return true;
        }

        $defaultFilename = DIRECTORY_SEPARATOR
                           . str_replace('_', DIRECTORY_SEPARATOR, $interface)
                           . self::FILE_EXTENSION;
        foreach (self::getDirectories() as $prefix => $dirs) {
            /**
             * Check for class prefix
             */
            if (strlen($prefix)) {
                /**
                 * Skip if prefix does not match
                 */
                if (0 !== strpos ($interface, $prefix)) {
                    continue;
                }
                $filename = DIRECTORY_SEPARATOR
                          . str_replace('_', DIRECTORY_SEPARATOR, str_replace($prefix, '', $interface))
                          . self::FILE_EXTENSION;
            } else {
                $filename = $defaultFilename;
            }

            /**
             * Scan directories
             */
            foreach ($dirs as $dir) {
                if (file_exists($dir . $filename)) {
                    require_once $dir . $filename;
                    if (interface_exists($interface, false)) {
                        return true;
                    }
                    break;
                }
            }
        }

        throw new Xi_Exception('Interface ' . $interface . ' could not be loaded.');
    }
}

