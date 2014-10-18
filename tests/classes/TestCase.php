<?php
/**
 * Base test case for the Absence Manager
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
 * Base test case for the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * Set up the tests.
     *
     * @global MockPluginClass   $plugins
     * @global MockDatabaseClass $db
     * @global MockTemplateClass $templates
     * @global MockMybbClass     $mybb
     *
     * @return void
     */
    public function setUp()
    {
        global $plugins;
        global $db;
        global $templates;
        global $mybb;

        // Create a mock object for the MyBB plugin system
        require_once dirname(__DIR__) . '/mocks/MockPluginClass.php';
        $plugins = $this->getMock('MockPluginClass');

        // Create a mock object for the MyBB database system
        require_once dirname(__DIR__) . '/mocks/MockDatabaseClass.php';
        $db = $this->getMock('MockDatabaseClass');

        // Create a mock object for the MyBB template system
        require_once dirname(__DIR__) . '/mocks/MockTemplateClass.php';
        $templates = $this->getMock('MockTemplateClass');

        // Create a mock object for the MyBB main system
        require_once dirname(__DIR__) . '/mocks/MockMybbClass.php';
        $mybb = $this->getMock('MockMybbClass');

        // Include the file to test
        require_once SOURCE . '/inc/plugins/absencemanager.php';

        // Call the parent test case setup
        parent::setUp();
    }
}
