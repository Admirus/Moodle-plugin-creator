<?php

/**
 * Library file for the plugin creator
 *
 * @package   plugin_creator
 * @copyright 2019 Lagos Yiannis
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

class plugin_form extends moodleform {

    function definition() {

        $mform = $this->_form;
        $filemanageropts = $this->_customdata['filemanageropts'];

        $mform->addElement('text', 'name', 'Insert plugin name', null);
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('select', 'type', 'Plugin type', get_plugintypes(),null);
        $mform->addElement('text', 'localfilename', 'Change if you want name different than index.php as the main file', array('value'=>'index.php'));
        $mform->hideIf('localfilename','type',1);
        $mform->setType('localfilename', PARAM_TEXT);



        //AMD folder
        $mform->addElement('selectyesno', 'amd', 'Create AMD folders?'); 

        //classes folder
        $mform->addElement('selectyesno', 'classes', 'Create Classes folders?');
        $mform->addElement('selectyesno', 'output', 'Create Output folder?');

        $mform->addElement('selectyesno', 'outputfile', 'Create php file in Output folder?');
        $mform->hideIf('outputfile', 'output', '0');
        $mform->addElement('text', 'outputfilename', 'Insert output php file name', null);
        $mform->hideIf('outputfilename', 'outputfile', '0');
        $mform->setType('outputfilename', PARAM_TEXT);


        $mform->addElement('selectyesno', 'privacy', 'Create Privacy folder?');

        $mform->addElement('selectyesno', 'external', 'Create external.php file?');
        $mform->addElement('selectyesno', 'externalfunction', 'Create function in external.php?');
        $mform->hideIf('externalfunction', 'external', '0');
        $mform->addElement('text', 'externalfunctionname', 'Insert external function name', null);
        $mform->hideIf('externalfunctionname', 'externalfunction', '0');
        $mform->setType('externalfunctionname', PARAM_TEXT);

        $mform->addElement('selectyesno', 'renderer', 'Create renderer.php file ?');
        $mform->addElement('selectyesno', 'rendereroutput', 'Create Output php file in renderer.php?');
        $mform->hideIf('rendereroutput', 'renderer', '0');
        $mform->hideIf('rendereroutput', 'outputfile', '0');
        $mform->setType('rendereroutput', PARAM_TEXT);
        

        $mform->hideIf('output', 'classes', '0');
        $mform->hideIf('privacy', 'classes', '0');
        $mform->hideIf('external', 'classes', '0');
        $mform->hideIf('renderer', 'classes', '0');

        //DB folder
        $mform->addElement('selectyesno', 'db', 'Create db folder?');
        $mform->addElement('selectyesno', 'access', 'Create access.php file?');
        $mform->addElement('selectyesno', 'services', 'Create services.php file?');
        $mform->addElement('selectyesno', 'install', 'Create install.xml file?');

        $mform->hideIf('access', 'db', '0');
        $mform->hideIf('services', 'db', '0');
        $mform->hideIf('install', 'db', '0');

        //LANG folder
        $mform->addElement('selectyesno', 'lang', 'Create Lang folder?');

        //TEMPLATES folder
        $mform->addElement('selectyesno', 'templates', 'Create Templates folder?');
        $mform->addElement('selectyesno', 'templatesfile', 'Create Templates file?');
        $mform->hideIf('templatesfile', 'templates', '0');
        $mform->addElement('text', 'templatesfilename', 'Insert Templates file name', null);
        $mform->hideIf('templatesfilename', 'templatesfile', '0');
        $mform->setType('templatesfilename', PARAM_TEXT);

        // Buttons
        $this->add_action_buttons();
    }
}

require_once('phpcode/strings.php');
function get_plugintypes(){
    $types[]='block';
    $types[]='local';
   // $types[]='format';
   // $types[]='tool';

    return $types;
}

function form_data($data){
    global $CFG;
    // echo "<pre>";
    // print_r($data);
    // echo"</pre>";die();
    $type=get_type($data->type);
    $name=$data->name;
    mkdir('../'.$type.'/'.$name);
    create_version($type,$name);
    if($data->localfilename && $type=='local'){
        if($data->renderer==1 && $data->rendereroutput==1 && $data->outputfilename){
        create_main_file($type,$name,$data->localfilename,$data->outputfilename);
    }else{
        create_main_file($type,$name,$data->localfilename);
    }
    }else{
         if($data->renderer==1 && $data->rendereroutput==1 && $data->outputfilename){
        create_main_file($type,$name,null,$data->outputfilename);
    }else{
        create_main_file($type,$name);
    }
    }
    if($data->amd==1){
        create_amd($type,$name);
    }

    if($data->classes==1){
        $items=array();
        if($data->output==1){
            $items[]='output';
            if($data->outputfile==1 && $data->outputfilename){
            $items['outputfile']=$data->outputfilename;
        }
        }
        if($data->privacy==1){
            $items[]='privacy';
        }
        if($data->external==1){
            $items[]='external';
            if($data->externalfunction==1 && $data->externalfunctionname){
                $items['externalfunction']=$data->externalfunctionname;
            }
        }
        if($data->renderer==1){
            $items[]='renderer';
            if($data->rendereroutput==1 && $data->outputfilename){
                $items['rendereroutput']=$data->outputfilename;
                if($data->templatesfile==1 && $data->templatesfilename){
                    $items['templatesfile']=$data->templatesfilename;
                }
            }
        }
        
        create_classes($type,$name,$items);

    }

    if($data->db==1){
        $items=array();
        if($data->access==1){
            $items[]='access';
        }
        if($data->services==1){
             if($data->external==1 && $data->externalfunction==1 && $data->externalfunctionname){
                $items['externalfunction']=$data->externalfunctionname;
            }
            $items[]='services';
        }
        if($data->install==1){
            $items[]='install';
        }
        create_db($type,$name,$items);
    }
    if($data->lang==1){
        create_lang($type,$name);
    }
    if($data->templates==1){
        if($data->templatesfile==1 && $data->templatesfilename){
            create_templates($type,$name,$data->templatesfilename);
        }else{
            create_templates($type,$name);
        }
    }
}

function get_type($type){

    switch ($type) {
        case '0':
           return 'block';
            break;
        case '1':
           return 'local';
            break;
        case '2':
           return 'format';
            break;
        case '3':
           return 'tool';
            break;
        
        default:
            # code...
            break;
    }
}

function create_main_file($type,$name,$localfilename=null,$outputfilename=null){
    $path='../'.$type.'/'.$name;
    if($type=='block'){
        $file=fopen($path.'/block_'.$name,'w');

        fwrite($file,get_main_block_file($type,$name,$outputfilename));
    }else if($type=='local'){
        $file=fopen($path.'/'.$localfilename,'w');
        fwrite($file,get_local_main_file($type,$name,$localfilename,$outputfilename));
    }
}

function create_amd($type,$name){
    global $CFG;
    mkdir('../'.$type.'/'.$name.'/amd');
    mkdir('../'.$type.'/'.$name.'/amd/build');
    mkdir('../'.$type.'/'.$name.'/amd/src');

}

function create_classes($type,$name,$items){
    global $CFG;
    $path='../'.$type.'/'.$name;
    mkdir($path.'/classes');
    foreach ($items as $item){
        if($item=='privacy'){
            mkdir($path.'/classes/privacy');
            continue;
        }
        if($item=='output'){
            mkdir($path.'/classes/output');
            if($items['outputfile']){
                $file=fopen($path.'/classes/output/'.$items['outputfile'],'w');
                fwrite($file,get_output_file($type,$name,$items['outputfile']));

    }
            continue;
        }
        if($item=='external'){
           $file=fopen($path.'/classes/external.php','w');
           if($items['externalfunction']){
            fwrite($file,get_external_file($type,$name,$items['externalfunction']));
           }else{
            fwrite($file,get_external_file($type,$name));
           }
            continue;
        }
        if($item=='renderer'){
           $file=fopen($path.'/classes/renderer.php','w');
           if($items['rendereroutput']){
            if($items['templatesfile']){
            fwrite($file,get_renderer_file($type,$name,$items['rendereroutput'],$items['templatesfile']));
            }else{
            fwrite($file,get_renderer_file($type,$name,$items['rendereroutput']));  
            }
           }else{
            fwrite($file,get_renderer_file($type,$name));  

           }
            continue;
        }

    }
    
}

function create_db($type,$name,$items){
    global $CFG;
    $path='../'.$type.'/'.$name;
    mkdir($path.'/db');
    foreach($items as $item){
        if($item=='access'){
           $file=fopen($path.'/db/access.php','w');
            fwrite($file,get_access_file($type,$name));  

            continue;
        }
        if($item=='services'){
           $file=fopen($path.'/db/services.php','w');
           if($items['externalfunction']){
            fwrite($file,get_services_file($type,$name,$items['externalfunction']));  
           }else{
            fwrite($file,get_services_file($type,$name));  

           }

            continue;
        }
        if($item=='install'){
           $file=fopen($path.'/db/install.xml','w');
            fwrite($file,get_install_file($type,$name));  

            continue;
        }
    }
}

function create_lang($type,$name){
     global $CFG;
    $path='../'.$type.'/'.$name;
    mkdir($path.'/lang');
    mkdir($path.'/lang/en');
    $file=fopen($path.'/lang/en/'.$type.'_'.$name.'.php','w');
    fwrite($file,get_lang_file($type,$name));  

}

function create_templates($type,$name,$templatesfilename=null){
         global $CFG;
    $path='../'.$type.'/'.$name;
    mkdir($path.'/templates');
    if($templatesfilename!=null){
        $file=fopen($path.'/templates/'.$templatesfilename.'.mustache','w');
    }
}

function create_lib($type,$name){
    global $CFG;
    $path='../'.$type.'/'.$name;
    fopen($path.'/lib.php','w');
}
function create_version($type,$name){
    global $CFG;
    $path='../'.$type.'/'.$name;
    $file=fopen($path.'/version.php','w');
    fwrite($file,get_version_file($type,$name));
}