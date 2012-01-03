<?php

class GlobalTechDev_RecentPopularRandom_Widget extends WP_Widget {
	/**
	* Constructor
	*/
	public function __construct() {
		$config = array(
			'classname'   => 'gtd-rpr',
			'description' => 'Tabbed Recent, Popular, Random posts',
		);
		parent::__construct('globaltechdev_recentpopularrandom_widget', 'GTD: Recent, Popular, Random', $config);
	}
	
	public function widget($args, $instance) {
		global $post;
		$post_backup = $post;
		?>
		<div id="tabbed" class="widget">
			<ul id="tabbed-area" class="clearfix">
				<li><a href="#recent-tab">Recent</a></li>
				<li><a href="#popular-tab">Popular</a></li>
				<li><a href="#random-tab">Random</a></li>
			</ul>
			<div id="recent-tab" class="tab">
				<ul>
					<?php
						$query = new WP_Query(array(
							'suppress_filters' => true,
							'posts_per_page'   => 5,
							'post_type'        => GlobalTechDev::$ALL,
							'post_status'      => 'publish',
						));
						if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
							get_template_part('includes/entry-list');
						endwhile; endif; wp_reset_postdata();
					?>
				</ul>	
			</div>
			<div id="popular-tab" class="tab">
				<ul>
					<?php
						$query = new WP_Query(array(
							'suppress_filters' => true,
							'posts_per_page'   => 5,
							'post_type'        => GlobalTechDev::$ALL,
							'post_status'      => 'publish',
							'meta_key'         => 'gtd_view_count',
							'orderby'          => 'meta_value_num',
							'order'            => 'DESC',
						));
						if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
							get_template_part('includes/entry-list');
						endwhile; endif; wp_reset_postdata();
					?>
				</ul>	
			</div>
			<div id="random-tab" class="tab">
				<ul>
					<?php
						$query = new WP_Query(array(
							'suppress_filters' => true,
							'posts_per_page'   => 5,
							'post_type'        => GlobalTechDev::$ALL,
							'post_status'      => 'publish',
							'orderby'          => 'rand',
						));
						if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
							get_template_part('includes/entry-list');
						endwhile; endif; wp_reset_postdata();
					?>
				</ul>	
			</div>
		</div>
		<?php
		$post = $post_backup;
	}
	
	public static function init_widget() {
		register_widget('GlobalTechDev_RecentPopularRandom_Widget');
	}
}

add_action('widgets_init', array('GlobalTechDev_RecentPopularRandom_Widget', 'init_widget'));