<?php


/**
 * Strings for component 'copicloud', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    mod
 * @subpackage copicloud
 */

$string['clicktodownload'] = 'Click {$a} enlace para descargar el archivo.';
$string['clicktoopen2'] = 'Click {$a} enlace para descargar el archivo.';
$string['configdisplayoptions'] = 'Seleccione todas las opciones que deben estar disponibles , la configuración existente no se modifican . Mantener pulsada la tecla CTRL para seleccionar varios campos .';
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
$string['displayoptions'] = 'Opciones de visualización disponibles';
$string['displayselect'] = 'Visualización';
$string['displayselect_help'] = 'This setting, together with the file type and whether the browser allows embedding, determines how the file is displayed. Options may include:

* Automatic - The best display option for the file type is selected automatically
* Embed - The file is displayed within the page below the navigation bar together with the file description and any blocks
* Force download - The user is prompted to download the file
* Open - Only the file is displayed in the browser window
* In pop-up - The file is displayed in a new browser window without menus or an address bar
* In frame - The file is displayed within a frame below the navigation bar and file description
* New window - The file is displayed in a new browser window with menus and an address bar';
$string['displayselect_link'] = 'mod/file/mod';
$string['displayselectexplain'] = 'Tipo de pantalla';
$string['dnduploadcopicloud'] = 'Create file copicloud';
$string['encryptedcode'] = 'Encrypted code';
$string['filenotfound'] = 'File not found, sorry.';
$string['filterfiles'] = 'Use filters on file content';
$string['filterfilesexplain'] = '';
$string['filtername'] = 'Resource names auto-linking';
$string['forcedownload'] = 'Force download';
$string['framesize'] = 'Frame height';
$string['legacyfiles'] = 'Migration of old course file';
$string['legacyfilesactive'] = 'Active';
$string['legacyfilesdone'] = 'Finished';
$string['modulename'] = 'Copicloud';
$string['modulename_help'] = 'Este módulo permite el almacenamiento e impresión de documentos en Copicloud. Los documentos subidos a esta plataforma entran dentro del espacio "apuntes", de modo que los alumnos pueden exclusivamente imprimir el documento en las copisterias Copicloud.

De esta forma Copicloud se hace una solución idónea para asegurar el cumplimiento de los derechos de autor.
';
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
$string['printintro'] = 'Descripción de la visualización Copicloud';
$string['printintroexplain'] = 'Pantalla copicloud descripción siguiente contenido? Puede que en algunos tipos de pantalla no se muestre la descripción incluso si está habilitado.';
$string['copicloud:addinstance'] = 'Add a new copicloud';
$string['copicloudcontent'] = 'Files and subfolders';
$string['copiclouddetails_sizetype'] = '{$a->size} Copicloud';
$string['copicloud:exportcopicloud'] = 'Export copicloud';
$string['copicloud:view'] = 'View copicloud';
$string['selectmainfile'] = 'Please select the main file by clicking the icon next to file name.';
$string['showsize'] = 'Mostrar tamaño';
$string['showsize_help'] = 'Displays the file size, such as \'3.1 MB\', beside links to the file.

If there are multiple files in this copicloud, the total size of all files is displayed.';
$string['showsize_desc'] = 'Mostrar el tamaño del documento en la página del curso';
$string['showtype'] = 'Mostrar tipo';
$string['showtype_desc'] = 'Mostrar el tipo de documento en la página del curso';
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
