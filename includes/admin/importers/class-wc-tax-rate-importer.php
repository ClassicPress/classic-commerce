<?php
/**
 * Tax importer class file
 *
 * @version WC-2.3.0
 * @package ClassicCommerce/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Importer' ) ) {
	return;
}

/**
 * Tax Rates importer - import tax rates and local tax rates into Classic Commerce.
 *
 * @package     ClassicCommerce/Admin/Importers
 * @version     WC-2.3.0
 */
class WC_Tax_Rate_Importer extends WP_Importer {

	/**
	 * The current file id.
	 *
	 * @var int
	 */
	public $id;

	/**
	 * The current file url.
	 *
	 * @var string
	 */
	public $file_url;

	/**
	 * The current import page.
	 *
	 * @var string
	 */
	public $import_page;

	/**
	 * The current delimiter.
	 *
	 * @var string
	 */
	public $delimiter;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->import_page = 'woocommerce_tax_rate_csv';
		$this->delimiter   = empty( $_POST['delimiter'] ) ? ',' : (string) wc_clean( wp_unslash( $_POST['delimiter'] ) ); // WPCS: CSRF ok.
	}

	/**
	 * Registered callback function for the WordPress Importer.
	 *
	 * Manages the three separate stages of the CSV import process.
	 */
	public function dispatch() {

		$this->header();

		$step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step'];

		switch ( $step ) {

			case 0:
				$this->greet();
				break;

			case 1:
				check_admin_referer( 'import-upload' );

				if ( $this->handle_upload() ) {

					if ( $this->id ) {
						$file = get_attached_file( $this->id );
					} else {
						$file = ABSPATH . $this->file_url;
					}

					add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

					$this->import( $file );
				}
				break;
		}

		$this->footer();
	}

	/**
	 * Import is starting.
	 */
	private function import_start() {
		if ( function_exists( 'gc_enable' ) ) {
			gc_enable(); // phpcs:ignore PHPCompatibility.PHP.NewFunctions.gc_enableFound
		}
		wc_set_time_limit( 0 );
		@ob_flush();
		@flush();
		@ini_set( 'auto_detect_line_endings', '1' );
	}

	/**
	 * UTF-8 encode the data if `$enc` value isn't UTF-8.
	 *
	 * @param mixed  $data Data.
	 * @param string $enc Encoding.
	 * @return string
	 */
	public function format_data_from_csv( $data, $enc ) {
		return ( 'UTF-8' === $enc ) ? $data : utf8_encode( $data );
	}

	/**
	 * Import the file if it exists and is valid.
	 *
	 * @param mixed $file File.
	 */
	public function import( $file ) {
		if ( ! is_file( $file ) ) {
			$this->import_error( __( 'The file does not exist, please try again.', 'classic-commerce' ) );
		}

		$this->import_start();

		$loop   = 0;
		$handle = fopen( $file, 'r' );

		if ( false !== $handle ) {

			$header = fgetcsv( $handle, 0, $this->delimiter );

			if ( 10 === count( $header ) ) {

				$row = fgetcsv( $handle, 0, $this->delimiter );

				while ( false !== $row ) {

					list( $country, $state, $postcode, $city, $rate, $name, $priority, $compound, $shipping, $class ) = $row;

					$tax_rate = array(
						'tax_rate_country'  => $country,
						'tax_rate_state'    => $state,
						'tax_rate'          => $rate,
						'tax_rate_name'     => $name,
						'tax_rate_priority' => $priority,
						'tax_rate_compound' => $compound ? 1 : 0,
						'tax_rate_shipping' => $shipping ? 1 : 0,
						'tax_rate_order'    => $loop ++,
						'tax_rate_class'    => $class,
					);

					$tax_rate_id = WC_Tax::_insert_tax_rate( $tax_rate );
					WC_Tax::_update_tax_rate_postcodes( $tax_rate_id, wc_clean( $postcode ) );
					WC_Tax::_update_tax_rate_cities( $tax_rate_id, wc_clean( $city ) );

					$row = fgetcsv( $handle, 0, $this->delimiter );
				}
			} else {
				$this->import_error( __( 'The CSV is invalid.', 'classic-commerce' ) );
			}

			fclose( $handle );
		}

		// Show Result.
		echo '<div class="updated settings-error"><p>';
		printf(
			/* translators: %s: tax rates count */
			esc_html__( 'Import complete - imported %s tax rates.', 'classic-commerce' ),
			'<strong>' . absint( $loop ) . '</strong>'
		);
		echo '</p></div>';

		$this->import_end();
	}

	/**
	 * Performs post-import cleanup of files and the cache.
	 */
	public function import_end() {
		echo '<p>' . esc_html__( 'All done!', 'classic-commerce' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=tax' ) ) . '">' . esc_html__( 'View tax rates', 'classic-commerce' ) . '</a></p>';

		do_action( 'import_end' );
	}
	
	
	/**
	 * Get all the valid filetypes for a CSV file.
	 *
	 * @return array
	 */
	protected static function get_valid_csv_filetypes() {
		return apply_filters(
			'woocommerce_csv_import_valid_filetypes', array(
				'csv' => 'text/csv',
				'txt' => 'text/plain',
			)
		);
	}
	
	/**
	 * Handles the CSV upload and initial parsing of the file to prepare for.
	 * displaying author import options.
	 *
	 * @return bool False if error uploading or invalid file, true otherwise
	 */
	public function handle_upload() {
		$file_url = isset( $_POST['file_url'] ) ? wc_clean( wp_unslash( $_POST['file_url'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification -- Nonce already verified in WC_Tax_Rate_Importer::dispatch()

		if ( empty( $file_url ) ) {
			if ( ! isset( $_FILES['import'] ) || $_FILES['import']['name'] == '' ) {
			  $this->import_error( __( 'File is empty. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.', 'classic-commerce' ) );
			}

			if ( ! wc_is_file_valid_csv( $_FILES['import']['name'], false ) ) {
				$this->import_error( __( 'Invalid file type. The importer supports CSV and TXT file formats.', 'classic-commerce' ) );
			}
			
			$overrides = array(
				'test_form' => false,
				'mimes'     => self::get_valid_csv_filetypes(),
			);
			$import    = $_FILES['import']; // WPCS: sanitization ok, input var ok.
			$upload    = wp_handle_upload( $import, $overrides );
			
			if ( isset( $upload['error'] ) ) {
				$this->import_error( $upload['error'] );
			}
			
			// Construct the object array.
			$object = array(
			  'post_title'     => basename( $upload['file'] ),
			  'post_content'   => $upload['url'],
			  'post_mime_type' => $upload['type'],
			  'guid'           => $upload['url'],
			  'context'        => 'import',
			  'post_status'    => 'private',
			);

			// Save the data.
			$id = wp_insert_attachment( $object, $upload['file'] );
			
			/*
			 * Schedule a cleanup for one day from now in case of failed
			 * import or missing wp_import_cleanup() call.
			 */
			wp_schedule_single_event( time() + DAY_IN_SECONDS, 'importer_scheduled_cleanup', array( $id ) );

			$this->id = absint( $id );
		} elseif ( file_exists( ABSPATH . $file_url ) ) {
			if ( ! wc_is_file_valid_csv( ABSPATH . $file_url ) ) {
				$this->import_error( __( 'Invalid file type. The importer supports CSV and TXT file formats.', 'classic-commerce' ) );
			}

			$this->file_url = esc_attr( $file_url );
		} else {
			$this->import_error();
		}

		return true;
	}

	/**
	 * Output header html.
	 */
	public function header() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Import tax rates', 'classic-commerce' ) . '</h1>';
	}

	/**
	 * Output footer html.
	 */
	public function footer() {
		echo '</div>';
	}

	/**
	 * Output information about the uploading process.
	 */
	public function greet() {

		echo '<div class="narrow">';
		echo '<p>' . esc_html__( 'Hi there! Upload a CSV file containing tax rates to import the contents into your shop. Choose a .csv file to upload, then click "Upload file and import".', 'classic-commerce' ) . '</p>';

		/* translators: 1: Link to tax rates sample file 2: Closing link. */
		echo '<p>' . sprintf( esc_html__( 'Your CSV needs to include columns in a specific order. %1$sClick here to download a sample%2$s.', 'classic-commerce' ), '<a href="' . esc_url( WC()->plugin_url() ) . '/sample-data/sample_tax_rates.csv">', '</a>' ) . '</p>';

		$action = 'admin.php?import=woocommerce_tax_rate_csv&step=1';

		$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		$size       = size_format( $bytes );
		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) :
			?>
			<div class="error">
				<p><?php esc_html_e( 'Before you can upload your import file, you will need to fix the following error:', 'classic-commerce' ); ?></p>
				<p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
			</div>
		<?php else : ?>
			<form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr( wp_nonce_url( $action, 'import-upload' ) ); ?>">
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label for="upload"><?php esc_html_e( 'Choose a CSV file from your computer:', 'classic-commerce' ); ?></label>
							</th>
							<td>
								<input type="file" id="upload" name="import" size="25" />
								<input type="hidden" name="action" value="save" />
								<input type="hidden" name="max_file_size" value="<?php echo absint( $bytes ); ?>" />
								<small>
									<?php
									printf(
										/* translators: %s: maximum upload size */
										esc_html__( 'Maximum size: %s', 'classic-commerce' ),
										esc_attr( $size )
									);
									?>
								</small>
							</td>
						</tr>
						<tr>
							<th><label><?php esc_html_e( 'Delimiter', 'classic-commerce' ); ?></label><br/></th>
							<td><input type="text" name="delimiter" placeholder="," size="2" /></td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<button type="submit" class="button" value="<?php esc_attr_e( 'Upload file and import', 'classic-commerce' ); ?>"><?php esc_html_e( 'Upload file and import', 'classic-commerce' ); ?></button>
				</p>
			</form>
			<?php
		endif;

		echo '</div>';
	}

	/**
	 * Show import error and quit.
	 *
	 * @param  string $message Error message.
	 */
	private function import_error( $message = '' ) {
		echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'classic-commerce' ) . '</strong><br />';
		if ( $message ) {
			echo esc_html( $message );
		}
		echo '</p>';
		echo '<p><a href="' . esc_url( admin_url( 'admin.php?import=woocommerce_tax_rate_csv' ) ) . '">' . esc_html__( '&laquo; Back', 'classic-commerce' ) . '</a></p>';
		$this->footer();
		die();
	}

	/**
	 * Added to http_request_timeout filter to force timeout at 60 seconds during import.
	 *
	 * @param  int $val Value.
	 * @return int 60
	 */
	public function bump_request_timeout( $val ) {
		return 60;
	}
}
