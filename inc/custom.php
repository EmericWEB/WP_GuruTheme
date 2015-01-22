<?php
/*
Plugin Name: Custom Post
Description: Extend posts
Author: Guru Meditation
Version: 1.6
Author URI: http://suppr.fr/
*/
    
abstract class CustomPost {
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
    protected $version;
    protected $option_version;
    protected $post_type;
    protected $_meta = array();
        
    function __construct() {
        
                     $this->version = "1.0";
                         
                    $this->option_version =  get_class($this) . '_version';
                    // Load plugin text domain
                    add_action( 'init', array( $this, 'plugin_textdomain' ) );
                    add_action( 'init', array( $this, 'custom' ) );
                        
                    // Register admin styles and scripts
                    add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
                    add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
                        
                    // Register site styles and scripts
                    //add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
                    //add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
                        
                        
                    //add_action('plugins_loaded', array( $this, 'update'));
                        
	    /*
	     * TODO:
	     * Define the custom functionality for your plugin. The first parameter of the
	     * add_action/add_filter calls are the hooks into which your code should fire.
	     *
	     * The second parameter is the function name located within this class. See the stubs
	     * later in the file.
	     *
	     * For more information: 
	     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
	     */
                      add_action( 'add_meta_boxes', array( $this, 'add_notice_metabox' ) );
                      add_action( 'save_post', array( $this, 'save' ) );
	    //add_action( 'TODO', array( $this, 'action_method_name' ) );
	    //add_filter( 'TODO', array( $this, 'filter_method_name' ) );
                
                      // using 11 priority to deal with wpautop call, we don't want our plugin pass to this function
                      //add_filter( 'the_content', array( $this, 'show_video' ), 11 );
                      //add_filter( 'template_include', array( $this, 'template' ), 1 );
                          
                          
                       add_action( 'admin_menu', array($this, 'add_menu_link' ));
                       /* Personalize Admin Panel */
                       /* add_filter( 'manage_video_posts_columns', array($this, 'columns') );
                        add_action( 'manage_posts_custom_column', array($this, 'custom_columns') );
                            
                        add_action( 'restrict_manage_posts', array($this,'filter_list') );
                        add_filter( 'parse_query', array($this, 'perform_filtering' ));
                            
                        * 
                        */
	} // end constructor
            
     abstract function custom ();
         
     /**
	 * Loads the plugin text domain for translation
	 */
	final public function plugin_textdomain() {
                    return;
                    // TODO: replace "plugin-name-locale" with a unique value for your plugin
                    $domain = $this->post_type . '-locale';
                    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
                    load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
                    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
                        
	} // end plugin_textdomain
            

        public function columns($columns) {
            //print_r($columns);

            $columns['video_thumb'] = 'Vignette';
            unset( $columns['comments'] );
            unset( $columns['date'] );
            return $columns;
        }

        public function custom_columns( $column ) {
            if ( 'video_thumb' == $column ) {
                if(has_post_thumbnail()) {
                    echo the_post_thumbnail(array(50,50));
                }

            }
        }
    
    /**
     * Registers and enqueues admin-specific styles.
     */
    public function register_admin_styles() {
         wp_enqueue_style('thickbox');
            // TODO:	Change 'plugin-name' to the name of your plugin
            //wp_enqueue_style($this->post_type . '-admin-styles', plugins_url( 'css/'.$this->post_type .'admin.css' ) );
                
    } // end register_admin_styles
        
