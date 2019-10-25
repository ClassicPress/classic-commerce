<?php
/**
 * Order Notes
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin/Meta Boxes
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Meta_Box_Order_Notes Class.
 */
class WC_Meta_Box_Order_Notes {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		global $post;

		$args = array(
			'order_id' => $post->ID,
		);

		$notes = wc_get_order_notes( $args );

		echo '<ul class="order_notes">';

		if ( $notes ) {

			foreach ( $notes as $note ) {

				$note_classes   = array( 'note' );
				$note_classes[] = $note->customer_note ? 'customer-note' : '';
				$note_classes[] = 'system' === $note->added_by ? 'system-note' : '';
				$note_classes   = apply_filters( 'woocommerce_order_note_class', array_filter( $note_classes ), $note );
				?>
				<li rel="<?php echo absint( $note->id ); ?>" class="<?php echo esc_attr( implode( ' ', $note_classes ) ); ?>">
					<div class="note_content">
						<?php echo wpautop( wptexturize( wp_kses_post( $note->content ) ) ); ?>
					</div>
					<p class="meta">
						<abbr class="exact-date" title="<?php echo $note->date_created->date( 'y-m-d h:i:s' ); ?>"><?php printf( __( 'added on %1$s at %2$s', 'classic-commerce' ), $note->date_created->date_i18n( wc_date_format() ), $note->date_created->date_i18n( wc_time_format() ) ); ?></abbr>
						<?php
						if ( 'system' !== $note->added_by ) :
							/* translators: %s: note author */
							printf( ' ' . __( 'by %s', 'classic-commerce' ), $note->added_by );
						endif;
						?>
						<a href="#" class="delete_note" role="button"><?php _e( 'Delete note', 'classic-commerce' ); ?></a>
					</p>
				</li>
				<?php
			}
		} else {
			echo '<li>' . __( 'There are no notes yet.', 'classic-commerce' ) . '</li>';
		}

		echo '</ul>';
		?>
		<div class="add_note">
			<p>
				<label for="add_order_note"><?php _e( 'Add note', 'classic-commerce' ); ?> <?php echo wc_help_tip( __( 'Add a note for your reference, or add a customer note (the user will be notified).', 'classic-commerce' ) ); ?></label>
				<textarea type="text" name="order_note" id="add_order_note" class="input-text" cols="20" rows="5"></textarea>
			</p>
			<p>
				<label for="order_note_type" class="screen-reader-text"><?php _e( 'Note type', 'classic-commerce' ); ?></label>
				<select name="order_note_type" id="order_note_type">
					<option value=""><?php _e( 'Private note', 'classic-commerce' ); ?></option>
					<option value="customer"><?php _e( 'Note to customer', 'classic-commerce' ); ?></option>
				</select>
				<button type="button" class="add_note button"><?php _e( 'Add', 'classic-commerce' ); ?></button>
			</p>
		</div>
		<?php
	}
}
