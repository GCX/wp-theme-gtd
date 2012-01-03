<?php
if(array_key_exists('join_project', $_REQUEST) || array_key_exists('leave_project', $_REQUEST)) {
	global $logger;
	$project_id = array_key_exists('join_project', $_REQUEST) ? (int) $_REQUEST['join_project'] : (int) $_REQUEST['leave_project'];
	$logger->debug('Project ID: ' . $project_id);
	$action = array_key_exists('join_project', $_REQUEST) ? 'add_user_to_project' : 'remove_user_from_project';
	
	GlobalTechDev::singleton()->$action($project_id);
	wp_redirect(get_permalink($project_id));
	exit();
}

if(array_key_exists('gtd_action', $_REQUEST) && $_REQUEST['gtd_action'] == 'edit') :
	gravity_form_enqueue_scripts(6, false);
	get_header();
	the_post();
?>
<div id="content" class="clearfix">
	<div id="left-area">
		<div id="post" class="post">
			<div class="post-content clearfix">
				<div class="post-text fullwidth">
					<h1 class="title">Edit Project</h1>
										
					<div class="hr"></div>
					<?php
						gravity_form(6, false, false);
					?>
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->			
		</div> <!-- #post -->
	</div> <!-- #left-area -->
	<?php get_sidebar('Add Project'); ?>
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
					<?php get_template_part('includes/infopanel-project'); ?>
				</div> <!-- end .info-panel -->
				
				<div class="post-text">
					<h1 class="title"><?php the_title(); ?></h1>
					<p class="post-meta">Project</p>
					<div class="hr"></div>
					
					<?php the_content(); ?>
					
					<?php  GlobalTechDev::singleton()->get_edit_link(0, 'Edit the Project'); ?>
					
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->
		</div> <!-- #post -->
	<?php endif; ?>
	</div> <!-- #left-area -->
	
	<div id="sidebar">
		<?php dynamic_sidebar('Project'); ?>
	</div> <!-- end #sidebar -->
</div> <!-- #content -->
			
<div id="content-bottom-bg"></div>
			
<?php get_footer();
endif;
?>

