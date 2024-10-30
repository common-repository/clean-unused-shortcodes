<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://amrelarabi.com
 * @since      1.0.0
 *
 * @package    Clean_Unused_Shortcodes
 * @subpackage Clean_Unused_Shortcodes/admin/partials
 */

?>

<h2><?php esc_html_e( 'Clean unused shortcodes', 'clean-unused-shortcodes' ); ?></h2>

<div id="cus_feedback_modal"><p></p></div>
<div class="cus-field-row">
	<label for="cus_select_post_type">
		<?php esc_html_e( 'Post type', 'clean-unused-shortcodes' ); ?>
	</label>
	<select id="cus_select_post_type" multiple="multiple" name="test[]">
		<option value="all"><?php esc_html_e( 'All', 'clean-unused-shortcodes' ); ?></option>

		<?php foreach ( $post_types as $post_obj ) : ?>
			<?php if ( 'attachment' !== $post_obj->name ) : ?>
				<option value="<?php echo esc_attr( $post_obj->name ); ?>"><?php echo esc_html( $post_obj->label ); ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
	</select>
</div>
<div class="cus-field-row">
	<button id="cus_clean_shortcodes" class="button"><?php esc_html_e( 'Clean', 'clean-unused-shortcodes' ); ?>
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
			<rect x="0" y="13" width="4" height="5" fill="#333">
			<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
			<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
			</rect>
			<rect x="10" y="13" width="4" height="5" fill="#333">
			<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
			<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
			</rect>
			<rect x="20" y="13" width="4" height="5" fill="#333">
			<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
			<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
			</rect>
		</svg>
	</button>
</div>
