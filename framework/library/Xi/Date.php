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
 * Extends PHP's DateTime to be able to deal with specific properties of the
 * date
 * 
 * @category    Xi
 * @package     Xi_Date
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */class Xi_Date
{
	/**
	 * @var int unix timestamp
	 */
	protected $_time;
	
	/**
	 * @var string presentation format
	 */
	protected $_format = 'Y-m-d H:i:s';
	
	/** 
	 * @param int|string $time unix timestamp or a string accepted by strtotime()
	 */
	public function __construct($time)
	{
	    if (((string) $time) !== ((string) ((int) $time))) {
	        $time = strtotime($time);
	    }
	    
	    $this->_time = (int) $time;
	}
	
    /**
     * @return int the unix timestamp represented by this Xi_Date object
     */
	public function getTimestamp()
	{
	    return $this->_time;
	}
	
	/**
	 * @param int $timestamp
	 * @return Xi_Date
	 */
	public function setTimestamp($timestamp)
	{
	    $this->_time = $timestamp;
	    return $this;
	}
	
	/**
	 * Get properties of current date. Similar to PHP's getdate() but weekdays
	 * and days of the year start from 1.
	 * 
	 * @return array
	 */
	public function getProperties()
	{
		$p = getdate($this->_time);
		// Weekdays start from monday as 1
		if (!$p['wday'])
		{
			$p['wday'] = 7;
		}
		
		// Day of year starts not from 0 but 1
		$p['yday']++;
		return $p;
	}
	
	/**
	 * Get $property from getProperties()
	 * 
	 * @param string $property
	 * @return mixed
	 */
	public function getProperty($property)
	{
		$p = $this->getProperties();
		return $p[$property];
	}
	
	/**
	 * Modifies the timestamp represented by this Xi_Date according to 'year',
	 * 'mday', 'mon', 'hours', 'minutes' and 'seconds' keys from $properties.
	 * The keys correspond to those output by getProperties().
	 * 
	 * Retrieves default values for properties from getProperties().
	 * 
	 * @param array $properties
	 * @return Xi_Date
	 */
	public function setProperties($properties)
	{
	    return $this->_setRawProperties($properties + $this->getProperties());
	}
	
	/**
	 * @param array $properties
	 * @return Xi_Date
	 */
	protected function _setRawProperties($properties)
	{
	    $this->_time = mktime(
	        $properties['hours'],
	        $properties['minutes'],
	        $properties['seconds'],
	        $properties['mon'],
	        $properties['mday'],
	        $properties['year']
        );
        return $this;
	}
	
	/**
	 * Modify the timestamp represented by this Xi_Date to reflect a change
	 * in one of the properties accepted by setProperties().
	 * 
	 * @param string $name
	 * @param int $value
	 * @return Xi_Date
	 */
	public function setProperty($name, $value)
	{
	    $this->setProperties(array($name => $value));
	}
	
	/**
	 * Set ISO 8601 year, week and day. Does not modify hour, minute and second
	 * components.
	 * 
	 * @param int $year
	 * @param int $week
	 * @param int $day optional, defaults to 1 (monday)
	 * @return Xi_Date
	 */
	public function setISODate($year, $week, $day = 1)
	{
	    $properties = $this->getProperties();
	    $this->_time = strtotime(sprintf("%04d-W%02d-%dT%02d:%02d:%02d", $year, $week, $day,
	        $properties['hours'], $properties['minutes'], $properties['seconds']));
	    return $this;
	}
	
	/**
	 * Set year, month and day. Does not modify hour, minute and second components.
	 * 
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 * @return Xi_Date
	 */
	public function setDate($year, $month, $day)
	{
	    return $this->setProperties(array(
	        'year' => $year,
	        'mon' => $month,
	        'mday' => $day
	    ));
	}
	
	/**
	 * @return int between 1 and 31
	 */
	public function getDayOfMonth()
	{
		return $this->getProperty('mday');
	}
	/**
	 * @param int $mday
	 * @return Xi_Date
	 */
	public function setDayOfMonth($mday)
	{
	    return $this->setProperty('mday', $mday);
	}
	
	/**
	 * @return int between 1 and 31
	 */
	public function getDaysInMonth()
	{
	    $properties = $this->getProperties();
		return self::daysInMonth($properties['year'], $properties['mon']);
	}
	
	/**
	 * @return int between 1 and 366
	 */
	public function getDayOfYear()
	{
		return $this->getProperty('yday');
	}
	
	/**
	 * @param int $yday
	 * @return Xi_Date
	 */
	public function setDayOfYear($yday)
	{
	    return $this->setProperties(array(
	        'mon' => 1,
	        'mday' => $yday
	    ));
	}
	
	/**
	 * Get ISO 8601 week number
	 * 
	 * @return int between 1 and 53
	 */
	public function getWeek()
	{
		return (int) $this->format('W');
	}
	
	/**
	 * Set ISO 8601 week number
	 * 
	 * @param int $wnumber
	 * @return Xi_Date
	 */
	public function setWeek($wnumber)
	{
	    $properties = $this->getProperties();
	    return $this->setISODate($properties['year'], $wnumber, $properties['wday']);
	}
	
	/**
	 * @return int between 1 and 7 (starts from monday)
	 */
	public function getDayOfWeek()
	{
		return $this->getProperty('wday');
	}
	
	/**
	 * @param int $weekday 1 (monday) through 7 (sunday)
	 * @return Xi_Date
	 */
	public function setDayOfWeek($weekday)
	{
	    $this->setISODate($this->getYear(), $this->getWeek(), $weekday);
		return $this;
	}
	
	/**
	 * @return int between 1 and 12
	 */
	public function getMonth()
	{
		return $this->getProperty('mon');
	}
	
	/**
	 * @param int
	 * @return Xi_Date
	 */
	public function setMonth($month)
	{
	    return $this->setProperty('mon', $month);
	}
	
	/**
	 * @return int
	 */
	public function getYear()
	{
		return $this->getProperty('year');
	}
	
	/**
	 * @param int
	 * @return Xi_Date
	 */
	public function setYear($year)
	{
	    return $this->setProperty('year', $year);
	}
	
	/**
	 * @return int
	 */
	public function getHour()
	{
	    return $this->getProperty('hours');
	}
	
	/**
	 * @param int $hour
	 * @return Xi_Date
	 */
	public function setHour($hour)
	{
	    return $this->setProperty('hours', $hour);
	}
	
	/**
	 * @return int
	 */
	public function getMinute()
	{
	    return $this->getProperty('minutes');
	}
	
	/**
	 * @param int $minute
	 * @return Xi_Date
	 */
	public function setMinute($minute)
	{
	    return $this->setProperty('minutes', $minute);
	}
	
	/**
	 * @return int
	 */
	public function getSecond()
	{
	    return $this->getProperty('seconds');
	}
	
	/**
	 * @param int $second
	 * @return Xi_Date
	 */
	public function setSecond($second)
	{
	    return $this->setProperty('seconds', $second);
	}
	
	/**
	 * Set default presentation format.
	 * 
	 * @param string $format
	 * @return Xi_Date
	 */
	public function setFormat($format)
	{
		$this->_format = $format;
		return $this;
	}
	
	/**
	 * Get default presentation format
	 * 
	 * @return string
	 */
	public function getFormat()
	{
		return $this->_format;
	}
	
	/**
	 * Format time as string. Use default format if not specified.
	 * 
	 * @param string $format optional
	 * @return string
	 */
	public function format($format = null)
	{
		if (!isset($format)) {
			$format = $this->_format;
		}
		return date($format, $this->_time);
	}
	
	/**
	 * Alter the timestamp. Accepts the same format as PHP's strotime().
	 * 
	 * @param string $description
	 * @return Xi_Date
	 */
	public function modify($description)
	{
		$this->_time = strtotime($description, $this->_time);
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function __toString()
	{
	    return $this->format();
	}
}