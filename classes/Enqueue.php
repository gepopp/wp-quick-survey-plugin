<?php

namespace QuickSurvey;

class Enqueue {


	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		add_action( 'admin_head', [ $this, 'add_color_picker' ] );

	}

	public function add_color_picker() {

		ob_start();
		?>
        <script>
            jQuery(document).ready(function ($) {
                $('.color-picker').wpColorPicker();
            })
        </script>

		<?php
		echo ob_get_clean();
	}

	public function enqueue_admin_scripts() {


		wp_register_script( 'quick_survey_admin', QSY_URL . 'dist/survey_admin.min.js', [], QSY_VERSION, true );
		wp_localize_script( 'quick_survey_admin', 'translations', [
			'status'              => __( 'Umfrage Status', 'quick-survey' ),
			'open'                => __( 'Offen', 'quick-survey' ),
			'closed'              => __( 'Geschlossen', 'quick-survey' ),
			'type'                => __( 'Fragentype', 'quick-survey' ),
			'truefalse'           => __( 'JA/NEIN', 'quick-survey' ),
			'redvalue'            => __( 'NEIN', 'quick-survey' ),
			'greenvalue'          => __( 'JA', 'quick-survey' ),
			'skala'               => __( 'Skala', 'quick-survey' ),
			'question'            => __( 'Frage zu diesem Beitrag', 'quick-survey' ),
			'description'         => __( 'Beschreibung der Frage', 'quick-survey' ),
			'showresults'         => __( 'Zeige Ergebnisse', 'quick-survey' ),
			'green'               => __( 'Text auf dem linkem Button', 'quick-survey' ),
			'red'                 => __( 'Text auf dem rechtem Button', 'quick-survey' ),
			'greencolorlabel'     => __( 'Farbe des linken Buttons', 'quick-survey' ),
			'redcolorlabel'       => __( 'Farbe des rechten Buttons', 'quick-survey' ),
			'minlabel'            => __( 'Mindeswert', 'quick-survey' ),
			'maxlabel'            => __( 'Maximalwert', 'quick-survey' ),
			'steplabel'           => __( 'Schrittweite', 'quick-survey' ),
			'mintextlabel'        => __( 'Beschriftung Mindestwert', 'quick-survey' ),
			'midtextlabel'        => __( 'Beschriftung Mittelwert', 'quick-survey' ),
			'maxtextlabel'        => __( 'Beschriftung Maximalwert', 'quick-survey' ),
			'barcolorlabel'       => __( 'Farbe des Schiebereglers und der Ergebnischart', 'quick-survey' ),
			'feedbacklabel'       => __( 'Feedbackseite', 'quick-survey' ),
			'feedbackclosed'      => __( 'Feedbackseite nicht anzeigen', 'quick-survey' ),
			'feedbackevery'       => __( 'Feedbackseite nach jeder Frage', 'quick-survey' ),
			'feedbackend'         => __( 'Feedbackseite am Ende', 'quick-survey' ),
			'multiplechoicelabel' => __( 'Antwortmodus', 'quick-survey' ),
			'multiplechoice'      => __( 'Multiplechoice', 'quick-survey' ),
			'singleanswerlabel'   => __( 'Eine Antwort wählbar', 'quick-survey' ),
			'multianswerlabel'    => __( 'Mehrer Antworten wählbar', 'quick-survey' ),
			'answerlabel'         => __( 'Auswahl', 'quick-survey' ),
			'textquestionlabel'   => __( 'Textfrage', 'quick-survey' ),
		] );
		wp_enqueue_script( 'quick_survey_admin' );


		wp_enqueue_style(
			'quick_survey_styles',
			trailingslashit( QSY_URL ) . "dist/survey_admin.css",
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

		wp_enqueue_script( 'campaign_monitor', 'https://js.createsend1.com/javascript/copypastesubscribeformlogic.js' );

		wp_enqueue_script( 'quick_survey_script', QSY_URL . "dist/main{$ext}.js", [], QSY_VERSION, true );
//		wp_localize_script( 'quick_survey_script', 'qsy_translations' );
		wp_localize_script( 'quick_survey_script', 'qsy_xhr', [
			'rootapiurl' => esc_url_raw( rest_url() ),
			'nonce'      => wp_create_nonce( 'wp_rest' ),
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
		] );
	}


}