<?php

.

/**
 * Private copicloud module utility functions
 *
 * @package    mod
 * @subpackage copicloud
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/filelib.php");
require_once("$CFG->libdir/resourcelib.php");
require_once("$CFG->dirroot/mod/copicloud/lib.php");

/**
 * Redirected to migrated copicloud if needed,
 * return if incorrect parameters specified
 * @param int $oldid
 * @param int $cmid
 * @return void
 */
function copicloud_redirect_if_migrated($oldid, $cmid) {
    global $DB, $CFG;

    if ($oldid) {
        $old = $DB->get_record('copicloud_old', array('oldid'=>$oldid));
    } else {
        $old = $DB->get_record('copicloud_old', array('cmid'=>$cmid));
    }

    if (!$old) {
        return;
    }

    redirect("$CFG->wwwroot/mod/$old->newmodule/view.php?id=".$old->cmid);
}

/**
 * Display embedded copicloud file.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @param stored_file $file main file
 * @return does not return
 */
function copicloud_display_embed($copicloud, $cm, $course, $file) {
    global $CFG, $PAGE, $OUTPUT;

    $clicktoopen = copicloud_get_clicktoopen($file,$copicloud, $copicloud->revision);

    $context = context_module::instance($cm->id);
    $path = '/'.$context->id.'/mod_copicloud/content/'.$copicloud->revision.$file->get_filepath().$file->get_filename();
    $fullurl = $CFG->redirect_copicloud.$copicloud->uuid_file;
    $moodleurl = $CFG->redirect_copicloud.$copicloud->uuid_file;

    $mimetype = $file->get_mimetype();
    $title    = $copicloud->name;

    $extension = resourcelib_get_extension($file->get_filename());

    $mediarenderer = $PAGE->get_renderer('core', 'media');
    $embedoptions = array(
        core_media::OPTION_TRUSTED => true,
        core_media::OPTION_BLOCK => true,
    );

    if (file_mimetype_in_typegroup($mimetype, 'web_image')) {  // It's an image
        $code = resourcelib_embed_image($fullurl, $title);

    } else if ($mimetype === 'application/pdf') {
        // PDF document
        $code = resourcelib_embed_pdf($fullurl, $title, $clicktoopen);

    } else if ($mediarenderer->can_embed_url($moodleurl, $embedoptions)) {
        // Media (audio/video) file.
        $code = $mediarenderer->embed_url($moodleurl, $title, 0, 0, $embedoptions);

    } else {
        // anything else - just try object tag enlarged as much as possible
        $code = resourcelib_embed_general($fullurl, $title, $clicktoopen, $mimetype);
    }

    copicloud_print_header($copicloud, $cm, $course);
    copicloud_print_heading($copicloud, $cm, $course);

    echo $code;

    copicloud_print_intro($copicloud, $cm, $course);

    echo $OUTPUT->footer();
    die;
}

/**
 * Display copicloud frames.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @param stored_file $file main file
 * @return does not return
 */
function copicloud_display_frame($copicloud, $cm, $course, $file) {
    global $PAGE, $OUTPUT, $CFG;

    $frame = optional_param('frameset', 'main', PARAM_ALPHA);

    if ($frame === 'top') {
        $PAGE->set_pagelayout('frametop');
        copicloud_print_header($copicloud, $cm, $course);
        copicloud_print_heading($copicloud, $cm, $course);
        copicloud_print_intro($copicloud, $cm, $course);
        echo $OUTPUT->footer();
        die;

    } else {
		
        $config = get_config('copicloud');
        $context = context_module::instance($cm->id);
        $path = '/'.$context->id.'/mod_copicloud/content/'.$copicloud->revision.$file->get_filepath().$file->get_filename();
        $fileurl = file_encode_url($CFG->wwwroot.'/pluginfile.php', $path, false);
        $navurl = "$CFG->wwwroot/mod/copicloud/view.php?id=$cm->id&amp;frameset=top";
        $title = strip_tags(format_string($course->shortname.': '.$copicloud->name));
        $framesize = $config->framesize;
        $contentframetitle = format_string($copicloud->name);
        $modulename = s(get_string('modulename','copicloud'));
        $dir = get_string('thisdirection', 'langconfig');

        $file = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html dir="$dir">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>$title</title>
  </head>
  <frameset rows="$framesize,*">
    <frame src="$navurl" title="$modulename" />
    <frame src="$fileurl" title="$contentframetitle" />
  </frameset>
</html>
EOF;

        @header('Content-Type: text/html; charset=utf-8');
        echo $file;
        die;
    }
}

