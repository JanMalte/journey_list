<?php
/**
 * Test the plugin information of the Absence Manager
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
 * Test the plugin information of the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class PluginInfoTest extends TestCase
{

    /**
     * Test if the plugin information is valid.
     *
     * @return void
     */
    public function testPluginInfo()
    {
        $pluginInfo = absencemanager_info();

        $this->assertArrayHasKey('name', $pluginInfo);
        $this->assertArrayHasKey('description', $pluginInfo);
        $this->assertArrayHasKey('website', $pluginInfo);
        $this->assertArrayHasKey('author', $pluginInfo);
        $this->assertArrayHasKey('authorsite', $pluginInfo);
        $this->assertArrayHasKey('version', $pluginInfo);
        $this->assertArrayHasKey('guid', $pluginInfo);
        $this->assertArrayHasKey('compatibility', $pluginInfo);

        $this->assertRegExp('/malte-gerth/i', $pluginInfo['website']);
        $this->assertRegExp('/Malte Gerth/i', $pluginInfo['author']);
        $this->assertRegExp('/malte-gerth/i', $pluginInfo['authorsite']);
        $this->assertRegExp('/(^|,)18(\*|[0-9]+)($|,)/i', $pluginInfo['compatibility']);
    }
}
