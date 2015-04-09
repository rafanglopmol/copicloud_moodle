<?php



/**
 * Define all the backup steps that will be used by the backup_copicloud_activity_task
 *
 * @package    mod
 * @subpackage copicloud
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete copicloud structure for backup, with file and id annotations
 */
class backup_copicloud_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $copicloud = new backup_nested_element('copicloud', array('id'), array(
            'name', 'intro', 'introformat', 'tobemigrated',
            'legacyfiles', 'legacyfileslast', 'display',
            'displayoptions', 'filterfiles', 'revision', 'timemodified'));

        // Build the tree
        // (love this)

        // Define sources
        $copicloud->set_source_table('copicloud', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations
        // (none)

        // Define file annotations
        $copicloud->annotate_files('mod_copicloud', 'intro', null); // This file areas haven't itemid
        $copicloud->annotate_files('mod_copicloud', 'content', null); // This file areas haven't itemid

        // Return the root element (copicloud), wrapped into standard activity structure
        return $this->prepare_activity_structure($copicloud);
    }
}
