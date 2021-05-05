<?php 
	// Mínimo de seguridad
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

get_header(); ?>

	<article>
		<div class="container">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<h1><?php the_title();?></h1>
				<?php the_content();?>
			<?php endwhile; ?>
		</div>
	</article>

<?php get_footer(); ?>
