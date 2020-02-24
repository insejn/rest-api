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
			'post_title' => $request['nazwa'],
			'post_content' => $request['opis'],
			'post_status'	=> ($request['aktywny']) ? 'publish' : 'draft',
		]);

		if(is_wp_error( $post_id )) return $post_id;

		self::update_fields( $post_id, $request );
		$kategoria[] = $request['kategoria_realizacji'];
		Taxonomies::set_terms( $post_id, $kategoria, 'kategoria_realizacji');
		Taxonomies::set_terms( $post_id, $request['tagi'], 'tag_realizacji' );

		return $post_id;
	}

	public static function update( $request ) {
		$post_id = wp_update_post([
			'ID'		=> $request['wp_id'],
			'post_type' => 'case_study',
			'post_title' => $request['nazwa'],
			'post_content' => $request['opis'],
			'post_status'	=> ($request['aktywny']) ? 'publish' : 'draft',
		]);

		if(is_wp_error( $post_id )) return $post_id;

		self::update_fields( $post_id, $request );
		Taxonomies::set_terms( $post_id, $request['tagi'], 'tag_realizacji' );
		Taxonomies::set_terms( $post_id, $request['kategoria'], 'kategoria_realizacji');

		return $post_id;
	}

	public static function delete( $id ) {
		return wp_delete_post( $id, true );
	}

	public static function translate_post_to_api( $post ) {
		return [
			'crm_id'                   => get_field('crm_id', $post->ID),
			'id'                       => $post->ID,
			'nazwa'                    => $post->post_title,
			'opis'                     => $post->post_content,
			'zdjecia'                  => Fields::get_gallery( $post->ID ),
			'zakres_prac'              => get_field( 'zakres_prac', $post->ID ),
			'parametry_ekranow'        => get_field( 'parametry_ekranow', $post->ID ),
			'tagi'                     => Taxonomies::get_tags( $post->ID ),
			'kategoria_realizacji'     => Taxonomies::get_category( $post->ID ),
			'aktywny'                  => ($post->post_status === 'publish') ? true : false,
			'wyrozniony'               => is_sticky( $post->ID ),
			'zmodyfikowany'            => $post->post_modified,
			"film"                     => get_field( 'film', $post->ID ),
			"data_realizacji"          => get_field( 'data_realizacji', $post->ID ),
			'miejsce_wykonania'        => get_field('miejsce_wykonania', $post->ID ),
			'nazwa_klienta'            => get_field( 'nazwa_klienta', $post->ID ),
			'imieinazwisko_referencje' => get_field( 'imienazwisko_referencje', $post->ID ),
			"cytat_referencje"         => get_field( 'cytat_referencje', $post->ID )
		];
	}

	public static function update_fields( $post_id, $request ) {
		if(intval( $request['id'] ) ) {
			update_field('crm_id', $request['id'], $post_id);
		}

		update_field('film', sanitize_url( $request['film']), $post_id);
		update_field('data_realizacji', sanitize_text_field( $request['data_realizacji']), $post_id);
		update_field('miejsce_wykonania', sanitize_text_field( $request['miejsce_wykonania']), $post_id);
		update_field('nazwa_klienta', sanitize_text_field( $request['nazwa_klienta']), $post_id);
		update_field('imienazwisko_referencje', sanitize_text_field( $request['imienazwisko_referencje']), $post_id);
		update_field('cytat_referencje', sanitize_text_field( $request['cytat_referencje']), $post_id);

		Fields::update_gallery( 'zdjecia', $request['zdjecia'], $post_id );
		Fields::update_repeater( 'zakres_prac', $request['zakres_prac'], $post_id );
		Fields::update_repeater( 'parametry_ekranow', $request['parametry_ekranow'], $post_id );
	}
}
