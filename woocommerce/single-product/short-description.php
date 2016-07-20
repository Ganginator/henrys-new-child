<?php
/**
 * Single product short description
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

if ( ! $post->post_excerpt ) return;
?>
<?php if($word_descriptors = get_post_meta( $post->ID, 'Word Descriptors', TRUE )): ?>
	<div class="word-descriptors">
		<p><?php echo $word_descriptors; ?></p>
	</div>
<?php endif; ?>
<div itemprop="description" class="product_description">
	<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
</div>