    /**
     * Registers and enqueues admin-specific JavaScript.
     */	
    public function register_admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_register_script('my-upload', get_stylesheet_directory_uri().'/js/upload.js', array('jquery','media-upload','thickbox'));
        wp_enqueue_script('my-upload');
        // TODO:	Change 'plugin-name' to the name of your plugin
        //wp_enqueue_script( $this->post_type .'-admin-script', plugins_url( 'js/'.$this->post_type .'-admin.js' ) );
                
    } // end register_admin_scripts
        
    /**
     * Registers and enqueues plugin-specific styles.
     */
    public function register_plugin_styles() {
        
            // TODO:	Change 'plugin-name' to the name of your plugin
            wp_enqueue_style( $this->post_type .'-plugin-styles', plugins_url( 'css/'.$this->post_type .'-display.css' ) );
                
    } // end register_plugin_styles
        
    /**
     * Registers and enqueues plugin-specific scripts.
     */
    public function register_plugin_scripts() {
        
            // TODO:	Change 'plugin-name' to the name of your plugin
            wp_enqueue_script( $this->post_type .'-plugin-script', plugins_url( 'js/'.$this->post_type .'-display.js' ) );
                
    } // end register_plugin_scripts
        
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
             
	/**
 	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 */
         function add_notice_metabox() {
            return true;
        } // end add_notice_metabox
            
        function meta_display( $post ) {
                return true;
            } // end meta_display   
                
    function update() {
        if (get_option($this->option_version) != $this->version) {
            $this->activate();
        }
            
    } // end update
        
        
    function dosave($post_id) {
       foreach($this->_meta as $metakey) {
            // save the code
            $post_meta = isset( $_POST[$this->post_type . $metakey] ) ? $_POST[$this->post_type . $metakey] : '';
            // If the value for the post message exists, delete it first. Don't want to write extra rows into the table.
            if ( 0 == count( get_post_meta( $post_id, $this->post_type . $metakey) ) ) {                      
                delete_post_meta( $post_id, $this->post_type . $metakey);
            } // end if
            update_post_meta( $post_id, $this->post_type . $metakey, $post_meta);
                
        }
        
    }
        function save( $post_id ) {
            
                    if ( isset( $_POST[$this->post_type . '_nonce'] )  ) {
                        
                        // Don't save if the user hasn't submitted the changes
                        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                            return;
                        } // end if
                            
                        // Verify that the input is coming from the proper form
                        if ( ! wp_verify_nonce( $_POST[$this->post_type . '_nonce'], plugin_basename( __FILE__ ) ) ) {
                            return;
                        } // end if
                            
                        /*
                        // Make sure the user has permissions to post
                        if ( 'video' == $_POST['post_type'] ) {
                            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                                return;
                            } // end if
                        } // end if/else
                        */
                        $this->dosave($post_id);
                            
                            
                    } // end if
                        
                    } // end save
	function action_method_name() {
    	// TODO:	Define your action method here
	} // end action_method_name
            
            
                
            function add_menu_link() {
                add_submenu_page( 'edit.php?post_type=' . $this->post_type, 'Options', 'Options', 'manage_options', $this->post_type . '_options', array($this, 'display_options' ) );
            }
                
            function post_options() {}
            function form_options() {}
                
function display_options() {
    if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
        
        
    if(isset( $_POST[$this->post_type . '_nonce'])) {
        if ( ! wp_verify_nonce( $_POST[$this->post_type . '_nonce'], plugin_basename( __FILE__ ) ) ) {
                wp_die( __( 'Nonce Error.' ) );
            return;
        } // end if
        // Read their posted value
        $this->post_options();
        // Put an settings updated message on the screen
            
?>
<div class="updated"><p><strong><?php _e('settings saved.', 'iltd_video-locale' ); ?></strong></p></div>
<?php
    
    }
        
    // Now display the settings editing screen
$this->form_options();
    
}
            function template( $template_path ) {
                if ( get_post_type() == $this->post_type ) {
                    if ( is_single() ) {
                        if ( $theme_file = locate_template( array ( 'single-'. $this->post_type .'.php' ) ) ) {
                            $template_path = $theme_file;
                        } /*else {
                            $template_path = plugin_dir_path( __FILE__ ) . '/single-video.php';
                        }*/
                    }
                    /*else {
                        get_template_part('content', 'event');
                        if ( $theme_file = locate_template( array ( 'content-event.php' ) ) ) {
                            $template_path = $theme_file;
                        } else {
                            $template_path = plugin_dir_path( __FILE__ ) . '/content-event.php';
                        }
                    }*/
                }
                return $template_path;
            }
                
                
            function filter_list() {
                $screen = get_current_screen();
                global $wp_query;
                if ( $screen->post_type == $this->post_type ) {
                    wp_dropdown_categories( array(
                        'show_option_all' => 'Toutes les vidéos',
                        'taxonomy' => 'video_category',
                        'name' => 'video_category',
                        'orderby' => 'name',
                        'selected' => ( isset( $wp_query->query['video_category'] ) ? $wp_query->query['video_category'] : '' ),
                        'hierarchical' => false,
                        'depth' => 3,
                        'show_count' => false,
                        'hide_empty' => false,
                    ) );
                }
            }
                
            function perform_filtering( $query ) {
                $qv = &$query->query_vars;
                if ( isset( $qv['video_category'] ) && is_numeric( $qv['video_category'] ) ) {
                    $term = get_term_by( 'id', $qv['video_category'], 'video_category' );
                    $qv['video_category'] = $term->slug;
                }
            }
                
                
            function filter_method_name() {
                // TODO:	Define your filter method here
            } // end filter_method_name
                
                
} // end class Post_Training
    
   
class Guru_Works extends CustomPost {
    
