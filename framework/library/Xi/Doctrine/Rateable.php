<?php
/**
 * @category    Xi
 * @package     Xi_Doctrine
 * @subpackage  Xi_Doctrine_Rateable
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Doctrine_Rateable extends Doctrine_Template
{
    /**
     * @var int
     */
    protected $_minimumRating = 0;
    
    /**
     * @var int
     */
    protected $_maximumRating = 5;
    
    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $options += array('minimumRating' => null, 'maximumRating' => null);
        $this->_setMinimumRating($options['minimumRating'])
             ->_setMaximumRating($options['maximumRating']);
             
        $this->_plugin = new Xi_Doctrine_Rateable_Rating();
        foreach ($options as $name => $value) {
            $this->_plugin->setOption($name, $value);
        }
    }
    
    /**
     * @param int|null $minimum
     * @return Xi_Doctrine_Rateable
     */
    protected function _setMinimumRating($minimum)
    {
        if (null !== $minimum) {
            $this->_minimumRating = $minimum;
        }
        return $this;
    }
    
    /**
     * @param int|null $maximum
     * @return Xi_Doctrine_Rateable
     */
    protected function _setMaximumRating($maximum)
    {
        if (null !== $maximum) {
            $this->_maximumRating = $maximum;
        }
        return $this;
    }
    
    public function setUp()
    {
        $this->_plugin->initialize($this->_table);
    }
    
    /**
     * @return Xi_Doctrine_Rateable_Rating
     */
    public function getRatingPlugin()
    {
        return $this->_plugin;
    }
    
    /**
     * @return int
     */
    public function getMinimumRating()
    {
        return $this->_minimumRating;
    }
    
    /**
     * @return int
     */
    public function getMaximumRating()
    {
        return $this->_maximumRating;
    }
    
    /**
     * @return { average: float, amount: int } 
     */
    public function getAverageRating()
    {
        return $this->_getRatingQuery()
            ->select('AVG(rating) as average, COUNT(*) as amount')
            ->groupBy($this->_getRatingToRateableForeignKey())
            ->fetchOne();
    }
    
    /**
     * @param int $user
     * @return boolean
     */
    public function hasBeenRatedBy($user)
    {
        return (boolean) $this->getRating($user);
    }
    
    /**
     * @param int $user
     * @return Xi_Doctrine_Rateable_Rating
     */
    public function getRating($user)
    {
        return $this->_getRatingQuery()
            ->addWhere('rater_id = ?', $user)
            ->fetchOne();
    }
    
    /**
     * Set user's rating of this Rateable to $rating
     * 
     * @param int $rating
     * @param int $user
     * @return Doctrine_Record
     */
    public function setRating($rating, $user)
    {
        $record = $this->getRating($user);
        if (empty($record)) {
            $record = $this->createRating($user);
        }
        $record->rating = $rating;
        $record->save();
        return $this->getInvoker();
    }
    
    /**
     * @param int $user
     * @return Doctrine_Record
     */
    public function createRating($user)
    {
        $table = $this->_plugin->getTable();
        $class = $table->getClassnameToReturn();
        
        $record = new $class();
        $record->{$this->_getRatingToRateableForeignKey()} = $this->getInvoker()->id;
        $record->rater_id = $user;
        
        return $record;
    }
    
    /**
     * TODO: Assumes that the foreign key is the first one to be listed in
     * the set of identifier columns. This is obviously fragile.
     * 
     * @return string
     */
    protected function _getRatingToRateableForeignKey()
    {
        $table = $this->_plugin->getTable();
        return current((array) $table->getIdentifier());
    }
    
    /**
     * @return Doctrine_Query
     */
    protected function _getRatingQuery()
    {
        $table = $this->_plugin->getTable();
        return Doctrine_Query::create()
            ->select('*')
            ->from($table->getComponentName())
            ->addWhere($this->_getRatingToRateableForeignKey() . ' = ?', $this->getInvoker()->id);
    }
}