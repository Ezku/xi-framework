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
 * Horizontal line module
 */
class TexyHorizLineModule extends TexyModule
{
    protected $syntax = array('horizline' => TRUE);


    public function begin()
    {
        $this->texy->registerBlockPattern(
            array($this, 'pattern'),
            '#^(?:\*{3,}|-{3,})\ *'.TEXY_MODIFIER.'?()$#mU',
            'horizline'
        );
    }



    /**
     * Callback for: -------
     *
     * @param TexyBlockParser
     * @param array      regexp matches
     * @param string     pattern name
     * @return TexyHtml
     */
    public function pattern($parser, $matches)
    {
        list(, $mMod) = $matches;
        //    [1] => ---
        //    [2] => .(title)[class]{style}<>

        $tx = $this->texy;
        $el = TexyHtml::el('hr');
        $mod = new TexyModifier($mMod);
        $mod->decorate($tx, $el);

        // event listener
        if (is_callable(array($tx->handler, 'afterHorizline')))
            $tx->handler->afterHorizline($parser, $el, $mod);

        return $el;
    }

}
