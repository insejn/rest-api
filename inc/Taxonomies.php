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
	public static function get_tags() {
		// get tags array - slug
		//crm_id, wp_id, slug, name
	}

	public static function get_category() {
		// get category - crm_id, wp_id, slug, name
	}

	public static function set_category() {

	}

	public static function set_tags() {

	}

	public static function create_tag( $tag_data ) {

	}

	public static function create_category( $category_data ) {

	}

	public static function checkIfTagExists( $tag_crm_id ) {

	}

	public static function checkIfCategoryExists( $category_crm_id ) {

	}
}
