<?php



/**
 * Definition of log events
 *
 * @package    mod_copicloud
 * @category   log
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module'=>'copicloud', 'action'=>'view', 'mtable'=>'copicloud', 'field'=>'name'),
    array('module'=>'copicloud', 'action'=>'view all', 'mtable'=>'copicloud', 'field'=>'name'),
    array('module'=>'copicloud', 'action'=>'update', 'mtable'=>'copicloud', 'field'=>'name'),
    array('module'=>'copicloud', 'action'=>'add', 'mtable'=>'copicloud', 'field'=>'name'),
);
