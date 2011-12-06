<?php get_header(); ?>
<div id="content" class="clearfix">
	<div id="left-area">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
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
					
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->
		</div> <!-- #post -->
	<?php endwhile; endif; ?>
	</div> <!-- #left-area -->
	
	<div id="sidebar">
		<?php dynamic_sidebar('Developer'); ?>
	</div> <!-- end #sidebar -->
</div> <!-- #content -->
			
<div id="content-bottom-bg"></div>
			
<?php get_footer(); ?>
