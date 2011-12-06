<?php

require_once get_stylesheet_directory() . '/widgets/add-buttons-widget.php';

class GlobalTechDev {
	/**
	 * Singleton instance
	 * @var GlobalTechDev
	 */
	private static $instance;

	/**
	 * Returns the GlobalTechDev singleton
	 * @code $gtd = GlobalTechDev::singleton();
	 * @return GlobalTechDev
	 */
	public static function singleton() {
		if(!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Prevent cloning of the GlobalTechDev object
	 */
	private function __clone() {}
	
	/**
	 * Developer post type
	 * @var string
	 */
	const DEVELOPERS = 'gtd_developers';
	
	/**
	* Project Owner post type
	* @var string
	*/
	const OWNERS = 'gtd_owners';
	
	/**
	* Project post type
	* @var string
	*/
	const PROJECTS = 'gtd_projects';
	
	/**
	* Developer Skills custom taxonomy
	* @var string
	*/
	const SKILLS = 'gtd_skills';
	
	/**
	* Developers to Projects post2post link id
	* @var string
	*/
	const DEVELOPERS_TO_PROJECTS = 'developers_to_projects';
	
	/**
	 * GTD Custom post types
	 * @var array
	 */
	static public $ALL = array(
		self::DEVELOPERS,
		self::OWNERS,
		self::PROJECTS,
	);
	
	/**
	 * Base absolute directory of the theme
	 * @var string
	 */
	public $base_dir;
	
	/**
	 * Base url of the theme
	 * @var string
	 */
	public $base_url;
	
	/**
	* GlobalTechDev class constructor
	*
	* Do not instantiate this class, instead use the singleton object.
	* @code $gtd = GlobalTechDev::singleton();
	*/
	private function __construct() {
		$this->base_dir = get_stylesheet_directory();
		$this->base_url = get_stylesheet_directory_uri();
		
		$this->register_hooks();
	}
	
	/**
	 * Register action and filter hooks with WordPress
	 */
	private function register_hooks() {
		add_action('init', array(&$this, 'register_post_types'));
		add_filter('pre_get_posts', array(&$this, 'get_posts_custom_types'));
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
		add_filter('gform_post_data', array(&$this, 'modify_gravityform_post_data'), 5, 2);
		add_action('after_setup_theme', array(&$this, 'register_sidebars'), 12, 0);
		add_filter('the_content', array(&$this, 'build_post_content'), 10, 1);
	}
	
	/**
	 * Register custom post types, taxonomies and post2post links
	 */
	public function register_post_types() {
		//Register the Projects (gtd_projects) Post Type
		register_post_type(
			self::PROJECTS,
			array(
				'label'           => 'Projects',
				'description'     => 'Projects that owners and developers would like to see come to life.',
				'public'          => true,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'capability_type' => 'post',
				'hierarchical'    => false,
				'rewrite'         => array(
					'slug'       => 'projects',
					'with_front' => false,
				),
				'query_var'       => true,
				'has_archive'     => true,
				'menu_position'   => 30,
				'supports'        => array(
					'title',
					'custom-fields',
					'thumbnail',
					'author',
				),
				'taxonomies'      => array(
					'post_tag',
				),
				'labels'          => array(
					'name'               => 'Projects',
					'singular_name'      => 'Project',
					'menu_name'          => 'Projects',
					'add_new'            => 'Add New',
					'add_new_item'       => 'Add New Project',
					'edit'               => 'Edit',
					'edit_item'          => 'Edit Project',
					'new_item'           => 'New Project',
					'view'               => 'View Project',
					'view_item'          => 'View Project',
					'search_items'       => 'Search Projects',
					'not_found'          => 'No Projects Found',
					'not_found_in_trash' => 'No Projects found in Trash',
					'parent'             => 'Parent Project',
				),
			)
		);
		
		//Register the Owners (gtd_owners) Post Type
		register_post_type(
			self::OWNERS,
			array(
				'label'           => 'Owners',
				'description'     => '',
				'public'          => true,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'capability_type' => 'post',
				'hierarchical'    => false,
				'rewrite'         => array(
					'slug'       => 'owners',
					'with_front' => false,
				),
				'query_var'       => true,
				'has_archive'     => true,
				'menu_position'   => 32,
				'supports'        => array(
					'title',
					'custom-fields',
					'thumbnail',
					'author',
				),
				'labels'          => array(
					'name'               => 'Owners',
					'singular_name'      => 'Owner',
					'menu_name'          => 'Owners',
					'add_new'            => 'Add Owner',
					'add_new_item'       => 'Add New Owner',
					'edit'               => 'Edit',
					'edit_item'          => 'Edit Owner',
					'new_item'           => 'New Owner',
					'view'               => 'View Owner',
					'view_item'          => 'View Owner',
					'search_items'       => 'Search Owners',
					'not_found'          => 'No Owners Found',
					'not_found_in_trash' => 'No Owners Found in Trash',
					'parent'             => 'Parent Owner',
				),
			)
		);
		
		//Register the Developers (gtd_developers) Post Type
		register_post_type(
			self::DEVELOPERS,
			array(
				'label'           => 'Developers',
				'description'     => 'Programmers, Designers, System Administrators',
				'public'          => true,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'capability_type' => 'post',
				'hierarchical'    => false,
				'rewrite'         => array(
					'slug'       => 'developers',
					'with_front' => false,
				),
				'query_var'       => true,
				'has_archive'     => true,
				'menu_position'   => 35,
				'supports'        => array(
					'title',
					'custom-fields',
					'thumbnail',
					'author',
				),
				'labels'          => array(
					'name'               => 'Developers',
					'singular_name'      => 'Developer',
					'menu_name'          => 'Developers',
					'add_new'            => 'Add New',
					'add_new_item'       => 'Add New Developer',
					'edit'               => 'Edit',
					'edit_item'          => 'Edit Developer',
					'new_item'           => 'New Developer',
					'view'               => 'View Developer',
					'view_item'          => 'View Developer',
					'search_items'       => 'Search Developers',
					'not_found'          => 'No Developers Found',
					'not_found_in_trash' => 'No Developers found in Trash',
					'parent'             => 'Parent Developer',
				),
			)
		);
		
		//Register the Skills (gtd_skills) taxonomy and asign it to gtd_developers post type
		register_taxonomy(
			self::SKILLS,
			array(0 => self::DEVELOPERS),
			array(
				'public'         => true,
				'hierarchical'   => false,
				'label'          => 'Skills',
				'show_ui'        => true,
				'show_tagcloud'  => true,
				'query_var'      => true,
				'rewrite'        => array(
					'slug'       => 'skills',
					'with_front' => false,
				),
				'singular_label' => 'Skill'
			)
		);
		
		// Use post2post plugin to handle related posts
		// http://wordpress.org/extend/plugins/posts-to-posts/
		// Must be installed and activated
		if(function_exists('p2p_register_connection_type')) {
			
			//Create a many-to-many relationship between Developers and Projects
			p2p_register_connection_type(array(
				'id'                 => self::DEVELOPERS_TO_PROJECTS,
				'from'               => self::DEVELOPERS,
				'to'                 => self::PROJECTS,
				'prevent_duplicates' => true,
			));
			
		}
	}
	
	/**
	 * Register additional sidebars for GTD
	 */
	public function register_sidebars() {
		foreach(array('Developer', 'Owner', 'Project', 'Add Developer', 'Add Owner', 'Add Project') as $name) {
			register_sidebar(array(
				'name'          => $name,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div></div><!-- end .widget -->',
				'before_title'  => '<h3 class="widgettitle">',
				'after_title'   => '</h3><div class="widgetcontent">',
			));
		}
	}
	
	/**
	 * Modifies the front page WP_Query to show projects, owners and developers
	 * 
	 * @param WP_Query $query
	 * @return WP_Query
	 */
	public function get_posts_custom_types($query) {
		if(is_home() && $query->query_vars['suppress_filters'] == false) {
			$query->set('post_type', array(self::DEVELOPERS, self::OWNERS, self::PROJECTS));
		}
		return $query;
	}
	
	/**
	 * Enqueues necessary javascript
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('gtd', $this->base_url . '/js/gtd.js', array('jquery'));
	}
	
	/**
	 * Disables comments on all posts created with Gravity Forms
	 * 
	 * @link http://www.gravityhelp.com/documentation/page/Gform_post_data
	 * @param unknown_type $post_data
	 * @param unknown_type $form
	 */
	public function modify_gravityform_post_data($post_data, $form) {
		$post_data['comment_status'] = 'closed';
		return $post_data;
	}
	
	/**
	 * Returns a WP_Query object with GTD post types
	 * 
	 * @param array $args WP_Query argument array. author=0 for current user, author=-1 for all.
	 * @return WP_Query
	 */
	public function get_gtd_posts($args = array()) {
		$defaults = array(
			'post_type'        => self::PROJECTS,
			'author'           => 0,
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'suppress_filters' => true,
		);
		$args = wp_parse_args($args, $defaults);
		
		if($args['author'] === -1)
			unset($args['author']);
		elseif($args['author'] === 0)
			$args['author'] = (int) get_current_user_id();
		
		return new WP_Query($args);
	}
	
	/**
	 * Get the user as a Developer or Owner post type
	 * 
	 * @param string $post_type GTD Post Type, default Developer
	 * @param null|int $user_id User ID or null for current user
	 * @return boolean|mixed false or Post
	 */
	public function get_user_as($post_type = self::DEVELOPERS, $user_id = null) {
		$user_id = ((int)$user_id <= 0) ? (int) get_current_user_id() : (int) $user_id;
		if($user_id <= 0)
			return false;
		
		if(!in_array($post_type, self::$ALL) || $post_type == self::PROJECTS)
			$post_type = self::DEVELOPERS;
		
		$query = $this->get_gtd_posts(array(
			'post_type'        => $post_type,
			'author'           => $user_id,
			'posts_per_page'   => 1
		));
		if($query->post_count > 0)
			return array_shift($query->posts);
		return false;
	}
	
	/**
	* Checks if a user is a specific GTD post type
	* 
	* @param int $user_id
	* @param string $post_type
	* @return boolean
	*/
	public function is_user_a($post_type = self::DEVELOPERS, $user_id = null) {
		return !($this->get_user_as($post_type, $user_id) === false);
	}

	/**
	 * Checks if the user is a GTD Developer
	 * 
	 * @param null|int $user_id
	 * @return boolean
	 */
	public function is_user_a_developer($user_id = null) {
		return $this->is_user_a(self::DEVELOPERS, $user_id);
	}
	
	/**
	* Checks if the user is a GTD Owner
	*
	* @param null|int $user_id
	* @return boolean
	*/
	public function is_user_an_owner($user_id = null) {
		return $this->is_user_a(self::OWNERS, $user_id);
	}
	
	/**
	 * Get the Owner of a Project post
	 * 
	 * Basically the Owner Post of the author of the Project Post
	 * @param object|int $project_post_or_id
	 * @return boolean|mixed
	 */
	public function get_owner_of_project($project_post_or_id) {
		if(!is_object($project_post_or_id)) {
			$project_post_or_id = (int) $project_post_or_id;
			$project_post_or_id = get_post($project_post_or_id);
		}
		if(!isset($project_post_or_id->post_author))
			return false;
		$query = $this->get_gtd_posts(array(
			'post_type'        => self::OWNERS,
			'author'           => $project_post_or_id->post_author,
			'posts_per_page'   => 1,
		));
		if($query->post_count > 0)
			return array_shift($query->posts);
		return false;
	}
	
	/**
	 * Get the Developers of a Project
	 * 
	 * @param object|int $project_post_or_id
	 * @return boolean|array
	 */
	public function get_developers_of_project($project_post_or_id) {
		$post_id = (is_object($project_post_or_id)) ? (int) $project_post_or_id->ID : (int) $project_post_or_id;
		if($post_id === 0)
			return false;
		$developers = p2p_type(self::DEVELOPERS_TO_PROJECTS)->get_connected($post_id);
		if($developers->post_count > 0)
			return $developers->posts;
		return false;
	}
	
	/**
	 * Get the Projects of a Developer
	 * 
	 * @param object|int $developer_post_or_id Developer post or post id
	 * @return false|array
	 */
	public function get_projects_of_developer($developer_post_or_id) {
		$post_id = (is_object($developer_post_or_id)) ? (int) $developer_post_or_id->ID : (int) $developer_post_or_id;
		if($post_id === 0)
			return false;
		$projects = p2p_type(self::DEVELOPERS_TO_PROJECTS)->get_connected($post_id);
		if($projects->post_count > 0)
			return $projects->posts;
		return false;
	}
	
	/**
	 * Get the Projects on and Owner
	 * 
	 * @param object|int $owner_post_or_id Owner post or post id
	 * @return false|array
	 */
	public function get_projects_of_owner($owner_post_or_id) {
		if(!is_object($owner_post_or_id)) {
			$owner_post_or_id = (int) $owner_post_or_id;
			$owner_post_or_id = get_post($owner_post_or_id);
		}
		if(!isset($owner_post_or_id->post_author))
			return false;
		$query = $this->get_gtd_posts(array(
			'post_type' => self::PROJECTS,
			'author' => $owner_post_or_id->post_author,
		));
		if($query->post_count > 0)
			return $query->posts;
		return false;
	}
	
	/**
	 * Get the projects either owned or developed by a user id
	 * 
	 * @param string $type
	 * @param int $user_id
	 * @return false|array
	 */
	public function get_projects_of_user($type = self::DEVELOPERS, $user_id = null) {
		if($post = $this->get_user_as($type, $user_id)) {
			if($type == self::DEVELOPERS)
				return $this->get_projects_of_developer($post);
			elseif($type == self::OWNERS)
				return $this->get_projects_of_owner($post);
		}
		return false;
	}
	
	/**
	 * Is the Developer a developer of the Project
	 * 
	 * @param object|int $project_post_or_id Project post or post id
	 * @param object|int $developer_post_or_id Developer post or post id
	 * @return boolean
	 */
	public function is_developer_of_project($project_post_or_id, $developer_post_or_id) {
		$developer_id = (is_object($developer_post_or_id)) ? (int) $developer_post_or_id->ID : (int) $developer_post_or_id;
		$project_id = (is_object($project_post_or_id)) ? (int) $project_post_or_id->ID : (int) $project_post_or_id;
		if($developers = $this->get_developers_of_project($project_id)) {
			foreach($developers as $developer)
				if($developer->ID === $developer_id)
					return true;
		}
		return false;
	}
	
	/**
	 * If the user a developer of the Project
	 * 
	 * @param object|int $project_post_or_id
	 * @param int $user_id
	 * @return boolean
	 */
	public function is_user_developer_of_project($project_post_or_id, $user_id = null) {
		$project_id = (is_object($project_post_or_id)) ? (int) $project_post_or_id->ID : (int) $project_post_or_id;
		$user_id = ((int)$user_id <= 0) ? (int) get_current_user_id() : (int) $user_id;
		if($developer_post = $this->get_user_as(self::DEVELOPERS, $user_id)) {
			return $this->is_developer_of_project($project_id, $developer_post->ID);
		}
		return false;
	}
	
	/**
	 * Add a Developer to a Project
	 * 
	 * @param object|int $project_post_or_id Project post or post id
	 * @param object|int $developer_post_or_id Developer post or post id
	 * @return boolean
	 */
	public function add_developer_to_project($project_post_or_id, $developer_post_or_id) {
		$developer_id = (is_object($developer_post_or_id)) ? (int) $developer_post_or_id->ID : (int) $developer_post_or_id;
		$project_id = (is_object($project_post_or_id)) ? (int) $project_post_or_id->ID : (int) $project_post_or_id;
		
		return !(p2p_type(self::DEVELOPERS_TO_PROJECTS)->connect($developer_id, $project_id) === false);
	}
	
	/**
	 * Add a user to a Project as a developer
	 * 
	 * @param object|int $project_post_or_id Project post or post id
	 * @param null|int $user_id user id
	 * @return boolean
	 */
	public function add_user_to_project($project_post_or_id, $user_id = null) {
		if($developer = $this->get_user_as(self::DEVELOPERS, $user_id))
			return $this->add_developer_to_project($project_post_or_id, $developer);
		return false;
	}
	
	/**
	 * Removes a Developer from a Project
	 * 
	 * @param object|int $project_post_or_id
	 * @param object|int $developer_post_or_id
	 * @return mixed|boolean
	 */
	public function remove_developer_from_project($project_post_or_id, $developer_post_or_id) {
		$developer_id = (is_object($developer_post_or_id)) ? (int) $developer_post_or_id->ID : (int) $developer_post_or_id;
		$project_id = (is_object($project_post_or_id)) ? (int) $project_post_or_id->ID : (int) $project_post_or_id;
		
		return p2p_type(self::DEVELOPERS_TO_PROJECTS)->disconnect($developer_id, $project_id);
	}
	
	/**
	 * Removes a user as a Developer from a Project
	 * 
	 * @param object|int $project_post_or_id
	 * @param null|int $user_id
	 * @return mixed|boolean
	 */
	public function remove_user_from_project($project_post_or_id, $user_id = null) {
		if($developer = $this->get_user_as(self::DEVELOPERS, $user_id))
			return $this->remove_developer_from_project($project_post_or_id, $developer);
		return false;
	}
	
	
	
	
	
	public function build_post_content($the_content) {
		global $post;
		if(in_array($post->post_type, self::$ALL)) {
			$the_content = '';
			switch($post->post_type) {
				case self::DEVELOPERS:
				case self::OWNERS:
					foreach(array(
						'email' => 'Email',
						'location' => 'Location',
						'ministry' => 'Ministry',
						'phone' => 'Phone',
						'website' => 'Website',
						) as $key => $label) {
							$value = get_post_meta($post->ID, "gtd_$key", true);
							//$value = preg_replace('/./', '-', $value);
							if($value)
								$the_content .= "{$label}: {$value}<br />\n";
					}
					break;
			}
		}
		return $the_content;
	}

	public function get_thumb($width, $height, $class, $title) {
		global $post;
		$thumbnail = get_thumbnail($width,$height,$class,$title,$title);
		if($thumbnail['thumb'] == '' && $post && in_array($post->post_type, self::$ALL)) {
			switch($post->post_type) {
				case self::DEVELOPERS:
					$thumbnail['fullpath'] = $this->base_url . '/images/developer.png';
					break;
				case self::OWNERS:
					$thumbnail['fullpath'] = $this->base_url . '/images/owner.png';
					break;
				case self::PROJECTS:
					$thumbnail['fullpath'] = $this->base_url . '/images/project.png';
					break;
			}
			$thumbnail['thumb'] = $thumbnail['fullpath'];
		}
		$thumbnail['use_timthumb'] = false;
		return $thumbnail;
	}
}

GlobalTechDev::singleton();