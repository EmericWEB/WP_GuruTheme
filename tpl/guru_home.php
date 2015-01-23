<?php
/**
 * Template Name: Guru Homepage
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
$_post = $post;
get_header(); ?>

<div id="main-content" class="main-content">


	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					/*if ( comments_open() || get_comments_number() ) {
						comments_template();
					}*/
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
        
<?php

if(is_front_page()) {
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}
$the_query = new WP_Query(array(
    'post_type' => 'guru_works',
    'paged' => $paged,
    'posts_per_page' => 20,
    'order' => 'DESC',
    'orderby' => 'ID',
));
//print_r($the_query);
if ($the_query->have_posts()) :
    //$cols = array(6,6,4,8);
    ?>
    <div class="owl">
    <?php
    for($c=0;$the_query->have_posts();$c++) : $the_query->the_post();
        guru_post_thumbnail('guru_bigsquare')
    ?>
            
    <?php
        //get_template_part('content', 'blog');
    endfor;
    ?>
    </div>
    
<?php guru_pagination($the_query->max_num_pages, $range = 2, $the_query); ?>
            <?php
    
endif;
?>
        
             
<?php

if(is_front_page()) {
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}
$the_query = new WP_Query(array(
    'post_type' => 'post',
    'paged' => $paged,
    'posts_per_page' => 20,
    'order' => 'DESC',
    'orderby' => 'ID',
));
//print_r($the_query);
if ($the_query->have_posts()) :
    //$cols = array(6,6,4,8);
    ?>
    <div id="homeblog">
        <div class="homeblog-inner">
            <div class="slider-nav">
            <a href="#" id="slider-prev">PREV</a>
            <a href="#" id="slider-next">NEXT</a>
            </div>
            <div class="slider">
    <?php
    for($c=0;$the_query->have_posts();$c++) : $the_query->the_post();
    ?>
                <div class="blog-entry slide">
            <?php
            // Post thumbnail.
            //guru_post_thumbnail('guru_fullscreen');
            the_title( '<h2>' . $c, '</h2>' );
            the_content();
            ?>
                </div>
    <?php
        //get_template_part('content', 'blog');
    endfor;
    ?>
            </div>
        </div>
    </div>
    
<?php //guru_pagination($the_query->max_num_pages, $range = 2, $the_query); ?>
            <?php
    
endif;
?>
        
</div><!-- #main-content -->

<?php
//get_sidebar();
get_footer();
