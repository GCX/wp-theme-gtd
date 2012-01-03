<?php $thumb = '';
	$width = 186;
	$height = 186;
	$classtext = 'smallthumb';
	$titletext = get_the_title();
	$thumbnail = GlobalTechDev::singleton()->get_thumb($width, $height);
	$thumb = $thumbnail['thumb']; ?>
	<div class="single-thumb">
		<?php print_thumbnail($thumb, $thumbnail['use_timthumb'], $titletext, $width, $height, $classtext); ?>
		<span class="overlay"></span>
	</div> <!-- end .single-thumb -->

<div class="clear"></div>

<h3 class="infotitle">Current Projects</h3>
<div class="tags clearfix">
	<?php
		$dev_projects = p2p_type('developers_to_projects')->get_connected($post->ID);
		if($dev_projects->post_count > 0) {
			p2p_list_posts($dev_projects);
		}
		else {
			echo '<ul><li>No Current Projects</li></ul>';
		}
	?>
</div>

<h3 class="infotitle">Skills (to pay the bills)</h3>
<div class="tags clearfix">
	<?php the_terms($post->ID, GlobalTechDev::SKILLS, '<ul><li>', '</li><li>', '</li></ul>')?>
</div>
