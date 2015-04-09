<?php


/**
 * @package    mod
 * @subpackage copicloud
 */

defined('MOODLE_INTERNAL') || die;

/*
 * Con este framento Javascript cambiamos los iconos de los archivos 
 * En lugar de aparecer el icono del tipo de archivo aparece
 * el icono de copicloud
 * 
 */
echo('<script>window.onload = function() {	
		var copicloud_images = document.querySelectorAll("li.copicloud img.iconlarge.activityicon");
		for (i = 0; i < copicloud_images.length; ++i) {
		  copicloud_images[i].src = "../mod/copicloud/pix/icon.png";
		}
	};</script>');
/**
 * List of features supported in Resource module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function copicloud_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_SHOW_DESCRIPTION:        return true;

        default: return null;
    }
}

/**
 * Returns all other caps used in module
 * @return array
 */
function copicloud_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function copicloud_reset_userdata($data) {
    return array();
}

/**
 * List of view style log actions
 * @return array
 */
function copicloud_get_view_actions() {
    return array('view','view all');
}

/**
 * List of update style log actions
 * @return array
 */
function copicloud_get_post_actions() {
    return array('update', 'add');
}

/**
 * Add copicloud instance.
 * @param object $data
 * @param object $mform
 * @return int new copicloud instance id
 */
function copicloud_add_instance($data, $mform) {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");
    require_once("$CFG->dirroot/mod/copicloud/locallib.php");
    $cmid = $data->coursemodule;
    $data->timemodified = time();
	
    copicloud_set_display_options($data);

    $data->id = $DB->insert_record('copicloud', $data);

    // we need to use context now, so we need to make sure all needed info is already in db
    $DB->set_field('course_modules', 'instance', $data->id, array('id'=>$cmid));
    $uuid_file = copicloud_set_mainfile($data);

	//Almacenamos el uuid que copicloud nos proporciona
    if($uuid_file != NULL)
    {
		$object_update = $data;
		$object_update->uuid_file=$uuid_file;
		$DB->update_record('copicloud', $object_update);
	}
    return $data->id;
}

/**
 * Update copicloud instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function copicloud_update_instance($data, $mform) {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");
    $data->timemodified = time();
    $data->id           = $data->instance;
    $data->revision++;

    copicloud_set_display_options($data);

    $DB->update_record('copicloud', $data);
    $uuid_file = copicloud_set_mainfile($data);
    
    //Almacenamos el uuid que copicloud nos proporciona
    if($uuid_file != NULL)
    {
		$object_update = $data;
		$object_update->uuid_file=$uuid_file;
		$DB->update_record('copicloud', $object_update);
	}
    return true;
}

/**
 * Updates display options based on form input.
 *
 * Shared code used by copicloud_add_instance and copicloud_update_instance.
 *
 * @param object $data Data object
 */
function copicloud_set_display_options($data) {
    $displayoptions = array();
    if ($data->display == RESOURCELIB_DISPLAY_POPUP) {
        $displayoptions['popupwidth']  = $data->popupwidth;
        $displayoptions['popupheight'] = $data->popupheight;
    }
    if (in_array($data->display, array(RESOURCELIB_DISPLAY_AUTO, RESOURCELIB_DISPLAY_EMBED, RESOURCELIB_DISPLAY_FRAME))) {
        $displayoptions['printintro']   = (int)!empty($data->printintro);
    }
    if (!empty($data->showsize)) {
        $displayoptions['showsize'] = 1;
    }
    if (!empty($data->showtype)) {
        $displayoptions['showtype'] = 1;
    }
    $data->displayoptions = serialize($displayoptions);
}

/**
 * Delete copicloud instance.
 * @param int $id
 * @return bool true
 */
function copicloud_delete_instance($id) {
    global $DB;
	global $CFG;
    if (!$copicloud = $DB->get_record('copicloud', array('id'=>$id))) {
        return false;
    }
	//Primero pedimos a copicloud que borre el archivo antes de borrarlo en BBDD
	$signature = $CFG->token_copicloud;
	$url = 	$CFG->delete_copicloud.' -H "Authorization: Token '.$signature.'"'; 	
	$uuid = $copicloud->uuid_file;
	
	$comando = 'curl -F "uuid ='.$uuid.'" '.$url.'';

	$respuesta = exec($comando); 
    // note: all context files are deleted automatically

    $DB->delete_records('copicloud', array('id'=>$copicloud->id));

    return true;
}

/**
 * Return use outline
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $copicloud
 * @return object|null
 */
function copicloud_user_outline($course, $user, $mod, $copicloud) {
    global $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'copicloud',
                                              'action'=>'view', 'info'=>$copicloud->id), 'time ASC')) {

        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $result = new stdClass();
        $result->info = get_string('numviews', '', $numviews);
        $result->time = $lastlog->time;

        return $result;
    }
    return NULL;
}

