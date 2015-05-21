<?php


/**
 * Defines backup_copicloud_activity_task class
 *
 * @package     mod_copicloud
 * @category    backup
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/copicloud/backup/moodle2/backup_copicloud_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the Resource instance
 */
class backup_copicloud_activity_task extends backup_activity_task {

    /**
     * @param bool $copicloudoldexists True if there are records in the copicloud_old table.
     */
    protected static $copicloudoldexists = null;

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the copicloud.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_copicloud_activity_structure_step('copicloud_structure', 'copicloud.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG, $DB;

        $base = preg_quote($CFG->wwwroot,"/");

        // Link to the list of copiclouds.
        $search="/(".$base."\/mod\/copicloud\/index.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@COPICLOUDINDEX*$2@$', $content);

        // Link to copicloud view by moduleid.
        $search = "/(".$base."\/mod\/copicloud\/view.php\?id\=)([0-9]+)/";
        // Link to copicloud view by recordid
        $search2 = "/(".$base."\/mod\/copicloud\/view.php\?r\=)([0-9]+)/";

        // Check whether there are contents in the copicloud old table.
        if (static::$copicloudoldexists === null) {
            static::$copicloudoldexists = $DB->record_exists('copicloud_old', array());
        }

        // If there are links to items in the copicloud_old table, rewrite them to be links to the correct URL
        // for their new module.
        if (static::$copicloudoldexists) {
            // Match all of the copiclouds.
            $result = preg_match_all($search, $content, $matches, PREG_PATTERN_ORDER);

            // Course module ID copicloud links.
            if ($result) {
                list($insql, $params) = $DB->get_in_or_equal($matches[2]);
                $oldrecs = $DB->get_records_select('copicloud_old', "cmid $insql", $params, '', 'cmid, newmodule');

                for ($i = 0; $i < count($matches[0]); $i++) {
                    $cmid = $matches[2][$i];
                    if (isset($oldrecs[$cmid])) {
                        // Resource_old item, rewrite it
                        $replace = '$@' . strtoupper($oldrecs[$cmid]->newmodule) . 'VIEWBYID*' . $cmid . '@$';
                    } else {
                        // Not in the copicloud old table, don't rewrite
                        $replace = '$@COPICLOUDVIEWBYID*'.$cmid.'@$';
                    }
                    $content = str_replace($matches[0][$i], $replace, $content);
                }
            }

            $matches = null;
            $result = preg_match_all($search2, $content, $matches, PREG_PATTERN_ORDER);

            // No copicloud links.
            if (!$result) {
                return $content;
            }
            // Resource ID links.
            list($insql, $params) = $DB->get_in_or_equal($matches[2]);
            $oldrecs = $DB->get_records_select('copicloud_old', "oldid $insql", $params, '', 'oldid, cmid, newmodule');

            for ($i = 0; $i < count($matches[0]); $i++) {
                $recordid = $matches[2][$i];
                if (isset($oldrecs[$recordid])) {
                    // Resource_old item, rewrite it
                    $replace = '$@' . strtoupper($oldrecs[$recordid]->newmodule) . 'VIEWBYID*' . $oldrecs[$recordid]->cmid . '@$';
                    $content = str_replace($matches[0][$i], $replace, $content);
                }
            }
        } else {
            $content = preg_replace($search, '$@COPICLOUDVIEWBYID*$2@$', $content);
        }
        return $content;
    }
}
