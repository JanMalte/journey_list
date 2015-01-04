<?php
/**
 * Mock database class
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
 * Mock database class
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class MockDatabaseClass
{

    /**
     * Empty mock attribute
     *
     * @var mixed
     */
    public $table_prefix;

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $query
     *
     * @return void
     */
    public function write_query($query)
    {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $table
     *
     * @return void
     */
    public function drop_table($table)
    {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $table
     *
     * @return void
     */
    public function table_exists($table)
    {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $table
     * @param mixed $fields
     * @param mixed $conditions
     * @param mixed $options
     *
     * @return void
     */
    public function simple_select(
        $table,
        $fields = null,
        $conditions = null,
        $options = null
    ) {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $query
     * @param mixed $resulttype
     *
     * @return void
     */
    public function fetch_array($query, $resulttype = null)
    {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param string $string
     *
     * @return string
     */
    public function escape_string($string)
    {
        return (string) $string;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $table
     * @param mixed $array
     *
     * @return void
     */
    public function insert_query($table, $array)
    {
        return;
    }

    /**
     * Just do nothing, as this is just a mocking class.
     *
     * @param mixed $table
     * @param mixed $array
     *
     * @return void
     */
    public function update_query($table, $array)
    {
        return;
    }

	/**
	 * Just do nothing, as this is just a mocking class.
	 *
	 * @param string The table name to perform the query on.
	 * @param string An optional where clause for the query.
	 * @param string An optional limit clause for the query.
     *
	 * @return void
	 */
	function delete_query($table, $where="", $limit="")
	{
		return;
	}
}
