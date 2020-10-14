<?php
/**
 * The template for employee
 */
get_header();
?>
	<div id="employee" class="container employee-container">
		<div class="col-sm-8">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part('template-parts/employee', get_post_type());

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
		</div>
		<div class="sidebar col-sm-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
<?php

get_footer();
