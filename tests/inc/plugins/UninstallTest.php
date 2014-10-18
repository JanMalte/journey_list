<?php
/**
 * Test uninstalling the Absence Manager
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
 * Test uninstalling the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class UninstallTest extends TestCase
{

    /**
     * Test if the plugin removes the tables if uninstalled.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testUninstallRemovesTables()
    {
        global $db;

        // Configure the mock object
        $db->expects($this->once())
            ->method('drop_table')
            ->with('userabsences');

        // Call the uninstall function
        absencemanager_uninstall();
    }
}