/**
 * Internal function - create click to open text with link.
 */
function copicloud_get_clicktoopen($file,$copicloud, $revision, $extra='') {
    global $CFG;
    global $DB;
    
    $filename = $file->get_filename();
    $path = '/'.$file->get_contextid().'/mod_copicloud/content/'.$revision.$file->get_filepath().$file->get_filename();
    $fullurl = $CFG->redirect_copicloud.$copicloud->uuid_file;

    $string = get_string('clicktoopen2', 'copicloud', "<a href=\"$fullurl\" $extra>$filename</a>");

    return $string;
}

/**
 * Internal function - create click to open text with link.
 */
function copicloud_get_clicktodownload($file,$copicloud, $revision) {
    global $CFG;

    $filename = $file->get_filename();
    $path = '/'.$file->get_contextid().'/mod_copicloud/content/'.$revision.$file->get_filepath().$file->get_filename();
    $fullurl = $CFG->redirect_copicloud.$copicloud->uuid_file;

    $string = get_string('clicktodownload', 'copicloud', "<a href=\"$fullurl\">$filename</a>");

    return $string;
}

/**
 * Print copicloud info and workaround link when JS not available.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @param stored_file $file main file
 * @return does not return
 */
function copicloud_print_workaround($copicloud, $cm, $course, $file) {
    global $CFG, $OUTPUT;

    copicloud_print_header($copicloud, $cm, $course);
    copicloud_print_heading($copicloud, $cm, $course, true);
    copicloud_print_intro($copicloud, $cm, $course, true);

    $copicloud->mainfile = $file->get_filename();
    echo '<div class="copicloudworkaround">';
    switch (copicloud_get_final_display_type($copicloud)) {
        case RESOURCELIB_DISPLAY_POPUP:
            $path = '/'.$file->get_contextid().'/mod_copicloud/content/'.$copicloud->revision.$file->get_filepath().$file->get_filename();
            $fullurl = $CFG->redirect_copicloud.$copicloud->uuid_file;
            $options = empty($copicloud->displayoptions) ? array() : unserialize($copicloud->displayoptions);
            $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
            $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
            $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
            $extra = "onclick=\"window.open('$fullurl', '', '$wh'); return false;\"";
            echo copicloud_get_clicktoopen($file,$copicloud, $copicloud->revision, $extra);
            break;

        case RESOURCELIB_DISPLAY_NEW:
            $extra = 'onclick="this.target=\'_blank\'"';
            echo copicloud_get_clicktoopen($file,$copicloud, $copicloud->revision, $extra);
            break;

        case RESOURCELIB_DISPLAY_DOWNLOAD:
            echo copicloud_get_clicktodownload($file,$copicloud, $copicloud->revision);
            break;

        case RESOURCELIB_DISPLAY_OPEN:
        default:
            echo copicloud_get_clicktoopen($file,$copicloud, $copicloud->revision);
            break;
    }
    echo '</div>';

    echo $OUTPUT->footer();
    die;
}

/**
 * Print copicloud header.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @return void
 */
function copicloud_print_header($copicloud, $cm, $course) {
    global $PAGE, $OUTPUT;

    $PAGE->set_title($course->shortname.': '.$copicloud->name);
    $PAGE->set_heading($course->fullname);
    $PAGE->set_activity_record($copicloud);
    $PAGE->set_button(update_module_button($cm->id, '', get_string('modulename', 'copicloud')));
    echo $OUTPUT->header();
}

