<?php

namespace QuickSurvey;

class FrontendQuestion {


	public function __construct() {
		add_filter( 'the_content', [ $this, 'render_question' ] );
	}


	function render_question( $content ) {

		global $post;

		$question = maybe_unserialize( get_post_meta( $post->ID, 'quick-survey-question', true ) );

        $status = $question['status'] ?? 'closed';

        if( $status == 'closed') return $content;

		if ( empty( $question ) || has_shortcode( $content, 'quick-survey' ) ) {
			return $content;
		}

		$answers = Answers::answers_for_chart($post->ID);

		ob_start();
		?>
        <div id="quick-survey" class="bg-white p-10 my-10">
            <simple-question
                    :post_id="<?php echo $post->ID ?>"
                    question="<?php echo $question['question'] ?>"
                    description="<?php echo $question['description'] ?>"
                    green="<?php echo $question['green'] ?>"
                    red="<?php echo $question['red'] ?>"
                    :feedback="<?php echo $question['feedback'] ?>"
                    :answers_given='<?php echo json_encode($answers) ?>'
            ></simple-question>
        </div>
		<?php

		$content .= ob_get_clean();

		return $content;

	}

}
