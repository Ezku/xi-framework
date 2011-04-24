<?php
/**
 * @category    Xi
 * @package     Xi_Doctrine
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
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