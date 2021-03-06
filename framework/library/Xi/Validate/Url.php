<?php
/**
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Validate_Url extends Xi_Validate_String
{
    const INVALID_SCHEME = 'invalidScheme';
    const INVALID_USERNAME = 'invalidUsername';
    const INVALID_PASSWORD = 'invalidPassword';
    const INVALID_HOST = 'invalidHost';
    const INVALID_PORT = 'invalidPort';
    const INVALID_PATH = 'invalidPath';
    const INVALID_QUERY = 'invalidQuery';
    const INVALID_FRAGMENT = 'invalidFragment';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_STRING          => "The value is not a string",
        self::INVALID_SCHEME      => "The URL has an invalid scheme",
        self::INVALID_USERNAME    => "The URL has an invalid username part",
        self::INVALID_PASSWORD    => "The URL has an invalid password part",
        self::INVALID_HOST        => "The URL has an invalid host",
        self::INVALID_PORT        => "The URL has an invalid port",
        self::INVALID_PATH        => "The URL has an invalid path",
        self::INVALID_QUERY       => "The URL has an invalid query string",
        self::INVALID_FRAGMENT    => "The URL has an invalid anchor fragment"
    );
    
    /**
     * @var string
     */
    protected $_scheme;
    
    /**
     * @var string $scheme
     */
    public function __construct($scheme = 'http')
    {
        $this->_scheme = $scheme;
    }
    
    /**
     * Checks whether $value is a valid Http Uri
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!parent::isValid($value)) {
            return false;
        }
        
        $this->_setValue($value);
        
        try {
            $url = Zend_Uri::factory($value);
        } catch (Zend_Uri_Exception $e) {
            $this->_error(self::INVALID_SCHEME);
            return false;
        }
        
        switch (false) {
            case $url->validateUsername():
                $this->_error(self::INVALID_USERNAME);
            break;
            case $url->validatePassword():
                $this->_error(self::INVALID_PASSWORD);
            break;
            case $url->validateHost():
                $this->_error(self::INVALID_HOST);
            break;
            case $url->validatePort():
                $this->_error(self::INVALID_PORT);
            break;
            case $url->validatePath():
                $this->_error(self::INVALID_PATH);
            break;
            case $url->validateQuery():
                $this->_error(self::INVALID_QUERY);
            break;
            case $url->validateFragment():
                $this->_error(self::INVALID_FRAGMENT);
            break;
            default:
                return true;
        }
        
        return false;
    }
}
