<?php
$gtd = GlobalTechDev::singleton();
if($owner = $gtd->get_user_as(GlobalTechDev::OWNERS)) {
	//User already has an owner post, redirect to it.
	wp_redirect(get_permalink($owner->ID));
	exit();
}

gravity_form_enqueue_scripts(5, false);
get_header();
?>
<div id="content" class="clearfix">
	<div id="left-area">
		<div id="post" class="post">
			<div class="post-content clearfix">
				<div class="post-text fullwidth">
					<h1 class="title">Add Owner</h1>
										
					<div class="hr"></div>
					<?php
						gravity_form(5, false, false);
					?>
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->			
		</div> <!-- #post -->
	</div> <!-- #left-area -->
	<?php get_sidebar('Add Owner'); ?>
</div> <!-- #content -->
			
<div id="content-bottom-bg"></div>
			
<?php get_footer(); ?>