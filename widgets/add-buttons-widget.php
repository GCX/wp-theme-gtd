<?php

class GlobalTechDev_AddButtons_Widget extends WP_Widget {
	
	/**
	* Constructor
	*/
	public function __construct() {
		$config = array(
			'classname'   => 'gtd-add-buttons',
			'description' => 'Shows the Add Buttons',
		);
		parent::__construct('globaltechdev_addbuttons_widget', 'GTD: Add Buttons', $config);
	}
	
	public function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		$gtd = GlobalTechDev::singleton();
		if($owner = $gtd->is_user_an_owner()) {
			echo '<h3 class="widgettitle"><a href="/add-project/" style="color:#9A1D0D;">add Project</a></h3>';
		}
		if(!$gtd->is_user_a_developer()) {
			echo '<h3 class="widgettitle"><a href="/add-developer/" style="color:#9A1D0D;" alt="Become a Developer">add Developer</a></h3>';
		}
		else {
			echo '<h3 class="widgettitle"><a href="/add-developer/" style="color:#9A1D0D;" alt="Edit your Developer Profile">edit Developer</a></h3>';
		}
		
		if(!$owner) {
			echo '<h3 class="widgettitle"><a href="/add-owner/" style="color:#9A1D0D;" alt="Become a Project Owner">add Owner</a></h3>';
			echo '<div class="widgetcontent">Want to create a Project? Become an Owner first by clicking add Owner above.</div>';
		}
		else {
			echo '<h3 class="widgettitle"><a href="/add-owner/" style="color:#9A1D0D;" alt="Edit your Project Owner Profile">edit Owner</a></h3>';
		}
		
		echo '</div>';
	}
	
	public static function init_widget() {
		register_widget('GlobalTechDev_AddButtons_Widget');
	}
}
add_action('widgets_init', array('GlobalTechDev_AddButtons_Widget', 'init_widget'));