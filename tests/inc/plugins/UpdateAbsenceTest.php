<?php
/**
 * Test the update_absence() function of the Absence Manager
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
 * Test the update_absence() function of the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class UpdateAbsenceTest extends TestCase
{

    /**
     * Test if update_absence() calls the corresponding query to update the
     * existing absence.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testAddNewAbsenceCallsInsertQuery()
    {
        global $db;

        // Test values
        $absenceId = 15;
        $start = 1013407616;
        $end = 1413438941;
        $reason = 'Some testing reason';

        // Configure the mock object
        $db->expects($this->any())
            ->method('escape_string')
            ->will($this->returnArgument(0));
        $db->expects($this->once())
            ->method('update_query')
            ->with(
                'userabsences',
                array(
                    'start' => $start,
                    'end' => $end,
                    'reason' => $reason,
                ),
                'id = ' . $absenceId
            );

        // Call the function
        update_absence($absenceId, $start, $end, $reason);
    }
}
