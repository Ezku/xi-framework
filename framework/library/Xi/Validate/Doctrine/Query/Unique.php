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
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_Doctrine_Query_Unique extends Xi_Validate_Doctrine_Query_Abstract
{
    const NOT_UNIQUE = 'not unique';

    protected $_messageTemplates = array(
        self::NOT_UNIQUE => "This name is already in use"
    );
    
    protected $_exclude = array();
    
    /**
     * @param Doctrine_Query $query
     * @param mixed|array $exclude optional
     */
    public function __construct(Doctrine_Query $query, $exclude = array())
    {
        parent::__construct($query);
        $this->_exclude = (array) $exclude;
    }
    
    /**
     * Checks that the row returned by the query either is empty or has an id
     * provided in the exclusion list
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $result = $this->getQuery()->fetchOne(array($value));
        if (!empty($result)) {
            $id = $result->identifier();
            
            if (count($id) === 1) {
                $id = array_pop($id);
            }
            
            if (!in_array($id, $this->_exclude)) {
                $this->_error(self::NOT_UNIQUE);
                return false;
            }
        }
        return true;
    }
}