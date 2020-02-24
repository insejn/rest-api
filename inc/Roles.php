<?php

namespace AKPRO\CaseStudies;
use Micropackage\DocHooks\HookAnnotations;

class Roles extends HookAnnotations {
	/**
	 * @action init
	 */
	public function register_role() {
		add_role( 'case_study_logger', 'Case Study Logger' );
	}

	/**
	 * @action plugins_loaded
	 */
	public function add_capabilities() {
		$roles = ['editor', 'administrator', 'case_study_logger'];

		foreach( $roles as $the_role ) {
			$role = get_role( $the_role );
			$role->add_cap( 'read' );
			$role->add_cap( 'edit_case_studys' );
			$role->add_cap( 'publish_case_studys' );
			$role->add_cap( 'edit_published_case_studys' );
			$role->add_cap( 'delete_published_case_studys' );
			$role->add_cap( 'read_private_case_study' );
			$role->add_cap( 'edit_private_case_studys' );
			$role->add_cap( 'edit_others_case_studys' );
			$role->add_cap( 'delete_published_case_studys' );
			$role->add_cap( 'delete_private_case_studys' );
			$role->add_cap( 'delete_others_case_studys' );
		}
	}
}
