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
 * @package     Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_Inflector extends Zend_Filter_Inflector
{
    /**
     * Patched replacement logic: uses replacements provided in source even if
     * there is no corresponding rule in {@link $_rules}.
     *
     * @param string|array replacements
     * @return string
     * @throws Zend_Filter_Exception
     */
    public function filter($source)
    {
        // clean source
        foreach ( (array) $source as $sourceName => $sourceValue) {
            $source[ltrim($sourceName, ':')] = $sourceValue;
        }

        $pregQuotedTargetReplacementIdentifier = preg_quote($this->_targetReplacementIdentifier, '#');

        $processedParts = array();
        $rules = $source + $this->_rules;

        foreach (array_keys($rules) as $ruleName) {
            $ruleValue = isset($this->_rules[$ruleName]) ? $this->_rules[$ruleName] : $source[$ruleName];

            if (isset($source[$ruleName])) {
                if (is_string($ruleValue)) {
                    // overriding the set rule
                    $processedParts['#'.$pregQuotedTargetReplacementIdentifier.$ruleName.'#'] = str_replace('\\', '\\\\', $source[$ruleName]);
                } elseif (is_array($ruleValue)) {
                    $processedPart = $source[$ruleName];
                    foreach ($ruleValue as $ruleFilter) {
                        $processedPart = $ruleFilter->filter($processedPart);
                    }
                    $processedParts['#'.$pregQuotedTargetReplacementIdentifier.$ruleName.'#'] = str_replace('\\', '\\\\', $processedPart);
                }
            } elseif (is_string($ruleValue)) {
                $processedParts['#'.$pregQuotedTargetReplacementIdentifier.$ruleName.'#'] = str_replace('\\', '\\\\', $ruleValue);
            }
        }

        // all of the values of processedParts would have been str_replace('\\', '\\\\', ..)'d to disable preg_replace backreferences
        $inflectedTarget = preg_replace(array_keys($processedParts), array_values($processedParts), $this->_target);

        if ($this->_throwTargetExceptionsOn && (preg_match('#(?='.$pregQuotedTargetReplacementIdentifier.'[A-Za-z]{1})#', $inflectedTarget) == true)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('A replacement identifier ' . $this->_targetReplacementIdentifier . ' was found inside the inflected target, perhaps a rule was not satisfied with a target source?  Unsatisfied inflected target: ' . $inflectedTarget);
        }

        return $inflectedTarget;
    }
}
