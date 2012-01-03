<?php
	$thumb = '';
	$width = 38;
	$height = 38;
	$classtext = 'smallthumb';
	$titletext = get_the_title();
	
	$thumbnail = GlobalTechDev::singleton()->get_thumb($width, $height);
	$thumb = $thumbnail["thumb"];
?>
<li class="clearfix">
	<?php if ($thumb <> '') { ?>
		<a href="<?php the_permalink(); ?>">
			<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
		</a>
	<?php } ?>
	<span class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
	<span class="postinfo">
	<?php
		switch($post->post_type) {
			case GlobalTechDev::DEVELOPERS:
				echo 'Developer';
				break;
			case GlobalTechDev::OWNERS:
				echo 'Project Owner';
				break;
			case GlobalTechDev::PROJECTS:
				echo 'Project';
				break;
		}
	?>
	</span>
</li>