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
 * List of all copiclouds in course
 *
 * @package    mod
 * @subpackage copicloud
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$id = required_param('id', PARAM_INT); // course id

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

add_to_log($course->id, 'copicloud', 'view all', "index.php?id=$course->id", '');

$strcopicloud     = get_string('modulename', 'copicloud');
$strcopiclouds    = get_string('modulenameplural', 'copicloud');
$strsectionname  = get_string('sectionname', 'format_'.$course->format);
$strname         = get_string('name');
$strintro        = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$PAGE->set_url('/mod/copicloud/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.$strcopiclouds);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strcopiclouds);
echo $OUTPUT->header();
echo $OUTPUT->heading($strcopiclouds);

if (!$copiclouds = get_all_instances_in_course('copicloud', $course)) {
    notice(get_string('thereareno', 'moodle', $strcopiclouds), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $table->head  = array ($strsectionname, $strname, $strintro);
    $table->align = array ('center', 'left', 'left');
} else {
    $table->head  = array ($strlastmodified, $strname, $strintro);
    $table->align = array ('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($copiclouds as $copicloud) {
    $cm = $modinfo->cms[$copicloud->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($copicloud->section !== $currentsection) {
            if ($copicloud->section) {
                $printsection = get_section_name($course, $copicloud->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $copicloud->section;
        }
    } else {
        $printsection = '<span class="smallinfo">'.userdate($copicloud->timemodified)."</span>";
    }

    $extra = empty($cm->extra) ? '' : $cm->extra;
    $icon = '';
    if (!empty($cm->icon)) {
        // each copicloud file has an icon in 2.0
        $icon = '<img src="'.$OUTPUT->pix_url($cm->icon).'" class="activityicon" alt="'.get_string('modulename', $cm->modname).'" /> ';
    }

    $class = $copicloud->visible ? '' : 'class="dimmed"'; // hidden modules are dimmed
    $table->data[] = array (
        $printsection,
        "<a $class $extra href=\"view.php?id=$cm->id\">".$icon.format_string($copicloud->name)."</a>",
        format_module_intro('copicloud', $copicloud, $cm->id));
}

echo html_writer::table($table);

echo $OUTPUT->footer();
