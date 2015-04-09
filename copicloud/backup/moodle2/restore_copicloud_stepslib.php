<?php


/**
 * @package moodlecore
 * @subpackage backup-moodle2

 */

/**
 * Define all the restore steps that will be used by the restore_copicloud_activity_task
 */

/**
 * Structure step to restore one copicloud activity
 */
class restore_copicloud_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('copicloud', '/activity/copicloud');

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_copicloud($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the copicloud record
        $newitemid = $DB->insert_record('copicloud', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function after_execute() {
        // Add choice related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_copicloud', 'intro', null);
        $this->add_related_files('mod_copicloud', 'content', null);
    }
}
