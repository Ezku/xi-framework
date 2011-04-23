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
 * @category    Xi_Test
 * @package     Xi_Validate
 * @group       Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Validate_OrTest extends Xi_Validate_TestCase
{
    public function testReturnsFalseWhenEmpty()
    {
        $validator = new Xi_Validate_Or;
        $this->assertFalse($validator->isValid('foo'));
    }

    public function testReturnsTrueIfAnyValidatorPasses()
    {
        $one = $this->getValidatorMock(true);
        $validator = new Xi_Validate_Or(array($one));
        $this->assertTrue($validator->isValid('foo'));

        $two = $this->getValidatorMock(false);
        $validator = new Xi_Validate_Or(array($two, $one));
        $this->assertTrue($validator->isValid('foo'));
    }

    public function testReturnsFalseIfAllValidatorsFail()
    {
        $one = $this->getValidatorMock(false);
        $two = $this->getValidatorMock(false);
        $validator = new Xi_Validate_And(array($one, $two));
        $this->assertFalse($validator->isValid('foo'));
    }
}
