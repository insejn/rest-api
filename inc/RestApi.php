<?php

namespace AKPRO\CaseStudies;
use Micropackage\DocHooks\HookAnnotations;


class RestApi extends HookAnnotations {
	/**
	 * @action rest_api_init
	 */
	public function register_get_endpoint() {
		register_rest_route( 'akpro', '/case-studies/(?P<wp_id>\d+)', array(
			'methods' => 'GET',
			'callback' => [$this, 'get_endpoint'],
		) );

		register_rest_route( 'akpro', '/case-studies/', array(
			'methods' => 'GET',
			'callback' => [$this, 'get_all_endpoint'],
		) );

		register_rest_route( 'akpro', '/case-studies/(?P<wp_id>\d+)', array(
			'methods' => 'POST',
			'callback' => [$this, 'update_endpoint'],
		) );

		register_rest_route( 'akpro', '/case-studies/', array(
			'methods' => 'POST',
			'callback' => [$this, 'insert_endpoint'],
		) );

		register_rest_route( 'akpro', '/case-studies/(?P<wp_id>\d+)', array(
			'methods' => 'DELETE',
			'callback' => [$this, 'delete_endpoint'],
		) );
	}

	public function get_all_endpoint( $request ) {
		$response = [];

		$data = Manager::get_all();
		$error = new \WP_Error();

		if ( empty( $data ) ) {
			$error->add( 406, __( 'Posts not found', 'rest-api-endpoints' ) );
			return $error;
		}

		$response['status'] = 200;
		$response['data'] = $data;

		return new \WP_REST_Response( $response );
	}


	public function get_endpoint( $request ) {
		$response = [];
		$params = $request->get_params();

		$data = Manager::get_one( $params['wp_id']);
		$error = new \WP_Error();

		if ( empty( $data ) ) {
			$error->add( 406, __( 'Nie znaleziono posta o podanym ID', 'rest-api-endpoints' ) );
			return $error;
		}

		$response['status'] = 200;
		$response['data'] = $data;

		return new \WP_REST_Response( $response );
	}

	public function insert_endpoint( $request ) {
		$response = array();
		$params = $request->get_params();

		$error = new \WP_Error();

		if ( empty( $params['id'] ) ) {
			$error->add(
				400,
				__( "ID jest wymagane", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		if ( empty( $params['nazwa'] ) ) {
			$error->add(
				400,
				__( "Nazwa jest wymagana", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		if ( empty( $params['opis'] ) ) {
			$error->add(
				400,
				__( "Opis jest wymagany", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		$post_id = Manager::insert( $params );

		if ( ! is_wp_error( $post_id ) ) {
			$response['status'] = 200;
			$response['wp_id'] = $post_id;
		} else {
			$error->add( 406, __( 'Coś poszło nie tak.', 'rest-api-endpoints' ) );
			return $error;
		}

		return new \WP_REST_Response( $response );
	}

	public function update_endpoint( $request ) {
		$response = array();
		$params = $request->get_params();

		$error = new \WP_Error();

		if(! get_post( $params['wp_id'] ) ) {
			$error->add(
				400,
				__( "Post o podanym ID nie istnieje", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		if ( empty( $params['id'] ) ) {
			$error->add(
				400,
				__( "ID jest wymagane", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		if ( empty( $params['nazwa'] ) ) {
			$error->add(
				400,
				__( "Nazwa jest wymagana", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		if ( empty( $params['opis'] ) ) {
			$error->add(
				400,
				__( "Opis jest wymagany", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		$post_id = Manager::update( $params );

		if ( ! is_wp_error( $post_id ) ) {
			$response['status'] = 200;
			$response['wp_id'] = $post_id;
		} else {
			$error->add( 406, __( 'Post creating failed', 'rest-api-endpoints' ) );
			return $error;
		}

		return new \WP_REST_Response( $response );
	}

	public function delete_endpoint( $request ) {
		$response = array();
		$params = $request->get_params();

		$error = new \WP_Error();

		if(! get_post( $params['wp_id'] ) ) {
			$error->add(
				400,
				__( "Post o podanym ID nie istnieje", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		if(Manager::delete( $params['wp_id'] ) ) {
			$response['data'] = 'Pomyślnie usunięto post.';
			$response['status'] = 200;
		} else {
			$error->add(
				400,
				__( "Coś poszło nie tak.", 'rest-api-endpoints' ),
				array( 'status' => 400 )
			);

			return $error;
		}

		return new \WP_REST_Response( $response );
	}
}
