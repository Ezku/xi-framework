<?php
/**
 * @category    Xi
 * @package     Xi_Debug
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Debug extends Zend_Debug
{
    public static function dump($var, $label=null, $echo=true)
    {
        ob_start();
        if (function_exists('xdebug_var_dump')) {
            xdebug_var_dump($var);
        } else {
            parent::dump($var, $label, true);
        }
        $output = ob_get_clean();

        $output = self::applyPreTags($output);

        if ($echo) {
            echo $output;
        }
        return $output;
    }

    public static function applyPreTags($string)
    {
        if (self::getSapi() !== 'cli') {
            $string = '<pre>'.$string.'</pre>';
        }
        return $string;
    }
}
