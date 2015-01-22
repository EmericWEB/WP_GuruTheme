<?php

function guru_contact_shortcode( $atts ) {
    extract( shortcode_atts( array(
            'email' => 'contact@',
    ), $atts ) );

    ob_start();
    
    // for sure this is a bot
    if(isset($_POST['guru_diduagree'])) {
        die('<p class="alert alert-success">Votre message a bien été envoyé.</p>');
    }
    
    if(isset($_POST['guru_contact'])) {

        if( isset($_POST['guru_captcha'])) {
            $errors = array();
            if ( ! wp_verify_nonce( $_POST['_wp_contact'], 'nonce') ) {
                $errors[] = 'Who are you ?';
                return;
            }

            //if( empty($_POST['nom']) or empty($_POST['prenom']) or empty($_POST['email']) or empty($_POST['message'])) {
            if( empty($_POST['lastname']) or empty($_POST['email']) or empty($_POST['message'])) {
                $errors[] = 'Tous les champs sont obligatoires.';
            }
            elseif(! is_email($_POST['email']) ) {
                $errors[] = "L'adresse <b>" . $_POST['email'] . "</b> n'est pas une adresse valide.";
            }
            else {
                $message = '<h2>'. get_bloginfo('name') . '</h2>';
                $message .= $_POST['lastname'] . ' ';
                $message .= $_POST['email'] . ' ';
                $message .= stripslashes($_POST['message']);

                $mails = get_option('gurutheme_email');
                foreach( explode(',', $mails) as $mail) {
                    $email = trim($mail);
                    if(is_email($email)) {
                         wp_mail($email, 'Contact', $message);
                    }
                }
                    echo '<p class="alert alert-success">Votre message a bien été envoyé.</p>';
                    $_POST = array();
                //wp_mail(get_option('kf_email'), 'Contact', $message);

            } // endif

            if(count($errors)) {
                foreach($errors as $e) {
                    echo '<p class="alert alert-danger">' . $e . '</p>';
                }
            }/*
            else {

                    echo '<p class="alert alert-info">Message envoyé</p>';
            }*/
        }
        else {

                    echo '<p class="alert alert-info">Activer le javascript pour envoyer votre message.</p>';
        }
        
    }
    ?>
<form  action="<?php the_permalink() ?>" class="form-horizontal"  method="POST" >
    <div class="form-line">
        <p>
        <input type="email" name="email" class="form-input" id="inputEmail" placeholder="Email" value="<?php echo esc_attr($_POST['email']) ?>">
        </p>
    </div>
    <div class="form-line">
        <p>
          <input type="text" name="lastname" class="form-input" id="inputLastname" placeholder="Nom, Prénom, Pseudo..." value="<?php echo esc_attr($_POST['lastname']) ?>">
                    </p>
    </div>
    <div class="form-line">
        <p>
          <input type="text" name="phone" class="form-input" id="inputPhone" placeholder="Téléphone" value="<?php echo esc_attr($_POST['phone']) ?>">
        </p>
    </div>
    <div class="form-line">
        <p>
            MESSAGE
            <textarea name="message" class="form-input" id="inputTxt" rows="5" placeholder="Merci d'indiquez un numéro de commande, si besoin."><?php echo esc_textarea($_POST['message']) ?></textarea>
        </p>
    </div>
    <button type="submit" class="bordaround">Envoyer</button>
    <!--
                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" name="email" class="form-control" id="inputEmail" placeholder="E@mail.fr" value="<?php echo esc_attr($_POST['email']) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputLastname" class="col-sm-2 control-label">Nom</label>
                    <div class="col-sm-10">
                      <input type="text" name="lastname" class="form-control" id="inputLastname" placeholder="Nom, Prénom, Pseudo..." value="<?php echo esc_attr($_POST['lastname']) ?>">
                    </div>
                  </div>	
                <div class="form-group">
                    <label for="inputPhone" class="col-sm-2 control-label">Téléphone</label>
                    <div class="col-sm-10">
                      <input type="text" name="phone" class="form-control" id="inputPhone" placeholder="Téléphone" value="<?php echo esc_attr($_POST['phone']) ?>">
                    </div>
                  </div>	
                <div class="form-group">
                    <label for="inputTxt" class="col-sm-2 control-label">Message</label>
                    <div class="col-sm-10">
                        <textarea name="message" class="form-control" id="inputTxt" rows="5" placeholder="Merci d'indiquez un numéro de commande, si besoin."><?php echo esc_textarea($_POST['message']) ?></textarea>
                    </div>
                </div>
    
                <div class="form-group no-border">
                    <label class="col-sm-2 control-label"><button type="submit" class="bordaround">Envoyer</button></label>
                    <div class="col-sm-10">
                      &nbsp;
                    </div>
                  </div>
   -->

                    <input type="hidden" name="guru_contact" value="1" />
                        <?php wp_nonce_field("nonce", "_wp_contact") ?>
                </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'contact-form', 'guru_contact_shortcode' );


