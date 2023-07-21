<?php
/**
*	Seguridad
**/
// Mínimo de seguridad
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Más seguridad
global $user_ID; if($user_ID) {
	if(!current_user_can('administrator')) {
		if (strlen($_SERVER['REQUEST_URI']) > 255 ||
		stripos($_SERVER['REQUEST_URI'], "eval(") ||
		stripos($_SERVER['REQUEST_URI'], "CONCAT") ||
		stripos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
		stripos($_SERVER['REQUEST_URI'], "base64")) {
		@header("HTTP/1.1 414 Request-URI Too Long");
			@header("Status: 414 Request-URI Too Long");
			@header("Connection: Close");
			@exit;
		}
	}
}

// Oculta la versión de WP
remove_action('wp_head', 'wp_generator');

/**
*	Evita que WordPress se actualice de manera automática
**/
define( 'WP_AUTO_UPDATE_CORE', false );

/**
*	Evita que los plugins se actualicen de manera automática
**/
add_filter( 'auto_update_plugin', '__return_false');

/**
* Controla el HeartBeat
**/
 function modificar_heartbeat( $settings ) {
	  $settings['interval'] = 300; // En el caso se utilice más de un usuario lo recomendable es un valor entre los 15 a 60 segundos
	  return $settings;
 }
 add_filter( 'heartbeat_settings', 'modificar_heartbeat' );

/**
*	Desactiva completamente HeardBeats
*	Solamente recomendado para sitios sencillos, que tengan un solo usuario y pocos o ningún plugin
**
add_action( 'init', 'stop_heartbeat', 1 );
function stop_heartbeat() {
	wp_deregister_script('heartbeat');
} */

/**
*	Include Jquery properly
**/
if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);

function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", false, null, true );
   wp_enqueue_script('jquery');
}

/**
*	Anula Gutenberg
**/
// add_filter('use_block_editor_for_post_type', '__return_false', 100);

/**
*	Saca las entradas default
**/
// Remove side menu 
add_action( 'admin_menu', 'remove_default_post_type' ); 
function remove_default_post_type() { 
    remove_menu_page( 'edit.php' ); 
} 
// Remove +New post in top Admin Menu Bar 
add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 ); 
function remove_default_post_type_menu_bar( $wp_admin_bar ) { 
    $wp_admin_bar->remove_node( 'new-post' ); 
} 
// Remove Quick Draft Dashboard Widget 
add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 ); 
function remove_draft_widget(){ 
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); 
}

/**
*	Saca los comentarios
**/
add_action('admin_init', function () { 
    // Redirect any user trying to access comments page 
    global $pagenow; 
     
    if ($pagenow === 'edit-comments.php') { 
        wp_redirect(admin_url()); 
        exit; 
    } 
    // Remove comments metabox from dashboard 
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); 
    // Disable support for comments and trackbacks in post types 
    foreach (get_post_types() as $post_type) { 
        if (post_type_supports($post_type, 'comments')) { 
            remove_post_type_support($post_type, 'comments'); 
            remove_post_type_support($post_type, 'trackbacks'); 
        } 
    } 
}); 
// Close comments on the front-end 
add_filter('comments_open', '__return_false', 20, 2); 
add_filter('pings_open', '__return_false', 20, 2); 
// Hide existing comments 
add_filter('comments_array', '__return_empty_array', 10, 2); 
// Remove comments page in menu 
add_action('admin_menu', function () { 
    remove_menu_page('edit-comments.php'); 
}); 
// Remove comments links from admin bar 
add_action('init', function () { 
    if (is_admin_bar_showing()) { 
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60); 
    } 
});

