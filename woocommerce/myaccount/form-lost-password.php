<?php
/**
 * Lost password form
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<style>
	#site-top-bar,
	#masthead,
	.entry-title,
	.page-title,
	#site-footer
	{
		display: none !important;
	}
	
	.login_header
	{
		display: block;
	}
	
	.st-content,
	.st-container
	{
		height: 100%;
	}
	
	.st-content
	{
		overflow-y: auto;
	}	
</style>

<?php wc_print_notices(); ?>

<div class="row">
	<div class="medium-10 medium-centered large-6 large-centered columns">

		<div class="login-register-container">
				
			<div class="account-forms-container">

				<form method="post" class="lost_reset_password">

				<?php if( 'lost_password' === $args['form'] ) : ?>
			
					<p class="lost-reset-pass-text"><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p>
			
					<p class=""><label for="user_login"><?php _e( 'Username or email', 'woocommerce' ); ?></label> <input class="input-text" type="text" name="user_login" id="user_login" /></p>
			
				<?php else : ?>
			
					<p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?></p>
			
					<p class="form-row form-row-first">
						<label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="password" class="input-text" name="password_1" id="password_1" />
					</p>
					<p class="form-row form-row-last">
						<label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="password" class="input-text" name="password_2" id="password_2" />
					</p>
			
					<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
					<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />
			
				<?php endif; ?>
			
				<div class="clear"></div>
			
				<?php do_action( 'woocommerce_lostpassword_form' ); ?>
			
				<p class="form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<input type="submit" class="button" value="<?php echo 'lost_password' === $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>" />
				</p>
			
				<?php wp_nonce_field( $args['form'] ); ?>
			
			</form>

			</div><!-- .account-forms-container	-->
		
		</div><!-- .login-register-container-->
	</div><!-- .medium-8 .large-6-->
</div><!-- .row-->
	
<div class="login_footer">
	<a class="go_home" href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
</div>
