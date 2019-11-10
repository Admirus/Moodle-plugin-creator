<?php

/**
 * Lang strings for the creator module.
 *
 * @package    creator_plugin
 * @copyright  2019 Lagos Yiannis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function get_version_file($type,$name){
 return "<?php
defined('MOODLE_INTERNAL') || die();

\$plugin->version   = 2018120300;         
\$plugin->requires  = 2018112800;         
\$plugin->component = '".$type."_".$name."'; 
";
}

function get_main_block_file($type,$name,$outputfilename=null){
    if($outputfilename==null){
        $outputfilename='SKIP';
    }
        $content="<?php
defined('MOODLE_INTERNAL') || die();
    
class ".$type."_".$name." extends block_base
{
      
    public function init() {
        \$this->title = 'INSERT_TITLE';
    }
/**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }
        /**
     * Core function, specifies where the block can be used.
     * @return array
     */
    public function applicable_formats() {
        return array('my' => true);
    }
        /**
     * Allows the block to be added multiple times to a single page
     * @return boolean
     */
    public function instance_allow_multiple() {
        return true;
    }
    /**
     * Returns the contents.
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        if (isset(\$this->content)) {
            return \$this->content;
        }

        

        \$this->content = new stdClass();
        \$this->content->header='';
        \$this->content->text='';


        //if output file exists then remove the below comments

       // \$renderable = new ".$type."_".$name."\output\$outputfilename();
       // \$renderer = \$this->page->get_renderer('".$type."_".$name."');
       // \$this->content->text = $renderer->render($renderable);
        

        
        
        \$this->content->footer = '';
        return \$this->content;

        return \$this->content;
    }

   
}";
    
    return $content;
}

function get_local_main_file($type,$name,$localfilename,$outputfilename=null){
    if($outputfilename==null){
        $outputfilename="SKIP";
    }
    $content="<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_login();
\$PAGE->set_context(context_system::instance());
\$PAGE->set_heading(\$SITE->fullname);
\$PAGE->set_title(\$SITE->fullname . ': ' . get_string('pluginname', 'local_$name'));
\$PAGE->set_url('http://localhost/moodle/local/$name/index.php') ;
echo \$OUTPUT->header();


//if output file exists remove the below comments

//\$renderable = new local_something\output\\$outputfilename();
//echo \$OUTPUT->render_from_template('local_$name/$outputfilename', \$renderable);


echo \$OUTPUT->footer();
";
return $content;
}

function get_external_file($type,$name,$functionname=null){
if($functionname==null){
    $functionname='INSERT_YOUR_OWN_NAME';
}
	$content="<?php

defined('MOODLE_INTERNAL') || die();

require_once(\$CFG->libdir . '/externallib.php');
class ".$type."_".$name."_external extends external_api {


//if function name exists remove the below comments

//     public static function ".$functionname."_parameters() {
//         return new external_function_parameters(
//             array(
//                 //change var1 depending on your variable
//                 'var1' => new external_value(PARAM_RAW, 'Number of courses to display', VALUE_REQUIRED)
//             )
//         );
//     }

// public static function $functionname(\$var1){
// 	global \$DB,\$CFG,\$PAGE;
//             \$params = self::validate_parameters(self::".$functionname."_parameters(), [
//             'var1' => \$var1
//         ]);
//             \$var1=\$params['var1'];
//             //insert code here
//     }
   
// public static function ".$functionname."_returns() {
        
//     }  
}";



return $content;
}

function get_output_file($type,$name,$outputfilename){
	return "<?php

namespace ".$type."_".$name."\\output;
defined('MOODLE_INTERNAL') || die();



use renderer_base;

class $outputfilename implements \\renderable, \\templatable {

    public function export_for_template(renderer_base \$output) {

      \$example='Example string';
        return \$example;

    }
}";
}

function get_renderer_file($type,$name,$outputfilename=null,$templatesfilename=null){
	if($outputfilename!=null){
		if($templatesfilename==null){
		$content="<?php

namespace ".$type."_".$name."\output;
defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_$outputfilename($outputfilename \$$outputfilename) {
        return \$this->render_from_template('".$type."_".$name."/INSERT_TEMPLATES_NAME', \$".$outputfilename."->export_for_template(\$this));
    }
    
}";
	}else{
		$content="<?php

namespace ".$type."_".$name."\output;
defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {


    public function render_$outputfilename($outputfilename \$$outputfilename) {
        return \$this->render_from_template('".$type."_".$name."/$templatesfilename', \$".$outputfilename."->export_for_template(\$this));
    }
    
}";
	}
}else{
	$content="<?php

namespace ".$type."_".$name."\output;
defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;


class renderer extends plugin_renderer_base {
    
}";
}
return $content;
}

function get_access_file($type,$name){
	return "<?php


defined('MOODLE_INTERNAL') || die();

\$capabilities = array(

    '$type/$name:INSERT_CAPABILITY_NAME' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
           
        ),
    )
);";
}

function get_services_file($type,$name,$externalfunctionname=null){
    if($externalfunctionname==null){
        $externalfunctionname='INSERT_YOUR_EXTERNAL_FUNCTION_NAME';
    }
	return "<?php


defined('MOODLE_INTERNAL') || die();

\$functions = array(

    '".$type."_".$name."_$externalfunctionname' => array(
        'classpath' => '$type/$name/classes/external.php',
        'classname'   => '".$type."_".$name."_external',
        'methodname'  => '$externalfunctionname',
        'description' => 'Get users rated courses.',
        'type'        => 'read',
        'ajax'        => true,
    )
);";
}

function get_install_file($type,$name){
	return '<?xml version="1.0" encoding="UTF-8" ?>
<XMLDB PATH="'.$type.'/'.$name.'/db" VERSION="20191030" COMMENT="XMLDB file for Moodle $type/$name"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="../../../lib/xmldb/xmldb.xsd"
>
  <TABLES>
    <TABLE NAME="'.$type.'_INSERT_TABLE_NAME" COMMENT="">
      <FIELDS>
        <FIELD NAME="id" TYPE="int" LENGTH="10" NOTNULL="true" SEQUENCE="true"/>
      </FIELDS>
      <KEYS>
        <KEY NAME="primary" TYPE="primary" FIELDS="id"/>
      </KEYS>
    </TABLE>
  </TABLES>
</XMLDB>';
}

function get_lang_file($type,$name){
	return "<?php


\$string['pluginname'] = '$name';";
}