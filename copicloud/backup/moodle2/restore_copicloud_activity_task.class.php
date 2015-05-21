<?php


/**
 * @package moodlecore
 * @subpackage backup-moodle2

 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/copicloud/backup/moodle2/restore_copicloud_stepslib.php'); // Because it exists (must)

/**
 * copicloud restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_copicloud_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // Choice only has one structure step
        $this->add_step(new restore_copicloud_activity_structure_step('copicloud_structure', 'copicloud.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('copicloud', array('intro'), 'copicloud');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('COPICLOUDVIEWBYID', '/mod/copicloud/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('COPICLOUDINDEX', '/mod/copicloud/index.php?id=$1', 'course');

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * copicloud logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('copicloud', 'add', 'view.php?id={course_module}', '{copicloud}');
        $rules[] = new restore_log_rule('copicloud', 'update', 'view.php?id={course_module}', '{copicloud}');
        $rules[] = new restore_log_rule('copicloud', 'view', 'view.php?id={course_module}', '{copicloud}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('copicloud', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
