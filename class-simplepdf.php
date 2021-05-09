<?php
/**
 * Class SimplePDF
 *
 * @package pdf
 */

namespace WPSimplePDF;

/**
 * SimplePDF - Basic wrapper methods for mpdf.
 */
class SimplePDF {

	/**
	 * Return URL for the current post that can output PDF
	 *
	 * @param int|WP_Post $post - The post.
	 * @param array       $args - Associative array query arguments to add to URL.
	 * @param boolean     $echo - Outputs the URL if echo is true.
	 *
	 * @return string the URL
	 */
	public static function url( $post = 0, $args = array(), $echo = false ) {
		$args = wp_parse_args( $args, array( 'output' => 'pdf' ) );
		$post = get_post( $post );
		$url  = wp_nonce_url( add_query_arg( $args, get_permalink( $post ) ), 'output-pdf-' . $post->ID );
		if ( $echo ) {
			echo $url;
		} else {
			return $url;
		}
	}

	/**
	 * Get the markup for a post/page row action.
	 *
	 * @param integer|WP_Post $post The post.
	 * @param array           $args the arguments.
	 * @param boolean         $echo - Outputs the html markup if set to true.
	 * @return string html markup
	 */
	public static function post_row_action( $post = 0, $args = array(), $echo = false ) {
		$defaults         = array(
			'text'  => __( 'PDF' ),
			'rel'   => 'nofollow',
			'extra' => array(),
		);
		$args             = wp_parse_args( $args, $defaults );
		$post             = get_post( $post );
		$post_type_object = get_post_type_object( $post->post_type );
		$can_edit_post    = current_user_can( 'edit_post', $post->ID );
		if ( is_post_type_viewable( $post_type_object ) ) {
			if ( ! in_array( $post->post_status, array( 'pending', 'draft', 'future' ), true ) &&
			'trash' !== $post->post_status ) {
				$html = sprintf(
					'<a href="%s" rel="%s" aria-label="%s">%s</a>',
					self::url( $post, $args['extra'] ),
					$args['rel'],
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( '%1$s &#8220;%2$s&#8221;' ), $args['text'], $post->post_title ) ),
					$args['text']
				);
			}
		}
		if ( $echo ) {
			echo $html; // phpcs:ignore
		} else {
			return $html;
		}
	}

	/**
	 * Output PDF using mpdf
	 *
	 * @param string $html - the markup.
	 * @param array  $args - arguments for PDF output.
	 * @return void
	 */
	public static function output_pdf( $html, $args = array() ) {
		$defaults   = array(
			'mode'              => '',
			'format'            => 'A4',
			'default_font_size' => 0,
			'default_font'      => '',
			'margin_left'       => 15,
			'margin_right'      => 15,
			'margin_top'        => 16,
			'margin_bottom'     => 16,
			'margin_header'     => 9,
			'margin_footer'     => 9,
			'orientation'       => 'P',
		);
		$args       = wp_parse_args( $args, $defaults );
		$args       = apply_filters( 'simplepdf_output_pdf_args', $args );
		$mpdf       = new \Mpdf\Mpdf( $args );
		$protection = isset( $args['protection'] ) ? $args['protection'] : null;
		if ( null !== $protection ) {
			$mpdf->SetProtection( $protection );
		}
		$title = isset( $args['title'] ) ? $args['title'] : null;
		if ( null !== $title ) {
			$mpdf->SetTitle( $title );
		}
		$author = isset( $args['author'] ) ? $args['author'] : null;
		if ( null !== $author ) {
			$mpdf->SetAuthor( $author );
		}
		$watermark = isset( $args['watermark'] ) ? $args['watermark'] : '';
		if ( '' !== $watermark ) {
			$mpdf->SetWatermarkText( $watermark );
			$mpdf->showWatermarkText  = true; // phpcs:ignore
			$watermark_font           = isset( $args['watermark_font'] ) ? $args['watermark_font'] : 'DejaVuSansCondensed';
			$mpdf->watermark_font     = $watermark_font;
			$watermark_text_alpha     = isset( $args['watermark_text_alpha'] ) ? $args['watermark_text_alpha'] : 0.1;
			$mpdf->watermarkTextAlpha = $watermark_text_alpha; // phpcs:ignore
		}
		$display_mode = isset( $args['display_mode'] ) ? $args['display_mode'] : 'fullpage';
		$mpdf->SetDisplayMode( $display_mode );
		$mpdf->WriteHTML( $html );
		$filename = isset( $args['filename'] ) ? $args['filename'] : 'simplepdf.pdf';
		$dest     = isset( $args['dest'] ) ? $args['dest'] : 'D';
		$mpdf->Output( $filename, $dest );
	}

	/**
	 * Print the PDF if output query_arg is set to pdf
	 *
	 * @return void
	 */
	public static function pdf_print() {
		global $post;

		if ( isset( $_GET['output'] ) && 'pdf' === $_GET['output'] ) {
			check_admin_referer( 'output-pdf-' . $post->ID );
			$template = \get_template_directory() . '/single.php';
			if ( ! \file_exists( $template ) ) {
				$template = \get_template_directory() . '/index.php';
			}
			$template = apply_filters( 'simplepdf_pdf_print_template', $template );
			ob_start();
			require $template;
			$html = ob_get_clean();
			self::output_pdf( $html );
			die();
		}
	}
}


add_action( 'template_redirect', array( 'WPSimplePDF\SimplePDF', 'pdf_print' ) );
