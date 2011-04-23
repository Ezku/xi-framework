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
 * @package     Xi_View
 * @subpackage  Xi_View_Helper
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_View_Helper_Element extends Xi_View_Helper
{
    /**
     * @var string
     */
    protected $_elementFormat = '<%1$s%2$s>%3$s</%1$s>';
    
    /**
     * @var string
     */
    protected $_emptyElementFormat = '<%1$s%2$s />';
    
    /**
     * Format the string for an HTML element.
     * 
     * @param string element tag name (eg. "em")
     * @param string|false|null element contents; false for an empty element
     * @param array|null element attributes
     * @return string
     */
    public function element($element, $content = false, $attributes = array())
    {
        if (false === $content) {
            return sprintf($this->_emptyElementFormat, $element, $this->_formatAttributes($attributes));
        } else {
            return sprintf($this->_elementFormat, $element, $this->_formatAttributes($attributes), $content);
        }
    }
    
    /**
     * Format attributes for an HTML element
     * 
     * @param array
     * @return string
     */
    protected function _formatAttributes(array $attribs)
    {
        $xhtml = '';
        foreach ((array) $attribs as $key => $val) {
            $key = $this->view->escape($key);
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            $val = $this->view->escape($val);
            $xhtml .= " $key=\"$val\"";
        }
        return $xhtml;
    }
}

