<?php
/**
 * Mock plugin class
 *
 * PHP version 5.3
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */

/**
 * Mock plugin class
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class MockPluginClass
{

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $name
     * @param mixed $function
     *
     * @return void
     */
    public function add_hook($name, $function)
    {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $name
     * @param mixed $mixed
     * @param mixed $throwError
     *
     * @return void
     */
    public function load($name, $mixed, $throwError)
    {
        return;
    }
}
