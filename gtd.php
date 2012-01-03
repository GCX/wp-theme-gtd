<?php

require_once get_stylesheet_directory() . '/includes/widgets/add-buttons-widget.php';
require_once get_stylesheet_directory() . '/includes/widgets/tag-cloud-widget.php';

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
	 * Tags custom taxonomy
	 * We dont use post_tag taxonomy in order to keep our tags separate.
	 * @var string
	 */
	const TAGS = 'gtd_tags';
	
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
		add_filter('gform_pre_render', array(&$this, 'populate_gravity_form'), 10, 1);
		add_filter('gform_field_content', array(&$this, 'add_image_to_form'), 10, 5);
		add_action('gform_pre_submission', array(&$this, 'form_pre_submission'), 10, 1);
		add_action('gform_confirmation', array(&$this, 'redirect_form_submission'), 10, 4);
		add_action('gform_field_validation', array(&$this, 'delete_image_from_post'), 10, 4);

		add_filter('next_post_rel_link', '__return_false'); //do not add <link rel="next"/> to <head/>
		add_filter('previous_post_rel_link', '__return_false'); //do not add <link rel="prev"/> to <head/>
		remove_action('wp_head', 'rel_canonical');
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
		
		//Register the Tags (gtd_tags) taxonomy and asign it to gtd_projects post type
		register_taxonomy(
			self::TAGS,
			array(0 => self::PROJECTS),
			array(
				'public'         => true,
				'hierarchical'   => false,
				'label'          => 'GTD Tags',
				'show_ui'        => true,
				'show_tagcloud'  => true,
				'query_var'      => true,
				'rewrite'        => array(
					'slug'       => 'tag',
					'with_front' => false,
				),
				'singular_label' => 'GTD Tag'
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
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('gtd', $this->base_url . '/js/gtd.js', array('jquery', 'jquery-ui-tabs'));
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
	
	/**
	 * Remove featured image from GTD post
	 * 
	 * Uses the gravity forms validation hook to determine if the image has been removed from
	 * a post and if so, it removes it.
	 * @param unknown_type $result
	 * @param unknown_type $value
	 * @param unknown_type $form
	 * @param unknown_type $field
	 * @return unknown
	 */
	public function delete_image_from_post($result, $value, $form, $field) {
		if(array_key_exists('gtd_delete_image', $_POST) && $_POST['gtd_delete_image'] == '1') {
			foreach($form['fields'] as $field) {
				if($field['inputName'] == 'post_ID') {
					$input_id = 'input_' . $field['id'];
					if(array_key_exists($input_id, $_POST) && (int) $_POST[$input_id] > 0) {
						$post_ID = (int) $_POST[$input_id];
						$query = new WP_Query(array(
							'post_type'        => 'attachment',
							'post_status'      => 'any',
							'post_parent'      => $post_ID,
							'suppress_filters' => true,
						));
						foreach($query->posts as $attachment) {
							wp_delete_attachment($attachment->ID, true);
						}
					}
				}
			}
			unset($_POST['gtd_delete_image']);
		}
		return $result;
	}
	
	
	public function populate_gravity_form($form) {
		global $post;
		if(array_key_exists('gtd_action', $_REQUEST) && $_REQUEST['gtd_action'] == 'edit' && is_single() && in_array($post->post_type, self::$ALL)) {

			if(!empty($_POST))
				foreach($form['fields'] as $field)
					if($field['failed_validation'] === true)
						return $form;

			$user = new WP_User($post->post_author);
			foreach($form['fields'] as &$field) {
				switch($field['inputName']) {
					case 'gtd_name':
					case 'gtd_owner':
						$field['defaultValue'] = $user->first_name . ' ' . $user->last_name;
						break;
					case 'gtd_project':
						$field['defaultValue'] = $post->post_title;
						break;
					case 'gtd_email':
						$field['defaultValue'] = $user->user_email;
						break;
					case 'gtd_phone':
					case 'gtd_location':
					case 'gtd_website':
					case 'gtd_availability':
					case 'gtd_ministry':
					case 'gtd_purpose':
					case 'gtd_description':
					case 'gtd_constraints':
					case 'gtd_cost':
					case 'gtd_status':
					case 'gtd_management_url':
						$meta = get_post_meta($post->ID, $field['inputName'], true);
						$field['defaultValue'] = $meta;
						break;
					case 'gtd_skills':
						$skills = get_the_terms($post->ID, self::SKILLS);
						$field['defaultValue'] = ($skills) ? array_keys($skills) : array();
						break;
					case 'gtd_tags':
						$tags = get_the_terms($post->ID, self::TAGS);
						$terms = array();
						if($tags)
							foreach($tags as $tag)
								$terms[] = $tag->name;
						$field['defaultValue'] = implode(', ', $terms);
						break;
					case 'post_ID':
						$field['defaultValue'] = "{$post->ID}";
						break;
				}
			}
		}
		return $form;
	}
	
	public function add_image_to_form($content, $field, $value, $lead_id, $form_id) {
		global $post;
		
		if($field['type'] == 'post_image' && array_key_exists('gtd_action', $_REQUEST) && $_REQUEST['gtd_action'] == 'edit' && is_single() && in_array($post->post_type, self::$ALL)) {
			$thumb = $this->get_thumb(186, 186);
			$start = '<label class="gfield_label" for="input_%1$s_%2$s">%3$s</label><div class="ginput_complex ginput_container"><span class="ginput_full">';
			$input = '<input name="input_%2$s" id="input_%1$s_%2$s" type="file" value="" class="medium %4$s" />';
			$end = '</span></div>';
			$args = array(
				"{$form_id}",
				"{$field['id']}",
				esc_html($field['label']),
				'',
			);
			
			$content = vsprintf($start, $args);
			if($thumb['gtd_default'] === false) {
				$args[3] = 'gform_hidden';
				$preview = '<span class="ginput_preview"><div>%1$s</div><a class="gtd-remove-image" href="#">remove</a></span>';
				$content .= sprintf($preview, print_thumbnail($thumb['thumb'], false, '', 186, 186, 'smallthumb', false));
			}
			$content .= vsprintf($input, $args);
			$content .= vsprintf($end, $args);
		}
		return $content;
	}
	
	public function form_pre_submission($form) {
		if(array_key_exists('gtd_action', $_REQUEST) && $_REQUEST['gtd_action'] == 'edit') {
			$values = array();
			foreach($form['fields'] as &$field) {
				switch($field['inputName']) {
					case 'gtd_name':
					case 'gtd_email':
					case 'gtd_phone':
					case 'gtd_location':
					case 'gtd_website':
					case 'gtd_availability':
					case 'gtd_ministry':
					case 'gtd_skills':
					case 'post_ID':
					case 'gtd_project':
					case 'gtd_purpose':
					case 'gtd_description':
					case 'gtd_constraints':
					case 'gtd_cost':
					case 'gtd_status':
					case 'gtd_management_url':
					case 'gtd_tags':
						$values[$field['inputName']] = $_POST['input_' . $field['id']];
						break;
				}
			}
			if(array_key_exists('post_ID', $values) && ((int)$values['post_ID'] > 0)) {
				$gtd_post = get_post($values['post_ID']);
				$author = new WP_User($gtd_post->post_author);
				
				//Determine if the Post title has changed and update it
				$title = $gtd_post->post_title;
				if($gtd_post->post_type == self::DEVELOPERS || $gtd_post->post_type == self::OWNERS)
					$title = $author->first_name . ' ' . $author->last_name;
				if($gtd_post->post_type == self::PROJECTS)
					$title = $values['gtd_project'];
				if($gtd_post->post_title != $title) {
					wp_update_post(array(
						'ID' => (int) $gtd_post->ID,
						'post_title' => $title,
						'post_name' => sanitize_title($title),
					));
					$gtd_post = get_post($gtd_post->ID);
				}
				
				//Update Profile image if it has changed
				$image_field = array_pop(GFCommon::get_fields_by_type($form, array('post_image')));
				if($image_field) {
					$image_input = 'input_' . $image_field['id'];
					if(array_key_exists($image_input, $_FILES) && array_key_exists('name', $_FILES[$image_input])) {
						$url = RGFormsModel::upload_file($form['id'], $_FILES[$image_input]);
						if(strpos($url, 'FAILED') === false)
							$this->add_image_to_post($url, $gtd_post);
					}
				}
				
				foreach($values as $name => $value) {
					if(in_array($name, array('post_ID', 'gtd_name', 'gtd_project', 'gtd_owner')))
						continue;
					
					switch($name) {
						case 'gtd_skills':
							$value = (is_array($value)) ? array_map('intval', $value) : array();
							wp_set_object_terms($gtd_post->ID, $value, self::SKILLS, false);
							break;

						case 'gtd_tags':
							wp_set_post_terms($gtd_post->ID, $value, self::TAGS, false);
							break;

						default:
							if($value)
								update_post_meta($gtd_post->ID, $name, $value);
							else
								delete_post_meta($gtd_post->ID, $name);
							break;
					}
				}
				wp_redirect(get_permalink($gtd_post->ID));
				exit();
			}
		}
	}
	
	public function add_image_to_post($url, $post) {
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		if (!(($uploads = wp_upload_dir($post->post_date)) && $uploads['error'] === false))
			return false;
		
		$file_name = basename($url);
		$filename = wp_unique_filename($uploads['path'], $file_name);
		
		$file = $uploads['path'] . "/$filename";
		$uploaddir = wp_upload_dir();
		$path = str_replace($uploaddir["baseurl"], $uploaddir["basedir"], $url);
		
        if(!copy($path, $file))
            return false;
        		
		// Set correct file permissions
		$stat = stat( dirname( $file ));
		$perms = $stat['mode'] & 0000666;
		@ chmod( $file, $perms );
		
		// Compute the URL
		$url = $uploads['url'] . "/$filename";
		
		if ( is_multisite() )
			delete_transient( 'dirsize_cache' );
		
		$type = wp_check_filetype($file);
		$name = basename($url, $type['ext']);

		$image_id = wp_insert_attachment(array(
			'post_mime_type' => $type['type'],
			'guid' => $url,
			'post_parent' => $post->ID,
			'post_title' => $name,
			'post_content' => '',
		), $file, $post->ID);
		
		if(!is_wp_error($image_id)) {
			wp_update_attachment_metadata($image_id, wp_generate_attachment_metadata($image_id, $file));
			set_post_thumbnail($post->ID, $image_id);
		}		
	}
	
	public function redirect_form_submission($confirmation, $form, $lead, $ajax) {
		if(array_key_exists('post_id', $lead) && (int) $lead['post_id'] > 0) {
			$confirmation = array(
				'redirect' => get_permalink((int) $lead['post_id']),
			);
		}
		return $confirmation;
	}
	
	public $developer_attrs = array(
		'gtd_email' => 'Email',
		'gtd_phone' => 'Phone',
		'gtd_ministry' => 'Minsitry',
		'gtd_location' => 'Location',
		'gtd_website' => 'Website',
		'gtd_availability' => 'Availability',
	);
	
	public $owner_attrs = array(
		'gtd_email' => 'Email',
		'gtd_phone' => 'Phone',
		'gtd_ministry' => 'Minsitry',
		'gtd_location' => 'Location',
		'gtd_website' => 'Website',
	);
	
	public $project_attrs = array(
		'gtd_purpose' => 'Email',
		'gtd_description' => 'Location',
		'gtd_constraints' => 'Minsitry',
		'gtd_cost' => 'Phone',
		'gtd_status' => 'Website',
		'gtd_management_url' => 'Website',
	);
	
	public $attrs = array(
		'gtd_email' => 'Email',
		'gtd_phone' => 'Phone',
		'gtd_ministry' => 'Minsitry',
		'gtd_location' => 'Location',
		'gtd_website' => 'Website',
		'gtd_availability' => 'Availability',
		'gtd_purpose' => 'Email',
		'gtd_description' => 'Location',
		'gtd_constraints' => 'Minsitry',
		'gtd_cost' => 'Phone',
		'gtd_status' => 'Website',
		'gtd_management_url' => 'Website',
	);
	
	public function build_post_content($the_content) {
		global $post;
		if(in_array($post->post_type, self::$ALL)) {
			$the_content = '';
			foreach($this->attrs as $key => $label) {
				$value = get_post_meta($post->ID, "$key", true);
				if($value)
					$the_content .= "{$label}: {$value}<br />\n";
			}
/*
			switch($post->post_type) {
				case self::DEVELOPERS:
				case self::OWNERS:
				case self::PROJECTS:
					break;
			}
*/
			//Increase view count for single items
			if(is_single()) {
				$view_count = (int) get_post_meta($post->ID, 'gtd_view_count', true);
				$view_count++;
				update_post_meta($post->ID, 'gtd_view_count', (int) $view_count);
			}
		}
		return $the_content;
	}

	/**
	 * Generate thumbnail link
	 * 
	 * Overrides TheStyle thumbnail support to return a thumbnail attached to the current post,
	 * or a default image based on GTD custom post type.
	 * @param int $width Desired width
	 * @param int $height Desired height
	 * @return array Array of thumbnail path, url and if it is a gtd_default image
	 */
	public function get_thumb($width = 100, $height = 100) {
		global $post;
		$thumbnail = get_thumbnail($width,$height);
		$thumbnail['gtd_default'] = false;
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
			$thumbnail['gtd_default'] = true;
		}
		$thumbnail['use_timthumb'] = false;
		return $thumbnail;
	}
	
	/**
	 * Generate an edit link for a GTD post type
	 * 
	 * @param int $id Post ID, 0 for current post (if in the loop).
	 * @param string $label Label for the link
	 * @param array $attrs Additional attributes for the <a> tag
	 * @param boolean $echo Echo the link or just return it
	 * @return string The link. This is returned regardless of what $echo is set to.
	 */
	public function get_edit_link($id = 0, $label = 'Edit your Profile', $attrs = array(), $echo = true) {
		$output = '';
		if ( $post = &get_post( $id ) ) {
			if($post_type_object = get_post_type_object( $post->post_type )) {
				if(current_user_can( $post_type_object->cap->edit_post, $post->ID ) || get_current_user_id() == $post->post_author) {
					$attributes = '';
					foreach($attrs as $name => $value)
						$attributes .= sprintf('%1$s="%2$s" ', $name, esc_attr($value));
					$output = sprintf(
						'<a href="%2$s" %3$s>%1$s</a>',
						esc_html($label),
						get_permalink($post->ID) . '?gtd_action=edit',
						$attributes
					);
				}
			}
		}
		if($echo)
			echo $output;
		return $output;
	}
}

GlobalTechDev::singleton();