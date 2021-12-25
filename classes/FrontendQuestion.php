<?php

namespace QuickSurvey;

class FrontendQuestion {


	public function __construct() {
		add_filter( 'the_content', [ $this, 'attach_survey' ] );
		add_filter( 'the_content', [ $this, 'add_id' ] );
		add_shortcode( 'quick-survey', [ $this, 'survey_shortcode' ] );
		add_filter( 'post_thumbnail_html', [ $this, 'override_post_thumbnail' ], 10, 5 );

	}


	public function override_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

		if ( ! is_front_page() || ! is_home() ) {
			return $html;
		}

		$renderable = $this->survey_renderable( $post_id );

        $meta = get_post_meta($post_id, 'quick-survey-attach');

        var_dump($meta);

		if ( ! $renderable ) {
			return $html;
		}

		ob_start();
		?>
        <div class="relative">
			<?php echo $html ?>
            <div class="absolute top-0 left-0 w-full h-full bg-white bg-opacity-90 z-50" style="z-index: 9999">
				<?php echo $this->render( '', null, 'no', $post_id ) ?>
            </div>

        </div>

		<?php
		return ob_get_clean();

	}


	public function add_id( $content ) {
		return '<div id="quick-survey">' . $content . '</div>';
	}


	public function survey_shortcode( $atts ) {

		return $this->render( '', $atts['question'] ?? false, $atts['newsletter'] ?? null );

	}


	public function attach_survey( $content ) {

		if ( has_shortcode( $content, 'quick-survey' ) ) {
			return $content;
		}

		return $this->render( $content );

	}

	public function survey_renderable( $post_id ) {

		if ( is_admin() ) {
			return false;
		}

		$attached = get_post_meta( $post_id, 'quick-survey-attach', true );
		$attached = maybe_unserialize( $attached );

		if ( ! is_array( $attached ) ) {
			return false;
		}

		if ( ! array_key_exists( 'survey', $attached ) ) {
			return false;
		}

		if ( ! get_post_type( $attached['survey'] ) == 'quick_survey' ) {
			return false;
		}


		$survey_meta = maybe_unserialize( get_post_meta( $attached['survey'], 'quick-survey-question', true ) );

		if ( $survey_meta['status'] == 'closed' ) {
			return false;
		}

		return [ 'attached' => $attached, 'survey_meta' => $survey_meta ];

	}


	public function render( $content = '', $question = false, $shortcode_newsletter = null, $post_id = false ) {

		if ( ! $post_id ) {
			global $post;
			$post_id = $post->ID ?? null;
		}

		$renderable = $this->survey_renderable( $post_id );

		if ( ! $renderable ) {
			return $content;
		}


		/**
		 * @var $attached
		 * @var $survey_meta
		 */
		extract( $renderable );

		$sponsor = maybe_unserialize( get_post_meta( $attached['survey'], 'quick-survey-sponsor', true ) );


		$answers = Answers::load_answers_by_survey( $attached['survey'] );


		if ( $question ) {
			foreach ( $survey_meta['questions'] as $id => $survey_question ) {
				if ( (int) $question != $id ) {
					unset( $survey_meta['questions'][ $id ] );
				}
			}

			$answers = json_decode( $answers );


			foreach ( $answers->answered as $index => $answer ) {
				if ( $answer != $question ) {
					unset( $answers->answered[ $index ] );
				}
			}

			$answers = json_encode( $answers );
		}

		$newsletter = $survey_meta['newsletter'];
		if ( $shortcode_newsletter != null ) {
			$newsletter = $shortcode_newsletter;
		}

		$is_frontpage = is_home() || is_front_page();

		$title = get_the_title( $attached['survey'] );

		$survey_id = $attached['survey'];

		$has_excerpt   = has_excerpt( $survey_id );
		$has_thumbnail = has_post_thumbnail( $survey_id );

		ob_start();

		?>
        <div class="p-5 <?php echo ( ! $is_frontpage ) ? 'bg-white' : 'h-full flex flex-col' ?>">
			<?php if ( ! $is_frontpage ): ?>
                <h3 class="text-xl font-semibold text-center mb-5"><?php echo $title ?></h3>
				<?php if ( $has_thumbnail && $has_excerpt ): ?>
                    <div class="flex justify-center">
                        <div class="flex space-x-10 items-center mb-5 pb-5 border-b border-gray-800">
                            <div class="rounded-full flex-none border border-primary-100 w-24 h-24">
								<?php echo get_the_post_thumbnail( $survey_id, 'thumbnail', [ 'class' => 'p-2 rounded-full' ] ); ?>
                            </div>
                            <div>
                                <p><?php echo get_the_excerpt( $survey_id ) ?></p>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endif; ?>

            <questions
                    :questions="<?php echo htmlentities( json_encode( $survey_meta['questions'] ) ) ?>"
                    :answers-given="<?php echo htmlentities( $answers ) ?>"
                    :survey="<?php echo $survey_id ?>"
                    layout="<?php echo $is_frontpage ? 'paginate' : $attached['layout'] ?>"
                    newsletter="<?php echo $newsletter ?>"
                    :is-frontpage="<?php echo $is_frontpage ? 'true' : 'false' ?>"
                    post-link="<?php echo get_the_permalink( $post_id ) ?>"
                    survey-title="<?php echo get_the_title( $survey_id ) ?>"
                    status="<?php echo $survey_meta['status'] ?>"
            ></questions>
            <div class="flex flex-col items-center pt-5 mt-auto">
				<?php if ( $sponsor['active'] == 'yes' ): ?>
                    <a href="<?php echo $sponsor['url'] ?>" class="flex items-center text-black">
                        <span class="mr-5"><?php echo $sponsor['text'] ?></span>
						<?php echo wp_get_attachment_image( $sponsor['logo'], 'thumbnail', null, [
							'class' => 'w-10 h-10',
							'style' => 'margin: 0 !important',
						] ) ?>
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


		return $content;
	}

}
