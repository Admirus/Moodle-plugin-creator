<?php


require_once(dirname(__FILE__) . '/../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once('lib.php');

$context = context_system::instance();
$PAGE->set_context( $context );

require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_heading($SITE->fullname);
$CFG->cachejs=false;
$PAGE->set_title($SITE->fullname . ': ' .'Plugin Creator');
$PAGE->set_url('/creator/index.php') ;
echo $OUTPUT->header();




$filemanageropts = array('subdirs' => 0, 'maxbytes' => '0', 'maxfiles' => 50, 'context' => $context);
$customdata = array('filemanageropts' => $filemanageropts);
$mform = new plugin_form(null, $customdata);

if ($mform->is_cancelled()) {

   
} else if ($data = $mform->get_data()) {
    // SUCCESS
    form_data($data);
    redirect('/my');
    
} else {
    // FAIL / DEFAULT
    echo '';
    $mform->display();
}


echo $OUTPUT->footer();