function add_gmap_scripts ($posts) {
    if ( empty($posts) )
            return $posts;

        $shortcode_found = false;

        foreach ($posts as $post) {

            //$post->post_content;
            if (  stripos($post->post_content, '[guru_gmap') !== false  ) {
                $shortcode_found = true;
                break;
            }
        }

        if ( $shortcode_found ) {
            wp_enqueue_script( 'googleapis-map', 'https://maps.googleapis.com/maps/api/js?key='.get_option('gurutheme_google_apikey').'&sensor=false', array(), '1.0');
            wp_enqueue_script( 'guru-gmap', get_stylesheet_directory_uri() . '/js/gmap.js', array(), '1.0', true );
            
        }
        return $posts;
    
}
add_action('the_posts', 'add_gmap_scripts', 1);

add_shortcode('guru_gmap', 'guru_gmap');
function guru_gmap($atts) {
    extract( shortcode_atts( array(
        'addr' => ''
     ), $atts ) );

    $output = '<div class="gmap" ><div id="map-canvas" data-map="'. esc_attr($addr) .'"></div></div>';
    return $output;
    
}
add_shortcode('parallax', 'guru_parallax');
function guru_parallax($atts, $content) {
    extract( shortcode_atts( array(
        'title' => '',
     ), $atts ) );

    $output = '';
    $img = array();
    preg_match_all('/(src)=("[^"]*")/i',$content, $img);
    //print_r($img);
    if(isset($img[2])) {
        $output .= '<div class="parallax" style="background-image: url('. trim($img[2][0], '"') .')">';

        if($title) {
            $output .= '<div class="parallax_static"><span>' . $title . '</span></div>';
        }

        $output .= '</div>';
    }
    return $output;
    
}

add_shortcode('guru_btn', 'guru_btn');
function guru_btn($atts, $content) {
    extract( shortcode_atts( array(
     ), $atts ) );
    
    return '<span class="guru_btn">' . $content . '</span>';
}

add_shortcode('guruteam', 'guru_team');
add_shortcode('guru_team', 'guru_team');
function guru_team($atts, $content) {
    extract( shortcode_atts( array(
        'p' => '',
     ), $atts ) );

    
    $posts = get_posts(array('post_type' => 'guru_team',
        'orderby' => 'menu_order',
    'posts_per_page' => -1,
        'order' => 'ASC',));

    $output = '';
    if(count($posts)) {
        $output .= '<div class="clearfix">';
        foreach($posts as $post) {
            $output .= '<div class="guru-team col-lg-3 col-sm-3 col-xs-6">'
                    . '<div class="txt-team">'
                    . '<h2>' . $post->post_title . '</h2>'
                    . $post->post_content
                    . '</div>';
            
            if(has_post_thumbnail($post->ID)) {
            
            $output .= get_the_post_thumbnail($post->ID, 'guru_square',
                        array(
                        'class' => "img-responsive img-team-1",
                        ));
            if (class_exists('MultiPostThumbnails')) :
                $output .= MultiPostThumbnails::get_the_post_thumbnail(
                    'guru_team',
                    'image-2',
                    $post->ID, 
                    "guru_square",
                    array('class' => "img-responsive img-team-2")
                );
                $output .= MultiPostThumbnails::get_the_post_thumbnail(
                    'guru_team',
                    'image-3',
                    $post->ID, 
                    "guru_square",
                    array('class' => "img-responsive img-team-3")
                );
            endif; 
            }
            else {
                foreach(array(1,2,3) as $i) {
                    if(get_post_meta($post->ID, 'guru_team_mos_' .$i, true)) {
                        $output .= '<img src="'. get_post_meta($post->ID, 'guru_team_mos_' .$i, true) .'" class="img-responsive img-team-'. $i .'" />';
                    }
                }
            }
            $output .=  '</div>';

        }
        $output .=  '</div>';
    }
    return $output;
}


