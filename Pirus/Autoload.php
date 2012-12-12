<?php
/**
 * Registers autoloader for Pirus and all other dependencies
 *
 * PHP version 5
 *
 * @category Pirum
 * @package  Pirus
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version  GIT: $Id$
 * @link     http://php5.laurent-laville.org/pirus/
 */

/**
 * Autoloader for Pirus
 *
 * @param string $className name of the class (and namespace) being instantiated.
 *
 * @return void
 */
function Pirus_autoload($className)
{
    static $classes = null;
    static $path    = null;

    if ($classes === null) {

        $classes = array(
            'Pirus_CLI'
                => 'Pirus/CLI.php',
            'Pirus_Builder'
                => 'Pirus/CLI.php',
        );
        $path = dirname(dirname(__FILE__));
    }

    if (isset($classes[$className])) {
        include $path . '/' . $classes[$className];

    } elseif (substr($className, 0, 5) == 'Pirum') {
        if (strpos('@bin_dir@', '@') === 0) {
            // stand-alone version is running
            include $path . '/vendor/pirum';
        } else {
            // PEAR installed version is running
            include '@bin_dir@/pirum';
        }

    } else {
        include str_replace('_', '/', $className) . '.php';
    }

}

spl_autoload_register('Pirus_autoload');