/**
 * Print copicloud heading.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @param bool $notused This variable is no longer used
 * @return void
 */
function copicloud_print_heading($copicloud, $cm, $course, $notused = false) {
    global $OUTPUT;
    echo $OUTPUT->heading(format_string($copicloud->name), 2);
}

/**
 * Gets optional details for a copicloud, depending on copicloud settings.
 *
 * Result may include the file size and type if those settings are chosen,
 * or blank if none.
 *
 * @param object $copicloud Resource table row
 * @param object $cm Course-module table row
 * @return string Size and type or empty string if show options are not enabled
 */
function copicloud_get_optional_details($copicloud, $cm) {
    global $DB;

    $details = '';

    $options = empty($copicloud->displayoptions) ? array() : unserialize($copicloud->displayoptions);
    if (!empty($options['showsize']) || !empty($options['showtype'])) {
        $context = context_module::instance($cm->id);
        $size = '';
        $type = '';
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'mod_copicloud', 'content', 0, 'sortorder DESC, id ASC', false);
        if (!empty($options['showsize']) && count($files)) {
            $sizebytes = 0;
            foreach ($files as $file) {
                // this will also synchronize the file size for external files if needed
                $sizebytes += $file->get_filesize();
            }
            if ($sizebytes) {
                $size = display_size($sizebytes);
            }
        }
        if (!empty($options['showtype']) && count($files)) {
            // For a typical file copicloud, the sortorder is 1 for the main file
            // and 0 for all other files. This sort approach is used just in case
            // there are situations where the file has a different sort order
            $mainfile = reset($files);
            $type = get_mimetype_description($mainfile);
            // Only show type if it is not unknown
            if ($type === get_mimetype_description('document/unknown')) {
                $type = '';
            }
        }

        if ($size && $type) {
            // Depending on language it may be necessary to show both options in
            // different order, so use a lang string
            $details = get_string('copiclouddetails_sizetype', 'copicloud',
                    (object)array('size'=>$size, 'type'=>$type));
        } else {
            // Either size or type is set, but not both, so just append
            $details = $size . $type;
        }
    }

    return $details;
}

/**
 * Print copicloud introduction.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @param bool $ignoresettings print even if not specified in modedit
 * @return void
 */
function copicloud_print_intro($copicloud, $cm, $course, $ignoresettings=false) {
    global $OUTPUT;

    $options = empty($copicloud->displayoptions) ? array() : unserialize($copicloud->displayoptions);

    $extraintro = copicloud_get_optional_details($copicloud, $cm);
    if ($extraintro) {
        // Put a paragaph tag around the details
        $extraintro = html_writer::tag('p', $extraintro, array('class' => 'copiclouddetails'));
    }

    if ($ignoresettings || !empty($options['printintro']) || $extraintro) {
        $gotintro = trim(strip_tags($copicloud->intro));
        if ($gotintro || $extraintro) {
            echo $OUTPUT->box_start('mod_introbox', 'copicloudintro');
            if ($gotintro) {
                echo format_module_intro('copicloud', $copicloud, $cm->id);
            }
            echo $extraintro;
            echo $OUTPUT->box_end();
        }
    }
}

/**
 * Print warning that instance not migrated yet.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @return void, does not return
 */
function copicloud_print_tobemigrated($copicloud, $cm, $course) {
    global $DB, $OUTPUT;

    $copicloud_old = $DB->get_record('copicloud_old', array('oldid'=>$copicloud->id));
    copicloud_print_header($copicloud, $cm, $course);
    copicloud_print_heading($copicloud, $cm, $course);
    copicloud_print_intro($copicloud, $cm, $course);
    echo $OUTPUT->notification(get_string('notmigrated', 'copicloud', $copicloud_old->type));
    echo $OUTPUT->footer();
    die;
}

/**
 * Print warning that file can not be found.
 * @param object $copicloud
 * @param object $cm
 * @param object $course
 * @return void, does not return
 */
