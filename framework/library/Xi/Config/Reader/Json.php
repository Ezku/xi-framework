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
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
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

