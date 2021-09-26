<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://example.com/
 * @since      1.0.0
 *
 * @package    Woocrp
 * @subpackage Woocrp/public/partials
 */

 
$columns_count =  wc_get_loop_prop( 'columns' ); // Save global columns quantity
wc_set_loop_prop( 'columns', 5 ); // we need five columns
?>
 
<h2><?php _e('You may also like this products') ?></h2>
<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
<?php
    while ( $related_products->have_posts() ) : $related_products->the_post();
        wc_get_template_part( 'content', 'product' );
    endwhile;
    wc_set_loop_prop( 'columns', $columns_count); // Reset
    wp_reset_postdata();
?> 
</ul>