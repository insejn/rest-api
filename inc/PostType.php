<?php

namespace AKPRO\CaseStudies;

use PostTypes\PostType as PT;

class PostType {
	public function __construct() {
		$case_study = new PT('case_study');

		$case_study->register();
	}
}



