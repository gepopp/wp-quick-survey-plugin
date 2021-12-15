<?php

namespace QuickSurvey;

class Boot {


	private static $instance = false;


	private $boot_classes = [
		Enqueue::class,
		MetaBox::class,
		SponsorMetaBox::class,
		AttachMetaBox::class,
		FrontendQuestion::class,
		Tables::class,
		Answers::class,
		CustomPostType::class,
	];


	public static function getInstance(): Boot {

		if ( ! self::$instance ) {
			self::$instance = new Boot();
		}

		return self::$instance;

	}

	public function boot() {

		$this->bootHooks();

		foreach ( $this->boot_classes as $boot_class ) {

			if ( class_exists( $boot_class ) ) {
				new $boot_class();
			}
		}

	}

	public function bootHooks() {

		add_action( 'init', function () {
			load_plugin_textdomain( 'quick-survey', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		} );
	}

	private function __construct() {
	}

	private function __clone() {
	}


}