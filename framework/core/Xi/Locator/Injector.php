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
 * @package     Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Locator_Injector
{
    /**
     * @var Xi_Locator
     */
    protected $_locator;
    
    /**
     * Provide a Xi_Locator instance to inject objects with
     *
     * @param Xi_Locator $locator
     * @return void
     */
    public function __construct($locator)
    {
        $this->_locator = $locator;
    }

    /**
     * Recursively inject locator to Injectables in a collection
     *
     * @param array
     * @return void
     */
    public function inject($target)
    {
        if ($target instanceof Xi_Locator_Injectable_Interface) {
            $this->_injectLeaf($target);
        } elseif (is_array($target) || $target instanceof Iterator | $target instanceof IteratorAggregate) {
            $this->_injectBranch($target);
        }
    }

    /**
     * Injection procedure: handle leaf node
     *
     * @param Xi_Locator_Injectable_Interface
     * @return false
     */
    protected function _injectLeaf($target)
    {
        $target->setLocator($this->_locator);
    }

    /**
     * Injection procedure: handle branch node
     *
     * @param array|Xi_Array|Xi_Locator
     */
    protected function _injectBranch($target)
    {
        if ($target instanceof Xi_Locator) {
            $target = $target->getIterator(false);
        }
        
        foreach ($target as $t) {
            $this->inject($t);
        }
    }
}
