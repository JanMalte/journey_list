<?php
/**
 * Test the registration of hooks of the Absence Manager
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
 * Test the registration of hooks of the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class RegisterHooksTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test if the plugin information is valid.
     *
     * @return void
     */
    public function testHooks()
    {
        // Get the plugin mock object to test the registration of hooks
        $plugins = $this->getMock('MockPluginClass');

        // Configure the mock object
        $plugins->expects($this->exactly(3))
            ->method('add_hook')
            ->withConsecutive(
                 array('member_profile_end', 'add_absence_profile_info'),
                 array('usercp_do_profile_end', 'save_absence_by_native_away_setting'),
                 array('usercp_do_profile_end', 'finish_absence_by_native_away_setting')
             );

        // Include the file to test
        require SOURCE . '/inc/plugins/absencemanager.php';
    }
}
