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
 * @package     Xi_Doctrine
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Doctrine_Rateable_Rating extends Doctrine_Record_Generator
{
    
    public function initOptions()
    {
        $this->_options += array(
            'className' => '%CLASS%Rating',
            'type'		=> 'integer',
            'length'	=> 4,
            'options'	=> array(),
        );
    }

    public function buildRelation()
    {
    	$this->buildForeignRelation('Ratings');
        $this->buildLocalRelation();
    }
    
    public function setUp()
    {
        $this->hasForeignKeyColumnFor($this->_options['table'], array('primary' => true));
        $this->hasColumn('rater_id', 'integer', 4, array('primary' => true));
        $this->hasColumn(
        	'rating',
            $this->getOption('type'),
            $this->getOption('length'),
            $this->getOption('options') + array('notnull' => true)
        );
    }
    
    /**
     * @param Doctrine_Table $table
     * @param array $options optional
     * @return Xi_Doctrine_Rateable_Rating
     */
    public function hasForeignKeyColumnFor($table, array $options = array())
    {
        $foreignKeyDefinition = $options + $this->getForeignKeyDefinition($table);
        $this->hasColumn(
            $this->getForeignKeyName($table),
            $foreignKeyDefinition['type'],
            $foreignKeyDefinition['size'],
            $foreignKeyDefinition
        );
        return $this;
    }
    
    /**
     * @param Doctrine_Table $table
     * @return string
     */
    public function getForeignKeyName($table)
    {
        return $table->getIdentifier();
    }
    
    /**
     * @param Doctrine_Table $table
     * @return array
     */
    public function getForeignKeyDefinition($table)
    {
        $definition = $table->getColumnDefinition($table->getIdentifier());
        $definition['autoincrement'] = false;
        return $definition;
    }
}