    function __construct() {
        $this->post_type = "guru_works";
            
        $this->_meta = array('_shortdesc');
        parent::__construct();
            
        add_action('after_setup_theme', array($this,'imagesize'));

    }
    
    function imagesize(){
        add_theme_support( 'post-thumbnails' );
        /*
        add_image_size('guru_home', 300, 300, true);
        add_image_size('guru_home_2col', 532, 252, true);
        */
    }
     public function custom() {
        register_post_type( $this->post_type,
        array(
        'labels' => array(
            'name' => __( 'Projets' ),
            'singular_name' => __( 'Projet' ),
            'add_new' => __( 'Ajouter' ),
            'add_new_item' => __( 'Ajouter' ),
            'edit' => __( 'Modifier' ),
            'edit_item' => __('Modifier'),
            'new_item' => __('Modifier'),
            'view' => __( 'Voir' ),
            'view_item' => __( 'Voir'),
            'search_items' => __( 'Rechercher'),
            'not_found' => __('Aucun résultat trouvé'),
            'not_found_in_trash' => __( 'Aucun résultat dans la poubelle' ),
            'parent' => __('Parent')
        ),
            
        'public' => true,
        'menu_position' => 15,
        'supports' => array( 'title', 'editor', 'thumbnail', 'tags'),
        //'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt'),
        //'taxonomies' => array( '' ),
        //'menu_icon' => plugins_url( 'image.png', __FILE__ ),
        'has_archive' => true,
            'rewrite' => array(
                 'slug' => 'works',
            )
        )
        );
            
     }
         
        
    function form_options() {
        parent::form_options();
            
    // settings form
        $meta = get_option($this->post_type . '_meta');
    ?>
<div class="wrap"><h2>Options</h2>
    
    <form name="form_<?php echo $this->post_type; ?>" method="post" action="">
        <input type="hidden" name="<?php echo 'nonce'; ?>" value="Y" />
    
        <p>META 
            <input type="text" name="<?php echo $this->post_type; ?>_meta" value="<?php echo $meta; ?>" size="20" />
        </p>
    
        <hr />
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>
<?php
wp_nonce_field( plugin_basename( __FILE__ ), $this->post_type . '_nonce' );
?>
    </form>
</div>
    
<?php
    }
        
    function post_options() {
        parent::post_options();
            
        $meta = $_POST[ $this->post_type . '_meta' ];
            
        // Save the posted value in the database
        update_option( $this->post_type . '_meta', $meta);
            
    }
        
         function add_notice_metabox() {
             //return;
                add_meta_box(
                    $this->post_type . '_id',
                    "Infos",
                    array( $this, 'meta_display' ),
                    $this->post_type,
                    'normal',
                    'high'
                );
            } // end add_notice_metabox
                
           function meta_display( $post ) {
               
            wp_nonce_field( plugin_basename( __FILE__ ), $this->post_type . '_nonce' );
            
            foreach($this->_meta as $meta) {
                ${'meta' . $meta} = get_post_meta( $post->ID, $this->post_type . $meta, true  );
            }
            /*
            $this_link=  get_post_meta( $post->ID, $this->post_type . '_link', true  );
            $this_2cols =  get_post_meta( $post->ID, $this->post_type . '_2cols', true  );
            $this_blank =  get_post_meta( $post->ID, $this->post_type . '_blank', true  );
              */  
    ?>
<table>
    <tr>
        <td style="width: 150px;">Résumé</td>
        <td>
            <?php 
            // looks likes it's not recommended but ...
            wp_editor($meta_shortdesc, $this->post_type . '_shortdesc');
            ?><!--
<textarea cols="80" rows="5" name="<?php echo $this->post_type . '_shortdesc' ?>"><?php echo $meta_shortdesc?></textarea>
              -->
        </td>
    </tr>
</table>
    <?php
        
                } // end meta_display   
                    
}


