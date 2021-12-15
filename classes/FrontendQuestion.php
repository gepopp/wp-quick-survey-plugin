<?php

namespace QuickSurvey;

class FrontendQuestion {


	public function __construct() {
		add_filter( 'the_content', [ $this, 'attach_survey' ] );
		add_shortcode( 'quick-survey', [ $this, 'survey_shortcode' ] );
	}

	function attach_survey( $content ) {


		global $post;

		$attached = get_post_meta( $post->ID, 'quick-survey-attach', true );
		$attached = maybe_unserialize( $attached );
        if(!is_array($attached)) $attached = [];

		if ( array_key_exists( 'survey', $attached )
		     && get_post_type( $attached['survey'] ) == 'quick_survey'
		     && ! has_shortcode( $content, 'quick-survey' ) ) {


			$survey = get_post( $attached['survey'] );

			$survey_meta = maybe_unserialize( get_post_meta( $attached['survey'], 'quick-survey-question', true ) );


			$sponsor = maybe_unserialize( get_post_meta( $attached['survey'], 'quick-survey-sponsor', true ) );

			if ( $survey_meta['status'] == 'closed' ) {
				return $content;
			}

			ob_start();
			?>
            <div class="my-10 p-5 bg-white" id="quick-survey">

                <div class="flex justify-center">
                    <h3 class="text-xl font-semibold mb-5"><?php echo get_the_title( $attached['survey'] ) ?></h3>
                </div>
                <hr>
				    <questions
                            :questions="<?php echo htmlentities( json_encode($survey_meta['questions'])) ?>"
                            :survey="<?php echo $attached['survey'] ?>"
                            layout="<?php echo $attached['layout'] ?>"
                            newsletter="<?php echo $survey_meta['newsletter'] ?>"
                    ></questions>
                <hr>
                <div class="flex justify-center pt-5">
					<?php if ( $sponsor['active'] == 'yes' ): ?>
                        <a href="<?php echo $sponsor['url'] ?>" class="flex items-center text-black space-x-5">
                            <span><?php echo $sponsor['text'] ?></span>
                            <div>
								<?php echo wp_get_attachment_image( $sponsor['logo'], 'thumbnail', null, [ 'class' => 'w-10 h-10' ] ) ?>
                            </div>
                        </a>
					<?php else: ?>
                        <div class="flex items-center text-black">
                            <span>made with</span>
                            <div class="px-2">
                                <svg class="w-6 h-6" fill="red" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span>by <a href="https://poppgerhard.at">poppgerhard</a> </span>
                        </div>
					<?php endif; ?>
                </div>
            </div>
			<?php
			$content .= ob_get_clean();
		}


		return $content;

	}

}
