<?php

namespace QuickSurvey;

class FrontendQuestion {


	public function __construct() {
		add_filter( 'the_content', [ $this, 'attach_question' ] );
		add_shortcode( 'quick-survey', [ $this, 'survey_shortcode' ] );
	}


	public function survey_renderalbe( $question ) {

		$status = $question['status'] ?? 'closed';
		if ( $status == 'closed' ) {
			return false;
		}

		$q = $question['question'] ?? '';

		if ( empty( $q ) ) {
			return false;
		}

		return true;
	}

	function attach_question( $content ) {

		global $post;

		$question = maybe_unserialize( get_post_meta( $post->ID, 'quick-survey-question', true ) );


		if ( $this->survey_renderalbe( $question ) && ! has_shortcode( $content, 'quick-survey' ) ) {
			$content .= $this->render_question( $post );
		}

		return $content;

	}

	function survey_shortcode() {
		global $post;

		return $this->render_question( $post );
	}


	function render_question( $post ) {

		$question = maybe_unserialize( get_post_meta( $post->ID, 'quick-survey-question', true ) );

		$answers = Answers::answers_for_chart( $post->ID );

		ob_start();
		?>
        <div id="quick-survey" class="bg-white p-10 my-10">

			<?php if ( $question['type'] == 'truefalse' ): ?>
                <simple-question
                        :post_id="<?php echo $post->ID ?>"
                        question="<?php echo $question['question'] ?>"
                        description="<?php echo $question['description'] ?>"
                        green="<?php echo $question['green'] ?>"
                        red="<?php echo $question['red'] ?>"
                        :feedback="<?php echo $question['feedback'] ?>"
                        :answers_given='<?php echo json_encode( $answers ) ?>'
                ></simple-question>
			<?php else: ?>

                <range-question
                        :post_id="<?php echo $post->ID ?>"
                        question="<?php echo $question['question'] ?>"
                        description="<?php echo $question['description'] ?>"
                        min="<?php echo $question['min'] ?>"
                        max="<?php echo $question['max'] ?>"
                        step="<?php echo $question['step'] ?>"
                        mintext="<?php echo $question['mintext'] ?>"
                        medtext="<?php echo $question['medtext'] ?>"
                        maxtext="<?php echo $question['maxtext'] ?>"
                        :feedback="<?php echo $question['feedback'] ?? 0 ?>"
                        :answers_given='<?php echo json_encode( $answers ) ?>'
                ></range-question>

			<?php endif; ?>
        </div>
		<?php

		return ob_get_clean();

	}

}
