<?php

/**
 * This file is part of the Texy! formatter (http://texy.info/)
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004-2007 David Grudl aka -dgx- (http://www.dgx.cz)
 * @license    GNU GENERAL PUBLIC LICENSE version 2
 * @version    $Revision: 47 $ $Date: 2008-01-16 20:32:20 +0200 (ke, 16 tammi 2008) $
 * @category   Text
 * @package    Texy
 */

// security - include texy.php, not this file
if (!class_exists('Texy', FALSE)) die();



/**
 * Texy! modules base class
 */
abstract class TexyModule
{
    /** @var Texy */
    protected $texy;

    /** @var array  list of syntax to allow */
    protected $syntax = array();



    public function __construct($texy)
    {
        $this->texy = $texy;
        $texy->registerModule($this);
        $texy->allowed = array_merge($texy->allowed, $this->syntax);
    }


    /**
     * Called by $texy->parse
     */
    public function begin()
    {}


    /**#@+
     * Access to undeclared property
     * @throws Exception
     */
    function __get($name) { throw new Exception("Access to undeclared property: " . get_class($this) . "::$$name"); }
    function __set($name, $value) { throw new Exception("Access to undeclared property: " . get_class($this) . "::$$name"); }
    function __unset($name) { throw new Exception("Access to undeclared property: " . get_class($this) . "::$$name"); }
    /**#@-*/

}




interface ITexyPreBlock
{
    /**
     * Single block pre-processing
     * @param string
     * @param bool
     * @return string
     */
    public function preBlock($block, $topLevel);
}


interface ITexyPostLine
{
    /**
     * Single line post-processing
     * @param string
     * @return string
     */
    public function postLine($line);
}
