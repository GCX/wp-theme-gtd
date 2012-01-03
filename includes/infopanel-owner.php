<?php $thumb = '';
	$width = 186;
	$height = 186;
	$classtext = 'smallthumb';
	$titletext = get_the_title();	
	$thumbnail = GlobalTechDev::singleton()->get_thumb($width, $height);
	$thumb = $thumbnail['thumb']; ?>
	<div class="single-thumb">
		<?php print_thumbnail($thumb, $thumbnail['use_timthumb'], $titletext, $width, $height); ?>
		<span class="overlay"></span>
	</div> <!-- end .single-thumb -->

<div class="clear"></div>

<h3 class="infotitle">Current Projects</h3>
<div class="tags clearfix">
	<?php
		global $post;
		$query = new WP_Query(array(
			'post_type'        => GlobalTechDev::PROJECTS,
			'author'           => $post->post_author,
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'suppress_filters' => true,
		));
		echo '<ul>';
		if(!empty($query->posts)) {
			foreach($query->posts as $p) {
				echo sprintf('<li><a href="%1$s">%2$s</a></li>', get_permalink($p->ID), get_the_title($p->ID));
			}
		}
		else {
			echo '<li>No Current Projects</li>';
		}
		echo '</ul>';
	?>
</div>