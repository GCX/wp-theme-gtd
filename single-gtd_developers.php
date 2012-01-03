<?php
if(array_key_exists('gtd_action', $_REQUEST) && $_REQUEST['gtd_action'] == 'edit') :
	gravity_form_enqueue_scripts(4, false);
get_header();
the_post();
?>
<div id="content" class="clearfix">
	<div id="left-area">
		<div id="post" class="post">
			<div class="post-content clearfix">
				<div class="post-text fullwidth">
					<h1 class="title">Edit Developer</h1>
										
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
			
<?php get_footer();


else :

get_header(); ?>
<div id="content" class="clearfix">
	<div id="left-area">
	<?php if ( have_posts() ) : the_post(); ?>
		<div id="post" class="post">
			<div class="post-content clearfix">
				<div class="info-panel">
					<?php get_template_part('includes/infopanel-developer'); ?>
				</div> <!-- end .info-panel -->
				
				<div class="post-text">
					<h1 class="title"><?php the_title(); ?></h1>
					<p class="post-meta">Developer</p>
					<div class="hr"></div>
					
					<?php the_content(); ?>
					
					<?php  GlobalTechDev::singleton()->get_edit_link(); ?>
					
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->
		</div> <!-- #post -->
	<?php endif; ?>
	</div> <!-- #left-area -->
	
	<div id="sidebar">
		<?php dynamic_sidebar('Developer'); ?>
	</div> <!-- end #sidebar -->
</div> <!-- #content -->
			
<div id="content-bottom-bg"></div>
			
<?php get_footer();
endif;
?>

