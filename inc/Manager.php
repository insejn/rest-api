<?php

namespace AKPRO\CaseStudies;
use Micropackage\DocHooks\Helper;

class Manager {
	public static function get_all() {
		$case_studies = [];
		$posts = get_posts(['post_type' => 'case_study', 'per_page' => -1]);

		foreach( $posts as $post ) {
			$case_studies[] = self::translate_post_to_api( $post );
		}

		return $case_studies;
	}

	public static function get_one( $id ) {
		$post = get_post( $id );
		if ($post instanceof \WP_Post) {
			return self::translate_post_to_api( $post );
		} else {
			return false;
		}
	}

	public static function insert( $request ) {
		$post_id = wp_insert_post([
			'post_type' => 'case_study',
			'title' => $request['nazwa'],
			'post_content' => $request['opis'],
			'post_status'	=> ($request['aktywny']) ? 'publish' : 'draft',
		]);

		if(is_wp_error( $post_id )) return $post_id;

		self::update_fields( $post_id, $request );

		return $post_id;
	}

	public static function translate_post_to_api( $post ) {
		return [
			'id'                       => get_field('crm_id', $post->ID),
			'wp_id'                    => $post->ID,
			'nazwa'                    => $post->post_title,
			'opis'                     => $post->post_content,
			'zdjecia'                  => Fields::preparePhotosForApi( 'zdjecia', $post->ID ),
			'zakres_prac'              => get_field( 'zakres_prac', $post->ID ),
			'parametry_ekranow'        => get_field('parametry_ekranow'),
			'tagi'                     => Taxonomies::get_tags(),
			'kategoria_realizacji'     => Taxonomies::get_category(),
			'aktywny'                  => ($post->post_status === 'publish') ? true : false,
			'wyrozniony'               => is_sticky( $post->ID ),
			'zmodyfikowany'            => $post->post_modified,
			"film"                     => get_field('film', $post->ID),
			"data_realizacji"          => get_field('data_realizacji', $post->ID),
			'miejsce_wykonania'        => get_field('miejsce', $post->ID),
			'nazwa_klienta'            => get_field('nazwa klienta', $post->ID),
			'imieinazwisko_referencje' => get_field('imieinazwisko_referencje', $post->ID),
			'nazwa_klienta'            => get_field('nazwa klienta', $post->ID),
			"imienazwisko_referencje"  => get_field('imienazwisko_referencje', $post->ID),
			"cytat_referencje"         => get_field('cytat_referencje', $post->ID)
		];
	}

	public static function update_fields( $post_id, $request ) {
		if(intval( $request['id'] ) ) {
			update_field('crm_id', $request['id'], $post_id);
		}

		Fields::updatePhotos( $request['zdjecia'], $post_id );
		Fields::updateRepeater( 'zakres_prac', $request['zakres_prac'], $post_id );
	}
}
