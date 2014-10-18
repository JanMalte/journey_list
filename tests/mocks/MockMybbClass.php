<?php
/**
 * Mock MyBB class
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
 * Mock MyBB class
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class MockMybbClass
{

    /**
     * Empty input container
     *
     * @var array
     */
    public $input = array();

    /**
     * Empty user container
     *
     * @var array
     */
    public $user = array();

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $variableName
     *
     * @return void
     */
    public function get_input($variableName)
    {
        return;
    }
}
