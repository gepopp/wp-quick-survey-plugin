<?php

namespace QuickSurvey;

class MetaBox {


	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'add_survey_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );

	}


	public function add_survey_meta_box( $post_type ) {

        if($post_type != 'quick_survey') return;

		add_meta_box(
			'quick_sruvey_meta_box',
			__( 'Add a survey', 'quick-survey' ),
			[ $this, 'render_meta_box_content' ],
			$post_type,
			'advanced',
			'high'
		);
	}


	public function render_meta_box_content( $post ) {

		$value = get_post_meta( $post->ID, 'quick-survey-question', true );

		$value = maybe_unserialize( $value );


		ob_start();
		?>
        <div id="quick-survey-admin">
            <survey :survey="<?php echo htmlspecialchars( json_encode( $value ), ENT_QUOTES, 'UTF-8' ); ?>"></survey>
        </div>
		<?php
		echo ob_get_clean();
	}

	public function save( $post_id ) {

		if ( ! isset( $_POST['qsy'] ) ) {
			return;
		}

		update_post_meta( $post_id, 'quick-survey-question', maybe_serialize( $_POST['qsy'] ) );

	}
}
