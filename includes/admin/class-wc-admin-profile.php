<?php
/**
 * Add extra profile fields for users in admin
 *
 * @author   WooThemes
 * @category Admin
 * @package  WooCommerce/Admin
 * @version  2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Admin_Profile', false ) ) :

	/**
	 * WC_Admin_Profile Class.
	 */
	class WC_Admin_Profile {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			add_action( 'show_user_profile', array( $this, 'add_customer_meta_fields' ) );
			add_action( 'edit_user_profile', array( $this, 'add_customer_meta_fields' ) );

			add_action( 'personal_options_update', array( $this, 'save_customer_meta_fields' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_customer_meta_fields' ) );
		}

		/**
		 * Get Address Fields for the edit user pages.
		 *
		 * @return array Fields to display which are filtered through woocommerce_customer_meta_fields before being returned
		 */
		public function get_customer_meta_fields() {
			$show_fields = apply_filters(
				'woocommerce_customer_meta_fields', array(
					'billing'  => array(
						'title'  => __( 'Customer billing address', 'classic-commerce' ),
						'fields' => array(
							'billing_first_name' => array(
								'label'       => __( 'First name', 'classic-commerce' ),
								'description' => '',
							),
							'billing_last_name'  => array(
								'label'       => __( 'Last name', 'classic-commerce' ),
								'description' => '',
							),
							'billing_company'    => array(
								'label'       => __( 'Company', 'classic-commerce' ),
								'description' => '',
							),
							'billing_address_1'  => array(
								'label'       => __( 'Address line 1', 'classic-commerce' ),
								'description' => '',
							),
							'billing_address_2'  => array(
								'label'       => __( 'Address line 2', 'classic-commerce' ),
								'description' => '',
							),
							'billing_city'       => array(
								'label'       => __( 'City', 'classic-commerce' ),
								'description' => '',
							),
							'billing_postcode'   => array(
								'label'       => __( 'Postcode / ZIP', 'classic-commerce' ),
								'description' => '',
							),
							'billing_country'    => array(
								'label'       => __( 'Country', 'classic-commerce' ),
								'description' => '',
								'class'       => 'js_field-country',
								'type'        => 'select',
								'options'     => array( '' => __( 'Select a country&hellip;', 'classic-commerce' ) ) + WC()->countries->get_allowed_countries(),
							),
							'billing_state'      => array(
								'label'       => __( 'State / County', 'classic-commerce' ),
								'description' => __( 'State / County or state code', 'classic-commerce' ),
								'class'       => 'js_field-state',
							),
							'billing_phone'      => array(
								'label'       => __( 'Phone', 'classic-commerce' ),
								'description' => '',
							),
							'billing_email'      => array(
								'label'       => __( 'Email address', 'classic-commerce' ),
								'description' => '',
							),
						),
					),
					'shipping' => array(
						'title'  => __( 'Customer shipping address', 'classic-commerce' ),
						'fields' => array(
							'copy_billing'        => array(
								'label'       => __( 'Copy from billing address', 'classic-commerce' ),
								'description' => '',
								'class'       => 'js_copy-billing',
								'type'        => 'button',
								'text'        => __( 'Copy', 'classic-commerce' ),
							),
							'shipping_first_name' => array(
								'label'       => __( 'First name', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_last_name'  => array(
								'label'       => __( 'Last name', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_company'    => array(
								'label'       => __( 'Company', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_address_1'  => array(
								'label'       => __( 'Address line 1', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_address_2'  => array(
								'label'       => __( 'Address line 2', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_city'       => array(
								'label'       => __( 'City', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_postcode'   => array(
								'label'       => __( 'Postcode / ZIP', 'classic-commerce' ),
								'description' => '',
							),
							'shipping_country'    => array(
								'label'       => __( 'Country', 'classic-commerce' ),
								'description' => '',
								'class'       => 'js_field-country',
								'type'        => 'select',
								'options'     => array( '' => __( 'Select a country&hellip;', 'classic-commerce' ) ) + WC()->countries->get_allowed_countries(),
							),
							'shipping_state'      => array(
								'label'       => __( 'State / County', 'classic-commerce' ),
								'description' => __( 'State / County or state code', 'classic-commerce' ),
								'class'       => 'js_field-state',
							),
						),
					),
				)
			);
			return $show_fields;
		}

		/**
		 * Show Address Fields on edit user pages.
		 *
		 * @param WP_User $user
		 */
		public function add_customer_meta_fields( $user ) {
			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				return;
			}

			$show_fields = $this->get_customer_meta_fields();

			foreach ( $show_fields as $fieldset_key => $fieldset ) :
				?>
				<h2><?php echo $fieldset['title']; ?></h2>
				<table class="form-table" id="<?php echo esc_attr( 'fieldset-' . $fieldset_key ); ?>">
					<?php foreach ( $fieldset['fields'] as $key => $field ) : ?>
						<tr>
							<th>
								<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
							</th>
							<td>
								<?php if ( ! empty( $field['type'] ) && 'select' === $field['type'] ) : ?>
									<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" style="width: 25em;">
										<?php
											$selected = esc_attr( get_user_meta( $user->ID, $key, true ) );
										foreach ( $field['options'] as $option_key => $option_value ) :
											?>
											<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $selected, $option_key, true ); ?>><?php echo esc_attr( $option_value ); ?></option>
										<?php endforeach; ?>
									</select>
								<?php elseif ( ! empty( $field['type'] ) && 'checkbox' === $field['type'] ) : ?>
									<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="1" class="<?php echo esc_attr( $field['class'] ); ?>" <?php checked( (int) get_user_meta( $user->ID, $key, true ), 1, true ); ?> />
								<?php elseif ( ! empty( $field['type'] ) && 'button' === $field['type'] ) : ?>
									<button type="button" id="<?php echo esc_attr( $key ); ?>" class="button <?php echo esc_attr( $field['class'] ); ?>"><?php echo esc_html( $field['text'] ); ?></button>
								<?php else : ?>
									<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $this->get_user_meta( $user->ID, $key ) ); ?>" class="<?php echo ( ! empty( $field['class'] ) ? esc_attr( $field['class'] ) : 'regular-text' ); ?>" />
								<?php endif; ?>
								<br/>
								<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				<?php
			endforeach;
		}

		/**
		 * Save Address Fields on edit user pages.
		 *
		 * @param int $user_id User ID of the user being saved
		 */
		public function save_customer_meta_fields( $user_id ) {
			$save_fields = $this->get_customer_meta_fields();

			foreach ( $save_fields as $fieldset ) {

				foreach ( $fieldset['fields'] as $key => $field ) {

					if ( isset( $field['type'] ) && 'checkbox' === $field['type'] ) {
						update_user_meta( $user_id, $key, isset( $_POST[ $key ] ) );
					} elseif ( isset( $_POST[ $key ] ) ) {
						update_user_meta( $user_id, $key, wc_clean( $_POST[ $key ] ) );
					}
				}
			}
		}

		/**
		 * Get user meta for a given key, with fallbacks to core user info for pre-existing fields.
		 *
		 * @since WC-3.1.0
		 * @param int    $user_id User ID of the user being edited
		 * @param string $key     Key for user meta field
		 * @return string
		 */
		protected function get_user_meta( $user_id, $key ) {
			$value           = get_user_meta( $user_id, $key, true );
			$existing_fields = array( 'billing_first_name', 'billing_last_name' );
			if ( ! $value && in_array( $key, $existing_fields ) ) {
				$value = get_user_meta( $user_id, str_replace( 'billing_', '', $key ), true );
			} elseif ( ! $value && ( 'billing_email' === $key ) ) {
				$user  = get_userdata( $user_id );
				$value = $user->user_email;
			}

			return $value;
		}
	}

endif;

return new WC_Admin_Profile();
