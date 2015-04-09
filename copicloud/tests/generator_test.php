<?php


/**
 * PHPUnit data generator tests.
 *
 * @package mod_copicloud
 * @category phpunit
 */

defined('MOODLE_INTERNAL') || die();


/**
 * PHPUnit data generator testcase.
 *
 * @package mod_copicloud
 * @category phpunit
 * @copyright 2013 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_copicloud_generator_testcase extends advanced_testcase {
    public function test_generator() {
        global $DB, $SITE;

        $this->resetAfterTest(true);

        // Must be a non-guest user to create copiclouds.
        $this->setAdminUser();

        // There are 0 copiclouds initially.
        $this->assertEquals(0, $DB->count_records('copicloud'));

        // Create the generator object and do standard checks.
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_copicloud');
        $this->assertInstanceOf('mod_rcopicloud_generator', $generator);
        $this->assertEquals('copicloud', $generator->get_modulename());

        // Create three instances in the site course.
        $generator->create_instance(array('course' => $SITE->id));
        $generator->create_instance(array('course' => $SITE->id));
        $copicloud = $generator->create_instance(array('course' => $SITE->id));
        $this->assertEquals(3, $DB->count_records('copicloud'));

        // Check the course-module is correct.
        $cm = get_coursemodule_from_instance('copicloud', $copicloud->id);
        $this->assertEquals($copicloud->id, $cm->instance);
        $this->assertEquals('copicloud', $cm->modname);
        $this->assertEquals($SITE->id, $cm->course);

        // Check the context is correct.
        $context = context_module::instance($cm->id);
        $this->assertEquals($copicloud->cmid, $context->instanceid);

        // Check that generated copicloud module contains a file.
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'mod_copicloud', 'content', false, '', false);
        $this->assertEquals(1, count($files));
    }
}
