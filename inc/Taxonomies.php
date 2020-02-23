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
		wp_get_post_terms( $post_id, 'tag_realizacji' );

	}

	public static function get_category( $post_id ) {
		wp_get_post_terms( $post_id, 'kategoria_realizacji' );
	}

	public static function set_category() {
		term_exists( $term, $taxonomy = '' );

	}

	public static function set_tags($terms) {
		foreach($terms as $term) {

		}
	}

	public static function create_term( $term, $taxonomy ) {
		$term_id = wp_insert_term( $term['name'], $taxonomy );
		update_term_meta( $term_id, 'crm_term_id', $term['id'] );

		return $term_id;
	}
}
