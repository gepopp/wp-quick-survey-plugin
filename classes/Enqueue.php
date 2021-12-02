<?php

namespace QuickSurvey;

class Enqueue {


	public function __construct() {


		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

	}

	public function enqueue_admin_scripts() {


		wp_enqueue_script('quick_survey_admin', QSY_URL . 'dist/admin.js', [], QSY_VERSION, true);
		wp_enqueue_style(
			'quick_survey_styles',
			trailingslashit( QSY_URL ) . "dist/admin.css",
			[],
			QSY_VERSION
		);

	}





	public function enqueue_frontend_scripts() {

		wp_enqueue_style(
			'quick_survey_styles',
			trailingslashit( QSY_URL ) . "dist/main.css",
			[],
			QSY_VERSION
		);

		$ext = '.min';
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$ext = '';
		}



		wp_enqueue_script( 'quick_survey_script', QSY_URL . "dist/main{$ext}.js", [], QSY_VERSION, true );
//		wp_localize_script( 'quick_survey_script', 'qsy_translations' );
		wp_localize_script( 'quick_survey_script', 'qsy_xhr', [
			'rootapiurl' => esc_url_raw( rest_url() ),
			'nonce'      => wp_create_nonce( 'wp_rest' ),
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		] );
	}


}