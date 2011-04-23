<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * @category    Xi
 * @package     Xi_Config_Reader
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Config_Reader extends ArrayObject
{
    /**
     * @var array extension => reader class
     */
    static protected $_readers = array(
      'yml'     =>  'Xi_Config_Reader_Yaml',
      'ini'     =>  'Xi_Config_Reader_Ini',
      'json'    =>  'Xi_Config_Reader_Json',
      'php'     =>  'Xi_Config_Reader_Php'
    );

    /**
     * Character that defines section extension in a key string
     *
     * @var string
     */
    protected $_extensionOperator = '<';

    /**
     * @param array data
     * @param null|string section to extract
     * @param null|string section to default to (only used if section not null)
     * @return void
     */
    public function __construct($data, $section = null, $defaultSection = 'all')
    {
        $data = $this->_process($data);
        if (!empty($section)) {
            if (isset($data[$section])) {
                $data = $data[$section];
            } elseif (isset($data[$defaultSection])) {
                $data = $data[$defaultSection];
            } else {
                throw new Xi_Config_Reader_Exception('Section "' . $section . '" could not be found');
            }
        }
        parent::__construct($data);
    }

    /**
     * Process data - handle section extension
     *
     * @param array data
     * @return array processed data
     */
    protected function _process($data)
    {
        /**
         * Process extensions
         */
        $processedData = array();
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $value = array();
            }
            if (strpos($key, $this->_extensionOperator)) {
                list($child, $parent) = explode($this->_extensionOperator, $key);
                $child  = trim($child);
                $parent = trim($parent);
                if (strlen($parent)) {
                    if (empty($data[$parent])) {
                        $data[$parent] = array();
                    }
                    $value += (array) $data[$parent];
                }
                $key = $child;
            }
            $processedData[$key] = $value;
        }

        return $processedData;
    }

    /**
     * Try to read a configuration file. Will automatically choose the correct
     * reader based on the file's extension and the defined readers.
     *
     * Returns false if no reader found.
     *
     * @param string filename
     * @param null|mixed additional reader constructor argument
     * ...
     * @return false|array
     */
    public static function read($file)
    {
        $args = func_get_args();

        $ext = substr($file, strpos($file, '.')+1);
        $reader = self::getReader($ext);
        if (!$reader) {
            return false;
        }

        return (array) Xi_Class::create($reader, $args);
    }

    /**
     * Set reader for config file type identified by its extension
     *
     * @param string extension (without .)
     * @param string class name
     * @return void
     */
    public static function setReader($extension, $class)
    {
        self::$_readers[$extension] = $class;
    }

    /**
     * Get reader for config file type
     *
     * @param string extension (without .)
     * @return null|string
     */
    public static function getReader($extension)
    {
        if (isset(self::$_readers[$extension])) {
            return self::$_readers[$extension];
        }
        return false;
    }

    /**
     * Get all readers
     *
     * @return array
     */
    public static function getReaders()
    {
        return self::$_readers;
    }
}

