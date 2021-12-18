<?php

namespace QuickSurvey;

class SurveyIframe {


	public function __construct() {

		add_action( 'init', [ $this, 'rewrites_init' ] );
		add_filter( 'query_vars', [ $this, 'query_vars' ] );
		add_filter( 'template_include', [ $this, 'survey_iframe_template' ], 99 );


	}

	public function survey_iframe_template( $template ) {

		if ( get_query_var( 'survey_id' ) ) {
			$survey_id   = (int) get_query_var( 'survey_id' );
			$survey      = get_posts( [ 'post_type' => 'quick_survey', 'post__in' => [ $survey_id ] ] );
			$survey_meta = maybe_unserialize( get_post_meta( $survey_id, 'quick-survey-question', true ) );
			$sponsor = maybe_unserialize( get_post_meta( $survey_id, 'quick-survey-sponsor', true ) );
			$answers = Answers::load_answers_by_survey( $survey_id );

			ob_start();
			?>

            <!DOCTYPE html>
            <html <?php language_attributes(); ?>>
            <head>
                <meta charset="<?php bloginfo( 'charset' ); ?>">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="profile" href="http://gmpg.org/xfn/11">
                <meta name="keywords" content="immobilienredaktion, immobilienmagazin, Immobilien Redaktion, Immobilien Magazin, Wien, Immobilien, Immoflash, ImmoWelt, International, Investment, Markt, Mieten, Wohnen, Österreich">
                <link rel="icon" type="image/png" href="<?= get_stylesheet_directory_uri() . '/assets/images/favicon.png'; ?>">
                <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
				<?php wp_head(); ?>
                <script>
                    window.ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
                </script>

                <script async src="https://www.googletagmanager.com/gtag/js?id=UA-137371315-1"></script>
                <script>
                    window.dataLayer = window.dataLayer || [];

                    function gtag() {
                        dataLayer.push(arguments);
                    }

                    gtag('js', new Date());

                    gtag('config', 'UA-137371315-1');
                </script>
            </head>
            <body itemscope itemtype="https://schema.org/WebPage">
            <div class="p-10 bg-white" id="quick-survey">
                <div class="p-5 h-full flex flex-col">
                    <h3 class="text-xl font-semibold text-center mb-5"><?php echo get_the_title( $survey_id ) ?></h3>
					<?php if ( has_post_thumbnail( $survey_id ) && has_excerpt( $survey_id ) ): ?>
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
                </div>
                <questions
                        :questions="<?php echo htmlentities( json_encode( $survey_meta['questions'] ) ) ?>"
                        :answers-given="<?php echo htmlentities(  $answers  ) ?>"
                        :survey="<?php echo $survey_id ?>"
                        layout="paginate"
                        newsletter="yes"
                        :is-frontpage="true"
                        post-link="<?php echo home_url() ?>"
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
            </div>


			<?php wp_footer(); ?>
            </body>
            </html>
			<?php
			echo ob_get_clean();
		} else {
			return $template;
		}

	}


	function rewrites_init() {

		add_rewrite_rule(
			'surveyframe/([0-9]+)/?$',
			'index.php?pagename=surveyframe&survey_id=$matches[1]',
			'top' );
	}

	function query_vars( $query_vars ) {
		$query_vars[] = 'survey_id';

		return $query_vars;
	}


}