<?php

namespace AKPRO\CaseStudies;

use PostTypes\PostType as PT;

class PostType {
	public function __construct() {
		$case_study = new PT([
			'name'     => 'case_study',
			'singular' => 'Case Study',
			'plural'   => 'Case Studies',
			'slug'     => 'case-study'
		]);

		$case_study->register();
		$case_study->options([
			'supports' => ['title', 'editor', 'thumbnail'],
			'capability_type' => 'case_study',
			'map_meta_cap' => true,
		]);
		$case_study->taxonomy( 'kategoria_realizacji' );
		$case_study->taxonomy( 'tag_realizacji' );
	}
}



