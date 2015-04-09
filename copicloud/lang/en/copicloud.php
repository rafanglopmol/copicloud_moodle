<?php


/**
 * Strings for component 'copicloud', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    mod
 * @subpackage copicloud
 */

$string['clicktodownload'] = 'Click {$a} link to download the file.';
$string['clicktoopen2'] = 'Click {$a} link to view the file.';
$string['configdisplayoptions'] = 'Select all options that should be available, existing settings are not modified. Hold CTRL key to select multiple fields.';
$string['configframesize'] = 'When a web page or an uploaded file is displayed within a frame, this value is the height (in pixels) of the top frame (which contains the navigation).';
$string['configparametersettings'] = 'This sets the default value for the Parameter settings pane in the form when adding some new copiclouds. After the first time, this becomes an individual user preference.';
$string['configpopup'] = 'When adding a new copicloud which is able to be shown in a popup window, should this option be enabled by default?';
$string['configpopupdirectories'] = 'Should popup windows show directory links by default?';
$string['configpopupheight'] = 'What height should be the default height for new popup windows?';
$string['configpopuplocation'] = 'Should popup windows show the location bar by default?';
$string['configpopupmenubar'] = 'Should popup windows show the menu bar by default?';
$string['configpopupresizable'] = 'Should popup windows be resizable by default?';
$string['configpopupscrollbars'] = 'Should popup windows be scrollable by default?';
$string['configpopupstatus'] = 'Should popup windows show the status bar by default?';
$string['configpopuptoolbar'] = 'Should popup windows show the tool bar by default?';
$string['configpopupwidth'] = 'What width should be the default width for new popup windows?';
$string['contentheader'] = 'Content';
$string['displayoptions'] = 'Available display options';
$string['displayselect'] = 'Display';
$string['displayselect_help'] = 'This setting, together with the file type and whether the browser allows embedding, determines how the file is displayed. Options may include:

* Automatic - The best display option for the file type is selected automatically
* Embed - The file is displayed within the page below the navigation bar together with the file description and any blocks
* Force download - The user is prompted to download the file
* Open - Only the file is displayed in the browser window
* In pop-up - The file is displayed in a new browser window without menus or an address bar
* In frame - The file is displayed within a frame below the navigation bar and file description
* New window - The file is displayed in a new browser window with menus and an address bar';
$string['displayselect_link'] = 'mod/file/mod';
$string['displayselectexplain'] = 'Choose display type, unfortunately not all types are suitable for all files.';
$string['dnduploadcopicloud'] = 'Create file copicloud';
$string['encryptedcode'] = 'Encrypted code';
$string['filenotfound'] = 'File not found, sorry.';
$string['filterfiles'] = 'Use filters on file content';
$string['filterfilesexplain'] = 'Select type of file content filtering, please note this may cause problems for some Flash and Java applets. Please make sure that all text files are in UTF-8 encoding.';
$string['filtername'] = 'Resource names auto-linking';
$string['forcedownload'] = 'Force download';
$string['framesize'] = 'Frame height';
$string['legacyfiles'] = 'Migration of old course file';
$string['legacyfilesactive'] = 'Active';
$string['legacyfilesdone'] = 'Finished';
$string['modulename'] = 'Copicloud';
$string['modulename_help'] = 'The file module enables a teacher to provide a file as a course copicloud. Where possible, the file will be displayed within the course interface; otherwise students will be prompted to download it. The file may include supporting files, for example an HTML page may have embedded images or Flash objects.

Note that students need to have the appropriate software on their computers in order to open the file.

A file may be used

* To share presentations given in class
* To include a mini website as a course copicloud
* To provide draft files of certain software programs (eg Photoshop .psd) so students can edit and submit them for assessment';
$string['modulename_link'] = 'mod/copicloud/view';
$string['modulenameplural'] = 'Files';
$string['neverseen'] = 'Never seen';
$string['notmigrated'] = 'This legacy copicloud type ({$a}) was not yet migrated, sorry.';
$string['optionsheader'] = 'Display options';
$string['page-mod-copicloud-x'] = 'Any file module page';
$string['pluginadministration'] = 'File module administration';
$string['pluginname'] = 'Copicloud';
$string['popupheight'] = 'Pop-up height (in pixels)';
$string['popupheightexplain'] = 'Specifies default height of popup windows.';
$string['popupcopicloud'] = 'This copicloud should appear in a popup window.';
$string['popupcopicloudlink'] = 'If it didn\'t, click here: {$a}';
$string['popupwidth'] = 'Pop-up width (in pixels)';
$string['popupwidthexplain'] = 'Specifies default width of popup windows.';
$string['printintro'] = 'Display copicloud description';
$string['printintroexplain'] = 'Display copicloud description below content? Some display types may not display description even if enabled.';
$string['copicloud:addinstance'] = 'Add a new copicloud';
$string['copicloudcontent'] = 'Files and subfolders';
$string['copiclouddetails_sizetype'] = '{$a->size} Copicloud';
$string['copicloud:exportcopicloud'] = 'Export copicloud';
$string['copicloud:view'] = 'View copicloud';
$string['selectmainfile'] = 'Please select the main file by clicking the icon next to file name.';
$string['showsize'] = 'Show size';
$string['showsize_help'] = 'Displays the file size, such as \'3.1 MB\', beside links to the file.

If there are multiple files in this copicloud, the total size of all files is displayed.';
$string['showsize_desc'] = 'Display file size on course page?';
$string['showtype'] = 'Show type';
$string['showtype_desc'] = 'Display file type (e.g. \'Word document\') on course page?';
$string['showtype_help'] = 'Displays the type of the file, such as \'Word document\', beside links to the file.

If there are multiple files in this copicloud, the start file type is displayed.

If the file type is not known to the system, it will not display.';
//Copicloud settings
//FILEDIR
$string['filedir_copicloud'] = 'Directorio copicloud';
$string['filedir_copicloudexplain'] = 'Directorio donde se almacenarán los archivos copicloud (Campo obligatorio para el funcionamiento del recurso)';
//TOKEN
$string['token_copicloud'] = 'Api key Copicloud';
$string['token_copicloudexplain'] = 'Token para la comunicación con servidor copicloud (Campo obligatorio para el funcionamiento del recurso)';
//UPLOAD
$string['upload_copicloud'] = 'Url para las subidas copicloud';
$string['upload_copicloudexplain'] = 'URL del servidor copicloud para subida de archivos (Campo obligatorio para el funcionamiento del recurso)';
//DELETE
$string['delete_copicloud'] = 'Url para eliminar recursos copicloud';
$string['delete_copicloudexplain'] = 'URL del servidor copicloud para borrar de archivos  (Campo obligatorio para el funcionamiento del recurso)';
//REDIRECT
$string['redirect_copicloud'] = 'Url necesaria para redireccionar con copicloud';
$string['redirect_copicloudexplain'] = 'URL para formar el enlace de los archivos subidos a copicloud (Campo obligatorio para el funcionamiento del recurso)';
