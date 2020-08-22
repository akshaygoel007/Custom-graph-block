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
 * Form for editing HTML block instances.
 *
 * @package   block_v7_score_progress
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 //	global $CFG;
 // require_once($CFG->dirroot . '/blocks/v7_score_progress/amd/src/samples/charts/bar/vertical.php');

class block_v7_score_progress extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_v7_score_progress');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        if (isset($this->config->title)) {
			getChart();
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newv7scoreprogressblock', 'block_v7_score_progress');
        }
    }

    function instance_allow_multiple() {
        return true;
    }
	
	
    function get_content() {
       
/*
	   global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== NULL) {
            return $this->content;
        }

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // fancy html allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        if (isset($this->config->text)) {
            // rewrite url
            $this->config->text = file_rewrite_pluginfile_urls($this->config->text, 'pluginfile.php', $this->context->id, 'block_v7_score_progress', 'content', NULL);
            // Default to FORMAT_HTML which is what will have been used before the
            // editor was properly implemented for the block.
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            $this->content->text = format_text($this->config->text, $format, $filteropt);
        } else {
            $this->content->text = '';
        }

        unset($filteropt); // memory footprint
*/

      global $DB, $USER;
    if ($this->content !== null) {
      return $this->content;
    }
 
    $this->content         =  new stdClass;


 $enrol_get_all = enrol_get_all_users_courses($USER->id, true, null);
	$licensureValStr='<li>
                                        <a class="dropdown-item active" href="#" data-filter="sort" data-pref="title" data-value="All" aria-label="Sort courses by course name" aria-controls="courses-view-5f2be4670a7165f2be466c58386">All </a>
                                    </li>';
									$arr_licenses[0]='All';
									$arr_license_courses[0]='';
									$i=0;
									$j=0;

	foreach($enrol_get_all as $enr)
	{
		$res = core_course_category::get($enr->category);
		//$category = $DB->get_record('course_categories',$res);
		$path = explode('/',$res->path);
		// echo $res->path;
		if(count($path)>2)	
			$root_category_id = $path[2];
		else
			$root_category_id = $path[1];
		$root_category = $DB->get_record('course_categories',array('id'=>$root_category_id));
		$courseid[]= $enr->id;
		$catid[] = $res->name;
		$j=array_search($root_category->name,$arr_licenses);
		// echo $j;
		if(!in_array($root_category->name,$arr_licenses))
		{
			$i++;
				$arr_licenses[$i]=$root_category->name;
				$arr_license_courses[$i]=$enr->id;
		}
		else
		{
			$arr_license_courses[$j].=','.$enr->id;
		}
		$a = array_fill_keys($courseid,$catid);
	}

	$arr_license_courses_combined=array();
	for($i=1;$i<count($arr_licenses);$i++)
    {
		$arr_license_courses_combined[$arr_licenses[$i]]=$arr_license_courses[$i];
	}
	$arr_license_courses_combined['All']='';
	ksort($arr_license_courses_combined);
	
	
    foreach($arr_license_courses_combined as $license => $courses) 
	{
		if($license=='All')
			continue;
		$licensureValStr.='  <li><a class="dropdown-item" data-id="'.$i.'" href="#" data-filter="sort" data-pref="title" data-value="'.$license.'" aria-label="Sort courses by course name" aria-controls="courses-view-5f2be4670a7165f2be466c58386">'.$license.' </a></li>';
     }
     
    // print_r($i);
    // exit();
	/*
	$records = $DB->get_recordset_sql("select mqg.grade,concat(TO_CHAR(date(to_timestamp(mqg.timemodified)),'Mon'),' ',DATE_PART('day', date(to_timestamp(mqg.timemodified)))) as date,mq.name from {quiz_grades} mqg, {quiz} mq where mqg.userid = :userid
		  and mq.id = mqg.quiz and to_timestamp(mqg.timemodified) 
        > NOW() - INTERVAL '60 days'", array('userid' => $USER->id));
       */ 
        $records = $DB->get_recordset_sql("select mqg.grade,NOW() as date,mq.name from {quiz_grades} mqg, {quiz} mq where mqg.userid = 46
		  and mq.id = mqg.quiz 
		", array('userid' => $USER->id));
   // $records = recordset_to_array($rs);
	 
	if ($records->valid())
	{
		foreach ($records as $record) 
		{
				if(!empty($record->grade))
				  $allrows[]=$record->grade;
				if(!empty($record->date))
				  $alldate[]=$record->date;
			  	if(!empty($record->name))
                  $allcourse[]=$record->name;
            }
           // print_r($allcourse);
           // exit();
			
		for($i=0;$i<count($alldate);$i++)
		{
		for($j=$i;$j<=$i;$j++)
		{
			$k[]=$alldate[$i].' ( '.$allcourse[$j].')';
		}
		
		}
	}
	else return true;
	
   $this->content->text   = '<div id="container" style="width: 75%;">

   <div class="col col-sm-6 text-right">
   <div class="dropdown d-inline-block">
     <button id="sortingdropdown" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Sorting drop-down menu">
       <i class="icon fa fa-filter fa-fw mr-0" aria-hidden="true"></i>                                        
       <span class="d-sm-inline-block" data-active-item-text="">
             Exam Domain
         </span>
     </button>
     <ul class="dropdown-menu" data-show-active-item="" aria-labelledby="sortingdropdown">
         <li>
             <a class="dropdown-item active" href="#" data-filter="sort" data-pref="title" data-value="fullname" aria-label="Sort courses by course name" aria-controls="courses-view-5f2be4670a7165f2be466c58386">
             '.$licensureValStr .'
             </a>
         </li>
         <li>
             <a class="dropdown-item " href="#" data-filter="sort" data-pref="lastaccessed" data-value="ul.timeaccess desc" aria-label="Sort courses by last accessed date" aria-controls="courses-view-5f2be4670a7165f2be466c58386">
                 Last accessed
             </a>
         </li>
     </ul>
   </div>
 </div>
</div>

		<canvas id="myChart" width="400" height="200"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
                    var i;
            var arraylength= '.$arrayLen .';
            
            for (i = 0; i < arraylength+1; i++) {
                var allcourse ='. 
                             '["' . implode('", "', $allcourse) . '"]'.';
            <script>
            
       
			var ctx = document.getElementById(\'myChart\').getContext(\'2d\');
			//Using PHP implode() function 
			var passedArray = '.
				'["' . implode('", "', $allrows) . '"];'.				
			'var dateArray =' .
				'["' . implode('", "', $k) . '"];'.
			'var courseArray =' .
				'["' . implode('", "', $allcourse) . '"];'.
		//	'console.log(courseArray);'.
			// Printing the passed array elements 
			'var myChart = new Chart(ctx, {
				type: \'bar\',
				
				//var test = <?php print_r($string);?>
				//console.log(test);
				data: {
					labels: dateArray,
					datasets: [{
						label: \'No. of Scores\',
						data: passedArray,
						backgroundColor: [
							\'rgba(255, 99, 132, 0.2)\',
							\'rgba(54, 162, 235, 0.2)\',
							\'rgba(255, 206, 86, 0.2)\',
							\'rgba(75, 192, 192, 0.2)\',
							\'rgba(153, 102, 255, 0.2)\',
							\'rgba(255, 159, 64, 0.2)\'
						],
						borderColor: [
							\'rgba(255, 99, 132, 1)\',
							\'rgba(54, 162, 235, 1)\',
							\'rgba(255, 206, 86, 1)\',
							\'rgba(75, 192, 192, 1)\',
							\'rgba(153, 102, 255, 1)\',
							\'rgba(255, 159, 64, 1)\'
						],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
				
			});
			</script>
</div>';
    //$this->content->footer = 'Footer here...';
 
    return $this->content;
    }

    public function get_content_for_external($output) {
        global $CFG;
        require_once($CFG->libdir . '/externallib.php');

        $bc = new stdClass;
        $bc->title = null;
        $bc->content = '';
        $bc->contenformat = FORMAT_MOODLE;
        $bc->footer = '';
        $bc->files = [];

        if (!$this->hide_header()) {
            $bc->title = $this->title;
        }

        if (isset($this->config->text)) {
            $filteropt = new stdClass;
            if ($this->content_is_trusted()) {
                // Fancy html allowed only on course, category and system blocks.
                $filteropt->noclean = true;
            }

            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config.
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            list($bc->content, $bc->contentformat) =
                external_format_text($this->config->text, $format, $this->context, 'block_v7_score_progress', 'content', null, $filteropt);
            $bc->files = external_util::get_area_files($this->context->id, 'block_v7_score_progress', 'content', false, false);

        }
        return $bc;
    }


    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
        global $DB;

        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match
        $config->text = file_save_draft_area_files($data->text['itemid'], $this->context->id, 'block_v7_score_progress', 'content', 0, array('subdirs'=>true), $data->text['text']);
        $config->format = $data->text['format'];

        parent::instance_config_save($config, $nolongerused);
    }

    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_v7_score_progress');
        return true;
    }
	

    /**
     * Copy any block-specific data when copying to a new block instance.
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid) {
        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();
        // This extra check if file area is empty adds one query if it is not empty but saves several if it is.
        if (!$fs->is_area_empty($fromcontext->id, 'block_v7_score_progress', 'content', 0, false)) {
            $draftitemid = 0;
            file_prepare_draft_area($draftitemid, $fromcontext->id, 'block_v7_score_progress', 'content', 0, array('subdirs' => true));
            file_save_draft_area_files($draftitemid, $this->context->id, 'block_v7_score_progress', 'content', 0, array('subdirs' => true));
        }
        return true;
    }

    function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        //find out if this block is on the profile page
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // this is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here
                return true;
            } else {
                // no JS on public personal pages, it would be a big security issue
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /*
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty($CFG->block_v7_score_progress_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        global $CFG;

        // Return all settings for all users since it is safe (no private keys, etc..).
        $instanceconfigs = !empty($this->config) ? $this->config : new stdClass();
        $pluginconfigs = (object) ['allowcssclasses' => $CFG->block_v7_score_progress_allowcssclasses];

        return (object) [
            'instance' => $instanceconfigs,
            'plugin' => $pluginconfigs,
        ];
    }
}