<?php
function guru_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'twentyfourteen' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Crimson Text:400,700' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}
function guru_scripts() {        
//wp_enqueue_style( 'guru-font', guru_font_url(), array(), null );
//wp_enqueue_style( 'guru-font-y', 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700,300&subset=latin,latin-ext', array(), null );
wp_enqueue_style( 'guru-bootstrap', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap.custom.css', array(), '3.0.3' );
wp_enqueue_style( 'guru-bootstrap-theme', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap-theme.min.css', array(), '3.0.3' );
wp_enqueue_style( 'guru-style', get_stylesheet_directory_uri() . '/style.css', array(), '3.0.3' );

if(get_page_template_slug() == 'tpl/guru_contact.php') {
            wp_enqueue_script( 'googleapis-map', 'https://maps.googleapis.com/maps/api/js?key='.get_option('gurutheme_google_apikey').'&sensor=false', array(), '1.0');
            wp_enqueue_script( 'guru-gmap', get_stylesheet_directory_uri() . '/js/gmap.js', array(), '1.0', true );
}
    wp_enqueue_script('jquery-masonry');
    wp_enqueue_style('jquery-masonry');
    
    wp_enqueue_script( 'guru-images-loaded', get_stylesheet_directory_uri() . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '20141209', true );
    wp_enqueue_script( 'guru-script', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), '20141209', true );

}
add_action( 'wp_enqueue_scripts', 'guru_scripts' );

add_action('init', 'guru_imagesize');

function guru_imagesize(){
    add_image_size('guru_fullscreen', 1920, 1280, true);
    add_image_size('guru_square', 300, 300, true);
    add_image_size('guru_bigsquare', 600, 600, true);
    add_image_size('guru_169', 640, 360, true);
    add_image_size('guru_large', 640, 9999);
    add_image_size('guru_col', 320, 9999);
    add_image_size('guru_vertical', 9999, 240);
    add_image_size('guru_blog', 640, 240, true);

    add_filter('image_size_names_choose', 'guru_image_sizes', 11);
    add_filter( 'get_image_tag_class', 'guru_image_classes' );
    add_filter( 'get_image_tag', 'guru_image_tag' );
}

function guru_image_classes($class) 
{
    return "img-responsive";
}
function guru_image_tag($html) 
{

    $array = explode(' ',$html);
    $output = array();
    foreach($array as $attr) {
        if( ( strpos($attr, "width") === FALSE ) && ( strpos($attr, "height") === FALSE ) )  {
            $output[] = $attr;
        }
    }
    return implode(' ',$output);



}
function guru_image_sizes($image_sizes) {
     // get the custom image sizes
    global $_wp_additional_image_sizes;
    //print_r($_wp_additional_image_sizes);
    // if there are none, just return the built-in sizes
    if ( empty( $_wp_additional_image_sizes ) ) {
        return $image_sizes;
    }
    // add all the custom sizes to the built-in sizes
    foreach ( $_wp_additional_image_sizes as $id => $data ) {
        // take the size ID (e.g., 'my-name'), replace hyphens with spaces,
        // and capitalise the first letter of each word
        if ( !isset($image_sizes[$id]) )
            //$image_sizes[$id] = implode('x' , $data)  . ' - ' . ucfirst( str_replace( array("_", "-"), "  ", $id ) );
            $image_sizes[$id] = ucfirst( str_replace( array("_", "-"), "  ", $id ) );
        }

    return $image_sizes;
    /*
            $addsizes = array(
                    "new-size" => __( "New Size")
                    );
        $newsizes = array_merge($sizes, $addsizes);
        return $newsizes;
     * 
     */
}

if ( ! function_exists( 'guru_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since GuruMeditation1.0
 */
function guru_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on twentyfifteen, use a find and replace
	 * to change 'twentyfifteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'gurumeditation', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'gurumeditation' ),
		'footer'  => __( 'Footer Menu', 'gurumeditation' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

        /*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	//add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', twentyfifteen_fonts_url() ) );
}
endif; // guru_setup
add_action( 'after_setup_theme', 'guru_setup' );

add_action( 'admin_menu', 'guru_add_theme_options' );
function guru_add_theme_options() {
                add_theme_page( __('Theme Options'), __('Options'), 'manage_options', 'guru_theme_options','guru_theme_options' );
            }
            
function guru_theme_options() {
    if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    
    ?>
<h2><?php echo _e('Theme Options'); ?></h2>
<?php
    
    $options = array(
        "Général" => array(
            "Email" => array('gurutheme_email'),
            "Crédits" => array('gurutheme_credits', 'textarea'),
        ),
        "Social Network" => array(
            "Twitter" => array('gurutheme_twitter'),
            "LinkedIn" => array('gurutheme_linkedin'),
            "FaceBook" => array('gurutheme_facebook'),
            "Vimeo" => array('gurutheme_vimeo'),
        ),
        "Google" => array(
            "Google API Key" => array('gurutheme_google_apikey'),
            "Google Analytics" => array('gurutheme_google_analytics'),
        ),
    );
    
    foreach($options as $option => $fields) {
        
        $hidden = md5($option);
        $save = false;
        if(isset( $_POST['gurutheme_nonce_'. $hidden])) {
            if ( ! wp_verify_nonce( $_POST['gurutheme_nonce_'. $hidden], 'gurutheme_options_' . $hidden) ) {
                    wp_die( __( 'Nonce Error.' ) );
                return;
            } // end if
            $save = true;
        }
        ?>
<h3 style="border-bottom: 1px solid #ccc;"><?php echo $option; ?></h3>
<form method="post" name="form_<?php echo $hidden; ?>">
<input type="hidden" name="<?php echo 'nonce'; ?>" value="Y" />
<input type="hidden" name="form_submit" value="<?php echo $hidden; ?>" />
<table>
<?php
foreach($fields as $name => $field) {
    
    $option_value = get_option($field[0]);
    
    if($save) {
        $option_value = stripslashes($_POST[$field[0]]);
        update_option($field[0], $option_value);
    }
    
    ?>
    <tr>
<td style="width:210px;text-align: right;"><p><b><?php echo $name ?></b></p></td>
<td><p>
    <?php
    $input_type = isset($field[1])?$field[1]:'input';
    switch ($input_type) {
        case 'tinymce':
            wp_editor($option_value, $field[0]);
            break;
        case 'textarea':
?>
    <textarea name="<?php echo $field[0] ?>" cols="50" rows="10"><?php echo $option_value ?></textarea>
    <?php

            break;

        case 'input':
        default:
            ?>
    <input type="text" name="<?php echo $field[0] ?>" size="60" value="<?php echo esc_attr($option_value) ?>" />
<?php
            break;
    }
    ?>
</p></td>
</tr>
   <?php
}
?>
</table>
<?php
if($save) {
?>
<div class="updated"><p><strong>Mise à jour terminée.</strong></p></div>
<?php
}
?>

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?> - <?php echo $option; ?>" />
</p>
<?php
wp_nonce_field( 'gurutheme_options_' . $hidden, 'gurutheme_nonce_'. $hidden );
?>
</form>


        <?php
        //print_r($fields);
        
    }
    
    return;
}

// Filter to hide protected posts
function exclude_protected($where) {
	global $wpdb;
	return $where .= " AND {$wpdb->posts}.post_password = '' ";
}

// Decide where to display them
function exclude_protected_action($query) {
	if( !is_single() && !is_page() && !is_admin() ) {
		add_filter( 'posts_where', 'exclude_protected' );
	}
}

// Action to queue the filter at the right time
add_action('pre_get_posts', 'exclude_protected_action');
function my_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <label for="' . $label . '">' . __( "Password:" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /><input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" />
    </form>
    ';
    return $o;
}
add_filter( 'the_password_form', 'my_password_form' );


add_action('rss2_item', function(){
  global $post;

  $output = '';
  $thumbnail_ID = get_post_thumbnail_id( $post->ID );
  $thumbnail = wp_get_attachment_image_src($thumbnail_ID, 'thumbnail');
  if($thumbnail) {
    $output .= '<thumbnail>';
    $output .= '<url>'. $thumbnail[0] .'</url>';
    $output .= '<width>'. $thumbnail[1] .'</width>';
    $output .= '<height>'. $thumbnail[2] .'</height>';
    $output .= '</thumbnail>';

  echo $output;
  }
});


function guru_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" role="navigation">
		<!--<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'gurumeditation' ); ?></h1>-->
		<div class="nav-links">
                                    	<?php
			if ( is_attachment() ) :
				previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'gurumeditation' ) );
			else :
				previous_post_link( '%link', __( '%title', 'gurumeditation' ) );
                        ?><br /><?php
				next_post_link( '%link', __( '%title', 'gurumeditation' ) );
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
function guru_array_split($array, $pieces=2)
{
// Less then 2 pieces?
if ($pieces < 2)
{
return array($array);
}
 
$newCount = ceil(count($array)/$pieces);
 
$a = array_slice($array, 0, $newCount);
 
$b = $this->array_split(array_slice($array, $newCount), $pieces-1);
 
return array_merge(array($a),$b);
}



/*
 * Custom Back-Office
 */

function guru_login_headerurl($url) {
    return home_url();
}

function guru_login_headertitle($title) {
    return get_bloginfo('name');
}
function guru_admin_filters(){
    
add_filter( 'login_headerurl', 'guru_login_headerurl' , 11);
add_filter( 'login_headertitle', 'guru_login_headertitle' , 11);
/*    
$login_header_url = apply_filters( 'login_headerurl', $login_header_url );
$login_header_title = apply_filters( 'login_headertitle', $login_header_title );
*/
}
add_action( 'login_init', 'guru_admin_filters', 1 );


add_action('login_head', 'guru_style_login');
function guru_style_login() {
    echo '<style>
body.login {background-color:#f9f9f9;}
#login {padding:4em 0 0;}
.login h1 a {background-image : url('. get_stylesheet_directory_uri() .'/img/logo.png);
width:100%;
display:block;
background-position:center center;
height:80px;
background-size:contain;
}
</style>';
}

//add_filter('admin_footer_text',   'guru_admin_footer',99);
function guru_admin_footer() {
    return '<p>Created by GuruMeditation</p>';
}

//require get_stylesheet_directory() . '/inc/custom.php';
//require get_stylesheet_directory() . '/inc/shortcodes.php';
//new CustomPost();
