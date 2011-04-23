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

Xi_Loader::loadInterface('Xi_Factory_Behaviour_Interface');

/**
 * @category    Xi
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Factory_Behaviour_Composite implements Xi_Factory_Behaviour_Interface
{
    /**
     * @var Xi_Locator
     */
    protected $_locator;

    /**
     * @var Xi_Factory_Interface
     */
    protected $_primary;

    /**
     * @var Xi_Factory_Interface
     */
    protected $_secondary;

    public function __construct(Xi_Factory_Interface $primary)
    {
        $this->_primary = $primary;
    }

    public function setLocator($locator)
    {
        $this->_locator = $locator;
        $this->_primary->setLocator($locator);
        if ($this->_secondary instanceof Xi_Factory_Behaviour_Interface) {
            $this->_secondary->setLocator($locator);
        }
    }

    public function setFactory(Xi_Factory_Interface $secondary)
    {
        $this->_secondary = $secondary;
    }
}
