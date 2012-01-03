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
		if($owner = $gtd->get_user_as(GlobalTechDev::OWNERS)) {
			echo '<h3 class="widgettitle"><a href="/add-project/" style="color:#9A1D0D;">add Project</a></h3>';
		}
		
		echo '<h3 class="widgettitle">';
		if($developer = $gtd->get_user_as(GlobalTechDev::DEVELOPERS))
			$gtd->get_edit_link($developer->ID, 'edit Developer', array('style'=>'color:#9A1D0D;', 'title'=>'Edit your Developer Profile'), true);
		else
			echo '<a href="/add-developer/" style="color:#9A1D0D;">add Developer</a>';
		echo '</h3>';
		
		if($owner) {
			echo '<h3 class="widgettitle">';
			$gtd->get_edit_link($owner->ID, 'edit Owner', array('style'=>'color:#9A1D0D;', 'title'=>'Edit your Project Owner Profile'), true);
			echo '</h3>';
		}
		else {
			echo '<h3 class="widgettitle"><a href="/add-owner/" style="color:#9A1D0D;" alt="Become a Project Owner">add Owner</a></h3>';
			echo '<div class="widgetcontent">Want to create a Project? Become an Owner first by clicking add Owner above.</div>';
		}
		
		echo '</div>';
	}
	
	public static function init_widget() {
		register_widget('GlobalTechDev_AddButtons_Widget');
	}
}
add_action('widgets_init', array('GlobalTechDev_AddButtons_Widget', 'init_widget'));