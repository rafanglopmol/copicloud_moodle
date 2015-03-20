<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Resource module version information
 *
 * @package    mod
 * @subpackage copicloud
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/copicloud/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

$id       = optional_param('id', 0, PARAM_INT); // Course Module ID
$r        = optional_param('r', 0, PARAM_INT);  // Resource instance ID
$redirect = optional_param('redirect', 0, PARAM_BOOL);

if ($r) {
    if (!$copicloud = $DB->get_record('copicloud', array('id'=>$r))) {
        copicloud_redirect_if_migrated($r, 0);
        print_error('invalidaccessparameter');
    }
    $cm = get_coursemodule_from_instance('copicloud', $copicloud->id, $copicloud->course, false, MUST_EXIST);

} else {
    if (!$cm = get_coursemodule_from_id('copicloud', $id)) {
        copicloud_redirect_if_migrated(0, $id);
        print_error('invalidcoursemodule');
    }
    $copicloud = $DB->get_record('copicloud', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/copicloud:view', $context);

add_to_log($course->id, 'copicloud', 'view', 'view.php?id='.$cm->id, $copicloud->id, $cm->id);

// Update 'viewed' state if required by completion system
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_url('/mod/copicloud/view.php', array('id' => $cm->id));

if ($copicloud->tobemigrated) {
    copicloud_print_tobemigrated($copicloud, $cm, $course);
    die;
}

$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'mod_copicloud', 'content', 0, 'sortorder DESC, id ASC', false); // TODO: this is not very efficient!!
if (count($files) < 1) {
    copicloud_print_filenotfound($copicloud, $cm, $course);
    die;
} else {
    $file = reset($files);
    unset($files);
}

$copicloud->mainfile = $file->get_filename();
$displaytype = copicloud_get_final_display_type($copicloud);
if ($displaytype == RESOURCELIB_DISPLAY_OPEN || $displaytype == RESOURCELIB_DISPLAY_DOWNLOAD) {
    // For 'open' and 'download' links, we always redirect to the content - except
    // if the user just chose 'save and display' from the form then that would be
    // confusing
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'modedit.php') === false) {
        $redirect = true;
    }
}

if ($redirect) {
    // coming from course page or url index page
    // this redirect trick solves caching problems when tracking views ;-)
    $path = '/'.$context->id.'/mod_copicloud/content/'.$copicloud->revision.$file->get_filepath().$file->get_filename();
    $fullurl =$CFG->redirect_copicloud.$copicloud["uuid_file"];
    //~ $fullurl = moodle_url::make_file_url('/pluginfile.php', $path, $displaytype == RESOURCELIB_DISPLAY_DOWNLOAD);
    redirect($fullurl);
}

switch ($displaytype) {
    case RESOURCELIB_DISPLAY_EMBED:
        copicloud_display_embed($copicloud, $cm, $course, $file);
        break;
    case RESOURCELIB_DISPLAY_FRAME:
        copicloud_display_frame($copicloud, $cm, $course, $file);
        break;
    default:
        copicloud_print_workaround($copicloud, $cm, $course, $file);
        break;
}
