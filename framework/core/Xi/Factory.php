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
 * @package     Xi_Factory
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Factory extends Xi_Factory_Injectable
{
    /**
     * @var null|Xi_Factory_Behaviour_Abstract
     */
    protected $_behaviour;

    /**
     * @var array behaviour name => class name
     */
    protected $_behaviourNames = array(
        'singleton' => 'Xi_Factory_Behaviour_Singleton',
        'cached'    => 'Xi_Factory_Behaviour_Cached',
        'config'    => 'Xi_Factory_Behaviour_Decorate_Config'
    );

    /**
     * Under which option to retrieve behaviours
     *
     * @var string
     */
    protected $_behaviourOptionKey = 'act_as';

    /**
     * Whether to automatically retrieve additional behaviours from options
     *
     * @var boolean
     */
    protected $_enableBehavioursFromOptions = true;

    /**
     * @var boolean whether behaviours have been applied to current get()
     */
    protected $_behavioursApplied = false;

    /**
     * On injection, retrieve additional behaviours from options if enabled in
     * {@link $_enableBehavioursFromOptions}.
     *
     * @param Xi_Locator
     * @return void
     */
    public function setLocator($locator)
    {
        parent::setLocator($locator);
        
        if ($this->_enableBehavioursFromOptions) {
            $this->addBehavioursFromOptions($this->_behaviourOptionKey);
        }
        
        if (isset($this->_behaviour)) {
            $this->_behaviour->setLocator($locator);
        }
    }

    /**
     * Retrieve additional behaviours from options
     *
     * @param string option key
     * @return void
     */
    public function addBehavioursFromOptions($key)
    {
        if ($this->hasOption($key)) {
            $behaviours = $this->getOption($key);
            /**
             * Possible input:
             * - name or class name
             * - array of names or class names
             * - Behaviour object
             * - array of Behaviour objects
             */
            $behaviours = is_object($behaviours) ? array($behaviours) : (array) $behaviours;
            foreach ($behaviours as $behaviour) {
                $this->actAs($behaviour);
            }
        }
    }

    /**
     * Add a behaviour to the list of behaviours to apply. Accepts a behaviour
     * name, a class name or a class instance.
     *
     * See {@link $_behaviourNames} for a list of allowed behaviour names.
     *
     * @param string|Xi_Factory_Behaviour_Abstract
     */
    public function actAs($behaviour)
    {
        $factory = isset($this->_behaviour) ? $this->_behaviour : $this;

        if (is_object($behaviour)) {
            $behaviour->setFactory($factory);
            $this->_behaviour = $behaviour;
            return $this;
        }

        if (isset($this->_behaviourNames[$behaviour])) {
            $behaviour = $this->_behaviourNames[$behaviour];
        }

        if (!class_exists($behaviour)) {
            throw new Xi_Factory_Exception('Unknown behaviour '.$behaviour);
        }

        $this->_behaviour = new $behaviour($factory);
        return $this;
    }

    /**
     * Intercepts factory call to apply behaviours
     *
     * @param null|array
     * @return mixed
     */
    public function get($args = null)
    {
        if (isset($this->_behaviour) && !$this->_behavioursApplied) {
            $this->_behavioursApplied = true;
            $retval = $this->_behaviour->get($args);
            $this->_behavioursApplied = false;
            return $retval;
        }
        return parent::get($args);
    }
}