add_shortcode('guru_usp', 'guru_custom_posts');
function guru_custom_posts($atts, $content) {
    global $post;
    $tmp = $post;
    extract( shortcode_atts( array(
        'p' => '',
        'post_type' => 'guru_usp',
        'cat' => '',
        'ids' => '',
     ), $atts ) );


    $the_query = new WP_Query(array(
        //'post_type' => $post_type,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        
	'tax_query' => array(
		array(
			'taxonomy' => 'guru_usp_category',
			'terms' => $cat,
			'field'    => 'slug',
		)),
        //'category' => $cat,
        'posts_per_page' => 12,
    ));
    if ($the_query->have_posts()) :
    
        $output .= '<div class="row-'. $cat .'">';
        while($the_query->have_posts()) {
        $the_query->the_post();
            ob_start();
            //$post = $p;
            get_template_part('content', $post_type);
            $output .= $p->post_title;
            $output .= ob_get_contents();
            ob_clean();
        }
        $output .=  '</div>';
        
    endif;

    
    $post = $tmp;
    
    return $output;
}

if(is_admin()) {
// a class for creating PopUp for shortcodes in TinyMCE
class Guru_TinyMCE {
    
    public function __construct() {
        add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
        add_filter( 'mce_buttons_2', array( $this, 'filter_mce_button_2' ) );
        add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
        
        add_action('edit_form_after_editor',  array($this, 'tinymce_popup'));
    }
    
    function filter_mce_button( $buttons ) {
            // add a separation before our button, here our button's id is "mygallery_button"
            array_push( $buttons, '|',  'guru_usp', 'guru_team', 'guru_btn' );
            return $buttons;
    }

    function filter_mce_button_2( $buttons ) {
            // add a separation before our button, here our button's id is "mygallery_button"
            array_push( $buttons, '|', 'sup', 'sub');
            return $buttons;
    }

    function filter_mce_plugin( $plugins ) {
            // this plugin file will work the magic of our button
            $plugins['guru_usp'] = get_stylesheet_directory_uri() . '/tinymce/tinymce_plugin.js';
            return $plugins;
    }
    
    
    function tinymce_popup() {
        ?>


<div id="guru_display_posts_popup" style="display: none;">
        <table id="mygallery-table" class="form-table">
            <tr>
                <th><label for="post_type">Choisir les données</label></th>
                <td>
                <?php
                $objects = false;
                    $post_types = get_post_types( array(
                            'public' => true
                                    ), 'objects' );
                    
                    $categories = get_categories(array('taxonomy' => 'guru_usp_category'));
                        if(count($categories)) : 
                        foreach ($categories as $cat) {
                            //print_r($cat);
                        ?>
                                <p><label><input type="radio" name="guru_display_posts-cat" value="<?php echo $cat->slug; ?>" />
                                    <?php echo $cat->name; ?>
                                    </label></p>
                            <?php                
                                    }
                                    
                    /*foreach ( $post_types as $post_type ) {
                            if ( $post_type->name != 'attachment' ) {
                                
                                ?>
                    <h3>&nbsp;<?php echo $post_type->label; ?></h3>
                                    <?php
                                $taxonomies = get_object_taxonomies($post_type->name, 'objects');
                                foreach ($taxonomies as $taxo) {    
                                    $categories = get_categories(array('taxonomy' => $taxo->name));
                                    if(count($categories)) : 
                                    foreach ($categories as $cat) {
                                        //print_r($cat);
                                    ?>
                                <p><label><input type="radio" name="guru_display_posts-cat" value="<?php echo $cat->slug; ?>" />
                                    <?php echo $cat->name; ?>
                                    </label></p>
                            <?php                
                                    }
                                }
                                
                            }
                    }
                     * 
                     */
                                    endif;
                    ?>
                                    
                </td>
            </tr>
            <tr>
                <th><label for="posts_per_page">Nombre d'articles</label></th>
                <td><input name="guru_display_posts-posts_per_page" /></td>
            </tr>
            <tr>
                <th><label for="posts_per_page">Identifiants</label></th>
                <td><input name="guru_display_posts-ids" /></td>
            </tr>
        </table>
        <p class="submit">
                <input type="button" id="guru_display_posts_popup-submit" class="button-primary" value="Ajouter le code" name="submit" />
        </p>
</div>
        <?php
    }
}
    new Guru_TinyMCE;
}
//echo get_stylesheet_directory();