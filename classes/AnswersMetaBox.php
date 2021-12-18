<?php

namespace QuickSurvey;

class AnswersMetaBox {


	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'add_survey_answers_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );

	}


	public function add_survey_answers_meta_box( $post_type ) {

		if ( $post_type != 'quick_survey' ) {
			return;
		}


		add_meta_box(
			'quick_sruvey_answers_meta_box',
			__( 'Survey answers', 'quick-survey' ),
			[ $this, 'render_answers_meta_box_content' ],
			$post_type,
			'advanced',
			'high'
		);
	}


	public function render_answers_meta_box_content( $post ) {


		$answers = Answers::load_all_answers( $post->ID );

		ob_start();
		?>
            <div id="quick-survey-table">
                <answers-table :answers-given="<?php echo htmlentities( json_encode($answers)) ?>"></answers-table>
            </div>
		<?php
		echo ob_get_clean();
	}

	public function save( $post_id ) {

		if ( ! isset( $_POST['qsy-answers'] ) ) {
			return;
		}

		update_post_meta( $post_id, 'quick-survey-answers', maybe_serialize( $_POST['qsy-answers'] ) );

	}

}
