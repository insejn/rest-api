<?php

namespace AKPRO\CaseStudies;
use PostTypes\Taxonomy;

class Taxonomies {
	public function __construct() {
		$case_study_category = new Taxonomy([
			'name'     => 'kategoria_realizacji',
			'singular' => 'Kategoria realizacji',
			'plural'   => 'Kategorie realizacji',
			'slug'     => 'kategoria-realizacji'
		]);
		$case_study_category->register();
		$case_study_tag = new Taxonomy([
			'name'     => 'tag_realizacji',
			'singular' => 'Tag realizacji',
			'plural'   => 'Tagi realizacji',
			'slug'     => 'tag-realizacji'
		]);
		$case_study_tag->register();
	}
	public static function get_tags( $post_id ) {
		return wp_get_post_terms( $post_id, 'tag_realizacji' );

	}

	public static function get_category( $post_id ) {
		return wp_get_post_terms( $post_id, 'kategoria_realizacji' );
	}

	public static function set_terms( $post_id, $terms, $taxonomy ) {
		$terms_array = [];
		foreach($terms as $term) {
			$wp_term = get_term_by( 'name', $term['name'], $taxonomy );
			if($wp_term === false) {
				$term_id = self::create_term($term['name'], $taxonomy);
			} else {
				$term_id = $wp_term->term_id;
			}
			$terms_array[] = $term_id;
		}

		wp_set_object_terms( $post_id, $terms_array, $taxonomy, true );
	}

	public static function create_term( $term, $taxonomy ) {
		$term = wp_insert_term( $term, $taxonomy );
		update_term_meta( $term['term_id'], 'crm_term_id', $term['id'] );

		return $term['term_id'];
	}
}
