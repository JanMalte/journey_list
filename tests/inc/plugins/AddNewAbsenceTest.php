<?php
/**
 * Test the add_new_absence() function of the Absence Manager
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
 * Test the add_new_absence() function of the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class AddNewAbsenceTest extends TestCase
{

    /**
     * Test if add_new_absence() calls the corresponding query to create the
     * absence.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testAddNewAbsenceCallsInsertQuery()
    {
        global $db;

        // Test values
        $userId = 15;
        $start = 1013407616;
        $end = 1413438941;
        $reason = 'Some testing reason';

        // Configure the mock object
        $db->expects($this->any())
            ->method('escape_string')
            ->will($this->returnArgument(0));
        $db->expects($this->once())
            ->method('insert_query')
            ->with(
                'userabsences',
                array(
                    'user_id' => $userId,
                    'start' => $start,
                    'end' => $end,
                    'reason' => $reason,
                )
            );

        // Call the function
        add_new_absence($userId, $start, $end, $reason);
    }
}
