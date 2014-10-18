<?php
/**
 * Test the installation of the Absence Manager
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
 * Test the installation of the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class InstallTest extends TestCase
{

    /**
     * Test if the plugin creates the tables.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testInstallCreatesTables()
    {
        global $db;

        // Test value
        $createTableSql = 'CREATE TABLE IF NOT EXISTS '
            . $db->table_prefix . 'userabsences';

        // Configure the mock object
        $db->expects($this->once())
            ->method('write_query')
            ->with($this->stringContains($createTableSql));

        // Call the install function
        absencemanager_install();
    }
}
