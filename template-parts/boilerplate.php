<?php
/**
 *  Template for boilerplate
 *
 *
 */

$cats = get_the_category();

if ( !empty( $cats ) ){ 
    
    $cat = $cats[0]->slug;
}


?>
<article id="boilerplate-post-<?php the_ID(); ?>" class="boilerplate boilerplate-<?php echo $cat; ?> border-top py-5 my-5">
    <h1 class="h3"><?php the_title(); ?></h1>
    <?php echo wpautop( $post->post_content ); ?>
</article>