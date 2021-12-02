<?php

namespace QuickSurvey;

class Tables {


	const ANSWER_TABLE = "qsy_answers";


	public function __construct() {
		add_action( 'plugins_loaded', [$this, 'update_tables'] );
	}


	public function update_tables(){

		$installed_version = get_option('quick-survey-version');

		if($installed_version == QSY_VERSION) return;

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . self::ANSWER_TABLE;


		$sql        = "CREATE TABLE $table_name (
		id BIGINT NOT NULL AUTO_INCREMENT,
		post_id BIGINT NOT NULL,
		question_id BIGINT NOT NULL,
		user_id BIGINT NULL,
		user_ip VARCHAR(255) NULL,
		user_email VARCHAR(255) NULL,
		answer TEXT NOT NULL,
		feedback TEXT NULL,
		created_at TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );


		update_option( 'quick-survey-version', QSY_VERSION );

	}



}