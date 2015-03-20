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
 * Resource module admin settings and defaults
 *
 * @package    mod
 * @subpackage copicloud
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once("$CFG->libdir/resourcelib.php");

    $displayoptions = resourcelib_get_displayoptions(array(RESOURCELIB_DISPLAY_AUTO,
                                                           RESOURCELIB_DISPLAY_EMBED,
                                                           RESOURCELIB_DISPLAY_FRAME,
                                                           RESOURCELIB_DISPLAY_DOWNLOAD,
                                                           RESOURCELIB_DISPLAY_OPEN,
                                                           RESOURCELIB_DISPLAY_NEW,
                                                           RESOURCELIB_DISPLAY_POPUP,
                                                          ));
    $defaultdisplayoptions = array(RESOURCELIB_DISPLAY_AUTO,
                                   RESOURCELIB_DISPLAY_EMBED,
                                   RESOURCELIB_DISPLAY_DOWNLOAD,
                                   RESOURCELIB_DISPLAY_OPEN,
                                   RESOURCELIB_DISPLAY_POPUP,
                                  );

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_configtext('copicloud/framesize',
        get_string('framesize', 'copicloud'), get_string('configframesize', 'copicloud'), 130, PARAM_INT));
    $settings->add(new admin_setting_configcheckbox('copicloud/requiremodintro',
        get_string('requiremodintro', 'admin'), get_string('configrequiremodintro', 'admin'), 1));
    $settings->add(new admin_setting_configmultiselect('copicloud/displayoptions',
        get_string('displayoptions', 'copicloud'), get_string('configdisplayoptions', 'copicloud'),
        $defaultdisplayoptions, $displayoptions));

    //--- modedit defaults -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('copicloudmodeditdefaults', get_string('modeditdefaults', 'admin'), get_string('condifmodeditdefaults', 'admin')));

    $settings->add(new admin_setting_configcheckbox('copicloud/printintro',
        get_string('printintro', 'copicloud'), get_string('printintroexplain', 'copicloud'), 1));
    $settings->add(new admin_setting_configselect('copicloud/display',
        get_string('displayselect', 'copicloud'), get_string('displayselectexplain', 'copicloud'), RESOURCELIB_DISPLAY_AUTO,
        $displayoptions));
    $settings->add(new admin_setting_configcheckbox('copicloud/showsize',
        get_string('showsize', 'copicloud'), get_string('showsize_desc', 'copicloud'), 0));
    $settings->add(new admin_setting_configcheckbox('copicloud/showtype',
        get_string('showtype', 'copicloud'), get_string('showtype_desc', 'copicloud'), 0));
    $settings->add(new admin_setting_configtext('copicloud/popupwidth',
        get_string('popupwidth', 'copicloud'), get_string('popupwidthexplain', 'copicloud'), 620, PARAM_INT, 7));
    $settings->add(new admin_setting_configtext('copicloud/popupheight',
        get_string('popupheight', 'copicloud'), get_string('popupheightexplain', 'copicloud'), 450, PARAM_INT, 7));
    $options = array('0' => get_string('none'), '1' => get_string('allfiles'), '2' => get_string('htmlfilesonly'));
    $settings->add(new admin_setting_configselect('copicloud/filterfiles',
        get_string('filterfiles', 'copicloud'), get_string('filterfilesexplain', 'copicloud'), 0, $options));
	//Especificidades de Copicloud
	//Directorio donde se almacenan los archivos que se suben al servidor
    $settings->add(new admin_setting_configtext('filedir_copicloud', new lang_string('filedir_copicloud', 'copicloud'), new lang_string('filedir_copicloudexplain', 'copicloud'), '', PARAM_RAW, 150));
	//Api token de comunicación
    $settings->add(new admin_setting_configtext('token_copicloud', new lang_string('token_copicloud', 'copicloud'), new lang_string('token_copicloudexplain', 'copicloud'), '', PARAM_RAW, 150));
	//URL UPLOAD
    $settings->add(new admin_setting_configtext('upload_copicloud', new lang_string('upload_copicloud', 'copicloud'), new lang_string('upload_copicloudexplain', 'copicloud'), 'http://api.copicloud.com/api/lecturenotes/moodle/upload/', PARAM_RAW, 150));
	//URL DELETE
    $settings->add(new admin_setting_configtext('delete_copicloud', new lang_string('delete_copicloud', 'copicloud'), new lang_string('delete_copicloudexplain', 'copicloud'), 'http://api.copicloud.com/api/lecturenotes/moodle/delete/', PARAM_RAW, 150));
	//URL REDIRECT
    $settings->add(new admin_setting_configtext('redirect_copicloud', new lang_string('redirect_copicloud', 'copicloud'), new lang_string('redirect_copicloudexplain', 'copicloud'), 'http://www.copicloud.com/#/moodle?uuid=', PARAM_RAW, 150));
}
