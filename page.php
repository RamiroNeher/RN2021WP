<?php 
	// Mínimo de seguridad
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	};

	get_header(); 
?>

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
		<!--Page Hero-->
		
		
		
		<!--Page Content-->
		
		<article class="page-content">
			<div class="container">
				<?php the_content();?>
			</div>
		</article>
		
	<?php endwhile; ?>

<?php get_footer(); ?>