/**
 * Return use complete
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $copicloud
 */
function copicloud_user_complete($course, $user, $mod, $copicloud) {
    global $CFG, $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'copicloud',
                                              'action'=>'view', 'info'=>$copicloud->id), 'time ASC')) {
        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $strmostrecently = get_string('mostrecently');
        $strnumviews = get_string('numviews', '', $numviews);

        echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

    } else {
        print_string('neverseen', 'copicloud');
    }
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 *
 * See {@link get_array_of_activities()} in course/lib.php
 *
 * @param stdClass $coursemodule
 * @return cached_cm_info info
 */
function copicloud_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;
    require_once("$CFG->libdir/filelib.php");
    require_once("$CFG->dirroot/mod/copicloud/locallib.php");
    require_once($CFG->libdir.'/completionlib.php');

    $context = context_module::instance($coursemodule->id);

    if (!$copicloud = $DB->get_record('copicloud', array('id'=>$coursemodule->instance),
            'id, name, display, displayoptions, tobemigrated, revision, intro, introformat,uuid_file')) {
        return NULL;
    }

    $info = new cached_cm_info();
    $info->name = $copicloud->name;
    if ($coursemodule->showdescription) {
        // Convert intro to html. Do not filter cached version, filters run at display time.
        $info->content = format_module_intro('copicloud', $copicloud, $coursemodule->id, false);
    }

    if ($copicloud->tobemigrated) {
        $info->icon ='i/invalid';
        return $info;
    }
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'mod_copicloud', 'content', 0, 'sortorder DESC, id ASC', false); // TODO: this is not very efficient!!
    if (count($files) >= 1) {
        $mainfile = reset($files);
      
        $info->icon = file_file_icon($mainfile, 24);
        $copicloud->mainfile = $mainfile->get_filename();
    }

    $display = copicloud_get_final_display_type($copicloud);

    if ($display == RESOURCELIB_DISPLAY_POPUP) {
        $fullurl = $CFG->redirect_copicloud.$copicloud->uuid_file;
        $options = empty($copicloud->displayoptions) ? array() : unserialize($copicloud->displayoptions);
        $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
        $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
        $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
        $info->onclick = "window.open('$fullurl', '', '$wh'); return false;";

    } else if ($display == RESOURCELIB_DISPLAY_NEW) {
        $fullurl = $CFG->redirect_copicloud.$copicloud->uuid_file;
        $info->onclick = "window.open('$fullurl'); return false;";

    }

    // If any optional extra details are turned on, store in custom data
    $info->customdata = copicloud_get_optional_details($copicloud, $coursemodule);

    return $info;
}

/**
 * Called when viewing course page. Shows extra details after the link if
 * enabled.
 *
 * @param cm_info $cm Course module information
 */
function copicloud_cm_info_view(cm_info $cm) {
    $details = $cm->get_custom_data();
    if ($details) {
        $cm->set_after_link(' ' . html_writer::tag('span', $details,
                array('class' => 'copicloudlinkdetails')));
    }
}

/**
 * Lists all browsable file areas
 *
 * @package  mod_copicloud
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @return array
 */
function copicloud_get_file_areas($course, $cm, $context) {
    $areas = array();
    $areas['content'] = get_string('copicloudcontent', 'copicloud');
    return $areas;
}

/**
 * File browsing support for copicloud module content area.
 *
 * @package  mod_copicloud
 * @category files
 * @param stdClass $browser file browser instance
 * @param stdClass $areas file areas
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param int $itemid item ID
 * @param string $filepath file path
 * @param string $filename file name
 * @return file_info instance or null if not found
 */
function copicloud_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    global $CFG;

    if (!has_capability('moodle/course:managefiles', $context)) {
        // students can not peak here!
        return null;
    }

    $fs = get_file_storage();

    if ($filearea === 'content') {
        $filepath = is_null($filepath) ? '/' : $filepath;
        $filename = is_null($filename) ? '.' : $filename;

        $urlbase = $CFG->wwwroot.'/pluginfile.php';
        if (!$storedfile = $fs->get_file($context->id, 'mod_copicloud', 'content', 0, $filepath, $filename)) {
            if ($filepath === '/' and $filename === '.') {
                $storedfile = new virtual_root_file($context->id, 'mod_copicloud', 'content', 0);
            } else {
                // not found
                return null;
            }
        }
        require_once("$CFG->dirroot/mod/copicloud/locallib.php");
        return new copicloud_content_file_info($browser, $context, $storedfile, $urlbase, $areas[$filearea], true, true, true, false);
    }

    // note: copicloud_intro handled in file_browser automatically

    return null;
}

