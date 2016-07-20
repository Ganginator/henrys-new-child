<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

?>


<?php if($did_you_know = get_post_meta( $post->ID, 'Did You Know?', TRUE )): ?>
	<div class="did-you-know">
		<p><?php echo $did_you_know; ?></p>
	</div>
<?php else: ?>
	<?php the_content(); ?>
<?php endif; ?>
