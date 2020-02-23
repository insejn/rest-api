<?php

namespace AKPRO\CaseStudies;
use Micropackage\DocHooks\HookAnnotations;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Fields extends HookAnnotations {
	/**
	 * @action acf/init
	 */
	public function build() {
		$crm_details = new FieldsBuilder('crm_details');
		$crm_details
			->addRepeater('zakres_prac', ['min' => 1, 'layout' => 'block'])
				->addText('nazwa')
				->addWysiwyg('opis')
				->endRepeater()
			->addRepeater('parametry_ekranow', ['min' => 1, 'layout' => 'block'])
				->addText('nazwa')
				->addWysiwyg('opis')
				->endRepeater()
			->addGallery('zdjecia')
			->addOembed('film')
			->addDatePicker('data_realizacji')
			->addText('miejsce_wykonania')
			->addText('nazwa_klienta')
			->addText('imienazwisko_referencje')
			->addText('cytat_referencje')
			->addNumber('crm_id')
			->setLocation('post_type', '==', 'case_study');

		acf_add_local_field_group($crm_details->build());
	}

	public static function get_gallery( $post_id ) {
		$gallery = get_field('zdjecia', $post_id );
		$gallery_parsed = [];
		foreach($gallery as $img) {
			$gallery_parsed[] = [
				'id'	=> get_post_meta( 'crm_image_id', $img, true),
				'link' => wp_get_attachment_url( $img ),
			];
		}

		return $gallery_parsed;
	}

	public static function update_gallery( $field_name, $data, $post_id ) {
		if ( empty( $data ) ) return;

		$gallery_ids = get_field( $field_name, $post_id );

		foreach( $data as $image ) {
			if(self::image_exists( $image['id'] ) ) {
				$gallery_array[] = $image['id'];
			} else {
				$id = self::upload_image( $image['link'] );
				if(! is_wp_error( $id ) ) {
					$gallery_array[] = $id;
					update_post_meta( $id, 'crm_image_id', $image['id'] );
				}
			}
		}
		update_field( $field_name, $gallery_array, $post_id );
	}

	public static function image_exists( $crm_id ) {
		$args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_query'  => array(
				array(
					'key'     => 'crm_image_id',
					'value'   => $crm_id
				)
			)
		);
		$query = new \WP_Query($args);
		return ($query->post_count > 0) ? true : false;
	}

	public static function upload_image($url) {
		if ( ! function_exists( 'download_url' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			include_once(ABSPATH."/wp-admin/includes/media.php");
			include_once(ABSPATH."/wp-admin/includes/file.php");
			include_once(ABSPATH."/wp-admin/includes/image.php");
		}

		$image = "";
		if($url != "") {

			$file = array();
			$file['name'] = $url;
			$file['tmp_name'] = download_url($url);

			if (is_wp_error($file['tmp_name'])) {
				@unlink($file['tmp_name']);
				var_dump( $file['tmp_name']->get_error_messages( ) );
			} else {
				$attachmentId = media_handle_sideload($file);

				if ( is_wp_error($attachmentId) ) {
					@unlink($file['tmp_name']);
					var_dump( $attachmentId->get_error_messages( ) );
				} else {
					$image = $attachmentId;
				}
			}
		}
		return $image;
	}

	public static function update_repeater( $field_name, $data, $post_id ) {
		$repeater = get_field($field_name, $post_id);
		if( count($repeater) === 0 ) {
			foreach( $data as $row ) {
				add_row( $field_name, $row, $post_id );
			}
		} else {
			$i = 1;
			foreach($data as $row) {
				update_sub_field( array($field_name, $i, 'nazwa'), $row['nazwa'], $post_id);
				update_sub_field( array($field_name, $i, 'opis'), $row['opis'],  $post_id);
				$i++;
			}
		}
	}
}