/**
 * Serves the copicloud files.
 *
 * @package  mod_copicloud
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function copicloud_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_course_login($course, true, $cm);
    if (!has_capability('mod/copicloud:view', $context)) {
        return false;
    }

    if ($filearea !== 'content') {
        // intro is handled automatically in pluginfile.php
        return false;
    }

    array_shift($args); // ignore revision - designed to prevent caching problems only

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = rtrim("/$context->id/mod_copicloud/$filearea/0/$relativepath", '/');
    do {
        if (!$file = $fs->get_file_by_hash(sha1($fullpath))) {
            if ($fs->get_file_by_hash(sha1("$fullpath/."))) {
                if ($file = $fs->get_file_by_hash(sha1("$fullpath/index.htm"))) {
                    break;
                }
                if ($file = $fs->get_file_by_hash(sha1("$fullpath/index.html"))) {
                    break;
                }
                if ($file = $fs->get_file_by_hash(sha1("$fullpath/Default.htm"))) {
                    break;
                }
            }
            $copicloud = $DB->get_record('copicloud', array('id'=>$cm->instance), 'id, legacyfiles', MUST_EXIST);
            if ($copicloud->legacyfiles != RESOURCELIB_LEGACYFILES_ACTIVE) {
                return false;
            }
            if (!$file = resourcelib_try_file_migration('/'.$relativepath, $cm->id, $cm->course, 'mod_copicloud', 'content', 0)) {
                return false;
            }
            // file migrate - update flag
            $copicloud->legacyfileslast = time();
            $DB->update_record('copicloud', $copicloud);
        }
    } while (false);

    // should we apply filters?
    $mimetype = $file->get_mimetype();
    if ($mimetype === 'text/html' or $mimetype === 'text/plain') {
        $filter = $DB->get_field('copicloud', 'filterfiles', array('id'=>$cm->instance));
        $CFG->embeddedsoforcelinktarget = true;
    } else {
        $filter = 0;
    }

    // finally send the file
    send_stored_file($file, null, $filter, $forcedownload, $options);
}

/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
function copicloud_page_type_list($pagetype, $parentcontext, $currentcontext) {
    $module_pagetype = array('mod-copicloud-*'=>get_string('page-mod-copicloud-x', 'copicloud'));
    return $module_pagetype;
}

/**
 * Export file copicloud contents
 *
 * @return array of file content
 */
function copicloud_export_contents($cm, $baseurl) {
    global $CFG, $DB;
    $contents = array();
    $context = context_module::instance($cm->id);
    $copicloud = $DB->get_record('copicloud', array('id'=>$cm->instance), '*', MUST_EXIST);

    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'mod_copicloud', 'content', 0, 'sortorder DESC, id ASC', false);

    foreach ($files as $fileinfo) {
        $file = array();
        $file['type'] = 'file';
        $file['filename']     = $fileinfo->get_filename();
        $file['filepath']     = $fileinfo->get_filepath();
        $file['filesize']     = $fileinfo->get_filesize();
        //~ $file['fileurl']      = file_encode_url("$CFG->wwwroot/" . $baseurl, '/'.$context->id.'/mod_copicloud/content/'.$copicloud->revision.$fileinfo->get_filepath().$fileinfo->get_filename(), true);
        $file['fileurl']      = $CFG->redirect_copicloud.$copicloud->uuid_file;
        $file['timecreated']  = $fileinfo->get_timecreated();
        $file['timemodified'] = $fileinfo->get_timemodified();
        $file['sortorder']    = $fileinfo->get_sortorder();
        $file['userid']       = $fileinfo->get_userid();
        $file['author']       = $fileinfo->get_author();
        $file['license']      = $fileinfo->get_license();
        $contents[] = $file;
    }

    return $contents;
}

/**
 * Register the ability to handle drag and drop file uploads
 * @return array containing details of the files / types the mod can handle
 */
function copicloud_dndupload_register() {
    return array('files' => array(
                     array('extension' => '*', 'message' => get_string('dnduploadcopicloud', 'mod_copicloud'))
                 ));
}

/**
 * Handle a file that has been uploaded
 * @param object $uploadinfo details of the file / content that has been uploaded
 * @return int instance id of the newly created mod
 */
function copicloud_dndupload_handle($uploadinfo) {
    // Gather the required info.
    $data = new stdClass();
    $data->course = $uploadinfo->course->id;
    $data->name = $uploadinfo->displayname;
    $data->intro = '';
    $data->introformat = FORMAT_HTML;
    $data->coursemodule = $uploadinfo->coursemodule;
    $data->files = $uploadinfo->draftitemid;

    // Set the display options to the site defaults.
    $config = get_config('copicloud');
    $data->display = $config->display;
    $data->popupheight = $config->popupheight;
    $data->popupwidth = $config->popupwidth;
    $data->printintro = $config->printintro;
    $data->showsize = (isset($config->showsize)) ? $config->showsize : 0;
    $data->showtype = (isset($config->showtype)) ? $config->showtype : 0;
    $data->filterfiles = $config->filterfiles;

    return copicloud_add_instance($data, null);
}