/**
*	Saca los emojis
**/
remove_action('wp_head', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
*	Desactiva el Search WP
**/
function fb_filter_query( $query, $error = true ) { 
if ( is_search() ) { 
$query->is_search = false; 
$query->query_vars[s] = false; 
$query->query[s] = false; 
// to error 
if ( $error == true ) 
$query->is_404 = true; 
} 
} 
add_action( 'parse_query', 'fb_filter_query' ); 
add_filter( 'get_search_form', create_function( '$a', "return null;" ) );

/**
 * Saca las notificaciones de temas y plugins (en teoría)
 * Evaluar ponerlo en un plugin de backend
 */
function hide_update_notice_to_all_but_admin_users()
{
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}

add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );

/** 
* Agrega bootstrap css y js 
*/ 
function bootstrap5_enqueue_scripts() { 
    // jQuery is stated as a dependancy of bootstrap-js - it will be loaded by WordPress before the BS scripts 
    wp_enqueue_script( 'bs-5-bundle-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', false, true); // all the bootstrap javascript goodness 
    // wp_enqueue_script( 'popper-js', 'cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array('jquery'), true); // all the bootstrap javascript goodness 
    // wp_enqueue_script( 'bootstrap-js', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), true); // all the bootstrap javascript goodness 
} 
add_action('wp_enqueue_scripts', 'bootstrap5_enqueue_scripts'); 

function bootstrap5_enqueue_styles() {
	
	wp_enqueue_style( 'bs-5-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css' ); 
	// this will add the stylesheet from it's default theme location if your theme doesn't already 
	//wp_enqueue_style( 'my-style', get_template_directory_uri() . '/style.css'); 

} 

add_action('wp_enqueue_scripts', 'bootstrap5_enqueue_styles');

/**
*	Enqueue Scrips and styles
**/
function wpdocs_scripts_method() {
	
	// CSS

   wp_register_style( 'primary-stylesheet', get_template_directory_uri() . '/style.css');
   wp_enqueue_style( 'primary-stylesheet' ); 
   
   // JS

   wp_register_script( 'custom-scripts', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ), '', true  );
   wp_enqueue_script('custom-scripts');
}
add_action( 'wp_enqueue_scripts', 'wpdocs_scripts_method' );

/**
* Remove Admin bar
**/
add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {
	 show_admin_bar(false);
}

/**
* Agrega soporte de menú
**/
add_theme_support( 'menus' );

/**
*	Sidebars
**/
/*
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
		'name' => 'Blog',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
 } 
*/

/**
*	Cambia el largo de los expert por defecto
**/
/*
function wpe_excerptlength_index($length) {
    return 160;
}
function wpe_excerptmore($more) {
    return '...<a href="'. get_permalink().'">Read More ></a>';
}

function wpe_excerpt($length_callback='', $more_callback='') {
    global $post;
    if(function_exists($length_callback)){
        add_filter('excerpt_length', $length_callback);
    }
    if(function_exists($more_callback)){
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>'.$output.'</p>';
    echo $output;
} */

/**
*	Agrega soporte para las miniaturas
**/
/* add_theme_support( 'post-thumbnails' );
//set_post_thumbnail_size( 50, 50, true );
add_image_size( 'xlarge', 1200, 1200); */


/**
*	Agrega Custom post type
**/
register_post_type('custom', array(
	'label' => __('Custom Post Type'),
	'singular_label' => __('Custom Post Type'),
	'public' => true,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'rewrite' => true,
	'query_var' => false,
	'has_archive' => false,
	'supports' => array('title', 'editor', 'thumbnail')
));

/**
*	Agrega Custom taxonomies
**/
add_action( 'init', 'build_taxonomies', 0 );

function build_taxonomies() {
    register_taxonomy( 'taxo', 'custom', array( 
        'hierarchical' => true, 
        'label' => 'Custom Taxonomy', 
        'query_var' => true, 
        'rewrite' => true 
    ) ); 
}

/**
* ACF OPTION PAGE
**/
/* if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
} */

/**
*	Agrega Google Analytics
**/
add_action( 'wp_head', 'my_own_analytics', 20 );
function my_own_analytics() { ?>

   <!-- Acá va el script (todo con las etiquetas script incluidas) -->
    
<?php }

