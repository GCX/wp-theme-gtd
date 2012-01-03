<?php
	global $post;

	$biglayout = ( (bool) get_post_meta($post->ID,'et_bigpost',true) ) ? true : false;

	$thumb = '';
	$width = $biglayout ? 466 : 222;
	$height = 180;
	$classtext = '';
	$titletext = get_the_title();
	
	$thumbnail = GlobalTechDev::singleton()->get_thumb($width, $height);
	$thumb = $thumbnail["thumb"];
?>
<div class="entry <?php if ($biglayout) echo('big'); else echo('small');?>">
	<div class="thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
			<span class="overlay"></span>
		</a>
	</div> <!-- end .thumbnail -->	
	<h2 class="title"><a href="<?php the_permalink(); ?>"><?php if ($biglayout) truncate_title(50); else truncate_title(20); ?></a></h2>
	<p class="postinfo">Developer</p>
	<div class="entry-content">
		<div class="bottom-bg">
			<div class="excerpt">
				<p><?php if ($biglayout) truncate_post(650); else truncate_post(295); ?></p>
				<div class="textright">
					<a href="<?php the_permalink(); ?>" class="readmore"><span>&raquo;</span>&raquo;</a>
				</div>
			</div><!-- end .excerpt -->
		</div><!-- end .bottom-bg -->
	</div><!-- end .entry-content -->
</div><!-- end .entry -->