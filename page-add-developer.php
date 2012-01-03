<?php
$gtd = GlobalTechDev::singleton();
if($developer = $gtd->get_user_as(GlobalTechDev::DEVELOPERS)) {
	//User already has a developer post, redirect to it.
	wp_redirect(get_permalink($developer->ID));
	exit();
}

gravity_form_enqueue_scripts(4, false);
get_header();
?>
<div id="content" class="clearfix">
	<div id="left-area">
		<div id="post" class="post">
			<div class="post-content clearfix">
				<div class="post-text fullwidth">
					<h1 class="title">Add Developer</h1>
										
					<div class="hr"></div>
					<?php
						gravity_form(4, false, false);
					?>
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->
		</div> <!-- #post -->
	</div> <!-- #left-area -->
	<?php get_sidebar('Add Developer'); ?>
</div> <!-- #content -->
			
<div id="content-bottom-bg"></div>
			
<?php get_footer(); ?>