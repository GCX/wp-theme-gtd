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
<?php global $post; ?>

<h3 class="infotitle">Project Owner</h3>
<div class="tags clearfix">
	<ul>
		<li>
			<?php
				if($owner_post = GlobalTechDev::singleton()->get_owner_of_project($post))
					echo sprintf('<a href="%1$s">%2$s</a></li>', get_permalink($owner_post->ID), get_the_title($owner_post->ID));
			?>
		</li>
	</ul>
</div>

<h3 class="infotitle">Developers</h3>
<div class="tags clearfix">
<?php
	$gtd = GlobalTechDev::singleton();
	if($developers = $gtd->get_developers_of_project($post->ID)) {
		p2p_list_posts($developers);
	}
	else {
		echo '<ul><li>No Developers! Become the first.</li></ul>';
	}
?>
</div>

<?php if($gtd->is_user_a_developer()) { ?>
	<h3 class="infotitle">Project Actions</h3>
	<div class="tags clearfix">
		<ul>
		<?php	
			if(!$gtd->is_user_developer_of_project($post->ID)) {
				echo '<li><a href="' . get_permalink($post->ID) . '?join_project='.$post->ID.'">Join this Project</a></li>';
			}
			else {
				echo '<li><a href="' . get_permalink($post->ID) . '?leave_project='.$post->ID.'">Leave this Project</a></li>';
			}
		?>
		</ul>
	</div>
<?php } ?>