<?php
/**
 * Absence Manager MyBB Plugin Phpunit bootstraping
 *
 * PHP version 5.3
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */

// Define IN_MYBB for getting access to the plugin files without loading a full
// MyBB instance
defined('IN_MYBB') || define('IN_MYBB', true);

// Define the source path
defined('SOURCE') || define('SOURCE', dirname(__DIR__) . '/src/');

// Require the base test case class
require_once __DIR__ . '/classes/TestCase.php';

// Mock some used MyBB functions
if (!function_exists('my_date')) {

    /**
     * Mock function for my_date().
     *
     * Concat the two parameter, seperated by "::" and surrounded by
     * "{{" and "}}".
     *
     * @param string  $dateformat
     * @param integer $timestamp
     *
     * @return string
     */
    function my_date($dateformat, $timestamp)
    {
        return '{{' . $dateformat . '::' . $timestamp . '}}';
    }
}
