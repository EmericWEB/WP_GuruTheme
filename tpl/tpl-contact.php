<?php
/**
 * Template Name: Guru Template - Contact
 *
 * @package WordPress
 * @subpackage Guru_Meditation
 * @since Gur Meditation 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
                    <div class="container">
                    <div class="row">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					/*if ( comments_open() || get_comments_number() ) {
						comments_template();
					}*/
                                        
                                        do_action('guru_contact');
                                        ?>
                    <form  action="<?php the_permalink() ?>" class="form-horizontal json"  method="POST" >
                        
                <div id="contact" class="clearfix col-center">
                <div class="form-groups">
                    <div class="">
                    <label for="inputLastname" class="control-labels">Prénom Nom</label>
                      <input type="text" name="lastname" class="form-controls" id="inputLastname" placeholder="Michelle Obama, Alain Ducasse…" value="<?php echo esc_attr($_POST['lastname']) ?>">
                    </div>
                </div>	
                <div class="form-groups">
                    <div class="">
                    <label for="inputEmail" class="control-labels">Email</label>
                        <input type="email" name="email" class="form-controls" id="inputEmail" placeholder="Votre email ici" value="<?php echo esc_attr($_POST['email']) ?>">
                    </div>
                </div>
                <div class="form-groups">
                    <div class="">
                    <label for="inputPhone" class="control-labels">N° Téléphone</label>
                    <input type="tel" name="phone" class="form-controls" id="inputEmail" placeholder="T'as un 06 ?" value="<?php echo esc_attr($_POST['phone']) ?>">
                    </div>
                </div>
                <div class="form-groups">
                    <div class="">
                    <label for="inputMessage" class="control-labels">Message</label>
                        <textarea name="message" class="form-controls" id="inputMessage" rows="5" placeholder="Drôle d'endroit pour une rencontre..."><?php echo esc_textarea($_POST['message']) ?></textarea>
                    </div>
                </div>
                <div class="form-groups">
                    <div class="">
                      <button type="submit" class="">Envoyer</button>
                    </div>
                  </div>
                
                </div>
                
                    <input type="hidden" name="guru_contact" value="contact" />
                    <?php wp_nonce_field("contact", "_wp_contact") ?>
                </form>
                <div class="col-center">
                    <div class="json-log"></div>
                </div>
                    <?php
				endwhile;
			?>

                        </div><!-- .row -->
                    </div><!-- .container -->
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
//get_sidebar();
get_footer();
