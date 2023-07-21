<?php 
	// Mínimo de seguridad
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	};
?>			

</main>
		
		<!--Footer-->
		
		<footer>
			<div class="container">
				<?php wp_nav_menu( array('menu' => 'Footer', 'container' => false, )); ?>
				
				
				
				<p class="copyright">&copy; <?php echo date("Y");?></p>
			</div>
		</footer>
		
		<!--External Scripts-->
		
		<script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>

		
		<?php wp_footer(); ?>
	
	</body>
</html>