function copicloud_print_filenotfound($copicloud, $cm, $course) {
    global $DB, $OUTPUT;

    $copicloud_old = $DB->get_record('copicloud_old', array('oldid'=>$copicloud->id));
    copicloud_print_header($copicloud, $cm, $course);
    copicloud_print_heading($copicloud, $cm, $course);
    copicloud_print_intro($copicloud, $cm, $course);
    if ($copicloud_old) {
        echo $OUTPUT->notification(get_string('notmigrated', 'copicloud', $copicloud_old->type));
    } else {
        echo $OUTPUT->notification(get_string('filenotfound', 'copicloud'));
    }
    echo $OUTPUT->footer();
    die;
}

/**
 * Decide the best display format.
 * @param object $copicloud
 * @return int display type constant
 */
function copicloud_get_final_display_type($copicloud) {
    global $CFG, $PAGE;

    if ($copicloud->display != RESOURCELIB_DISPLAY_AUTO) {
        return $copicloud->display;
    }

    if (empty($copicloud->mainfile)) {
        return RESOURCELIB_DISPLAY_DOWNLOAD;
    } else {
        $mimetype = mimeinfo('type', $copicloud->mainfile);
    }

    if (file_mimetype_in_typegroup($mimetype, 'archive')) {
        return RESOURCELIB_DISPLAY_DOWNLOAD;
    }
    if (file_mimetype_in_typegroup($mimetype, array('web_image', '.htm', 'web_video', 'web_audio'))) {
        return RESOURCELIB_DISPLAY_EMBED;
    }

    // let the browser deal with it somehow
    return RESOURCELIB_DISPLAY_OPEN;
}

/**
 * File browsing support class
 */
class copicloud_content_file_info extends file_info_stored {
    public function get_parent() {
        if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.') {
            return $this->browser->get_file_info($this->context);
        }
        return parent::get_parent();
    }
    public function get_visible_name() {
        if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.') {
            return $this->topvisiblename;
        }
        return parent::get_visible_name();
    }
}

function copicloud_set_mainfile($data) {
	
    global $DB;
    global $CFG;
    $fs = get_file_storage();
    $cmid = $data->coursemodule;
    $draftitemid = $data->files;

    $context = context_module::instance($cmid);
    if ($draftitemid) {
        file_save_draft_area_files($draftitemid, $context->id, 'mod_copicloud', 'content', 0, array('subdirs'=>true));
    }
    $files = $fs->get_area_files($context->id, 'mod_copicloud', 'content', 0, 'sortorder', false);
    if (count($files) == 1) {
        // only one file attached, set it as main file automatically
        $file = reset($files);
        file_set_sortorder($context->id, 'mod_copicloud', 'content', 0, $file->get_filepath(), $file->get_filename(), 1);
    }


	//Conexión con copicloud
	$subject_name = $DB->get_record('course', array('id'=>$data->course),"fullname");

    $filedir = $CFG->filedir_copicloud;
	$contenthash = $file->get_contenthash();
	$l1 = $contenthash[0].$contenthash[1];
	$l2 = $contenthash[2].$contenthash[3];
	$path = $filedir."$l1/$l2/$contenthash";
	
	$signature = $CFG->token_copicloud;
	$url = $CFG->upload_copicloud.' -H "Authorization: Token '.$signature.'"'; 
	
	$subject_name = $subject_name->fullname;
	
	$lecturenote_name = $data->name;
	$lecturenote_description =  $data->intro;
	$lecturenote_file = $path;
	$comando = 'curl -F "subject_name = '.$subject_name.'" -F "lecturenote_name = '.$lecturenote_name.'" -F "lecturenote_description = '.$lecturenote_description.'" -F "lecturenote_file=@'.$lecturenote_file.'" '.$url.'';
	$respuesta = exec($comando); 
	$respuesta = json_decode($respuesta); 
	if($respuesta->status == "ok")
		return $respuesta->uuid;
	else
		return NULL;
	
	


}
