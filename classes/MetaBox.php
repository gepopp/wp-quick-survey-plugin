<?php

namespace QuickSurvey;

class MetaBox {


	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'add_survey_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );
		add_filter( 'get_post_metadata', [ $this, 'load_defaults' ], 10, 4 );

	}


	public function load_defaults( $value, $post_id, $meta_key, $single ) {

		if ( $meta_key != 'quick-survey-question' ) {
			return null;
		}

		$defaults = [
			'type'     => 'truefalse',
			'status'   => 'closed',
			'question' => '',
            'feedback' => 'Y'
		];

		global $wpdb;
		$sql          = sprintf( 'SELECT meta_value FROM %s WHERE post_id = %d AND meta_key = "%s" LIMIT 1', $wpdb->prefix . 'postmeta', $post_id, $meta_key );
		$exist = $wpdb->get_var( $sql );
		$value = maybe_unserialize( unserialize( $exist ));

		if ( ! is_array( $value ) ) {
			$value = [];
		}

		$parsed = array_merge( $defaults, $value );

		return $parsed;

	}

	public function add_survey_meta_box( $post_type ) {
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

		$value = get_post_meta( $post->ID, 'quick-survey-question' );

		ob_start();
		?>
        <script>
            jQuery(document).ready(function ($) {

                var checked = $('input[name="qsy[type]"]:checked').val();

                var set = $('.swap');

                $(set).each(function (index, el) {
                    if ($(el).attr('id') !== checked) {
                        $(el).hide();
                    } else {
                        $(el).show();
                    }
                });

                $('input[type="radio"]').on('click', function (e) {
                    var type = $('input[name="qsy[type]"]:checked').val();

                    var set = $('.swap');

                    $(set).each(function (index, el) {
                        if ($(el).attr('id') !== type) {
                            $(el).hide();
                        } else {
                            $(el).show();
                        }
                    });
                });
            });
        </script>

		<?php //echo var_dump($value) ?>

        <div id="quick-survey-admin" class="p-10">
            <label class="mb-5 font-semibold"><?php _e( 'Umfrage Status', 'quick-survey' ) ?></label>
            <div class="flex space-x-5 mb-4">
                <label>
                    <input type="radio" name="qsy[status]" value="open" <?php checked( $value['status'] ?? 'closed', 'open', true ) ?>> <?php _e( 'Offen', 'quick-survey' ) ?>
                </label>
                <label>
                    <input type="radio" name="qsy[status]" value="closed" <?php checked( $value['status'] ?? 'closed', 'closed', true ) ?>> <?php _e( 'Geschlossen', 'quick-survey' ) ?>
                </label>
            </div>


            <label class="mb-5 font-semibold"><?php _e( 'Frage Typ', 'quick-survey' ) ?></label>
            <div class="flex space-x-5 mb-4">
                <label>
                    <input type="radio" name="qsy[type]" value="truefalse" <?php checked( $value['type'] ?? 'truefalse', 'truefalse', true ) ?>> <?php _e( 'JA/NEIN', 'quick-survey' ) ?>
                </label>
                <label>
                    <input type="radio" name="qsy[type]" value="range" <?php checked( $value['type'] ?? 'range', 'range', true ) ?>> <?php _e( 'SKALA', 'quick-survey' ) ?>
                </label>
            </div>
            <div class="mb-4">
                <label class="mb-3 font-semibold"><?php _e( 'Frage zu diesem Beitrag', 'quick-survey' ) ?></label>
                <input type="text" class="block w-full border border-black" name="qsy[question]" value="<?php echo $value['question'] ?? '' ?>">
            </div>
            <div class="mb-4">
                <label class="mb-3 font-semibold"><?php _e( 'Beschreibung der Frage', 'quick-survey' ) ?></label>
                <textarea class="block w-full border border-black" name="qsy[description]"><?php echo trim( $value['description'] ?? '' ) ?></textarea>
            </div>

            <div id="truefalse" class="swap">
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Text auf dem grünen Button', 'quick-survey' ) ?></label>
                    <input type="text" class="block w-full border border-black" name="qsy[green]" value="<?php echo $value['green'] ?? 'Ja' ?>">
                </div>
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Text auf dem rotem Button', 'quick-survey' ) ?></label>
                    <input type="text" class="block w-full border border-black" name="qsy[red]" value="<?php echo $value['red'] ?? 'Nein' ?>">
                </div>
            </div>

            <div id="range" class="swap">
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Skala Mindestwert', 'quick-survey' ) ?></label>
                    <input type="number" class="block w-full border border-black" name="qsy[min]" value="<?php echo $value['min'] ?? 1 ?>">
                </div>
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Skala Maximalwert', 'quick-survey' ) ?></label>
                    <input type="number" class="block w-full border border-black" name="qsy[max]" value="<?php echo $value['max'] ?? 10 ?>">
                </div>
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Auswahl Schrittweite', 'quick-survey' ) ?></label>
                    <input type="number" class="block w-full border border-black" name="qsy[step]" value="<?php echo $value['step'] ?? 1 ?>">
                </div>
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Beschriftung Minimalwert', 'quick-survey' ) ?></label>
                    <input type="text" class="block w-full border border-black" name="qsy[mintext]" value="<?php echo $value['mintext'] ?? 'Ich bin nicht der Meinung' ?>">
                </div>
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Beschriftung Mittelwert', 'quick-survey' ) ?></label>
                    <input type="text" class="block w-full border border-black" name="qsy[medtext]" value="<?php echo $value['medtext'] ?? 'Keine Meinung' ?>">
                </div>
                <div class="mb-4">
                    <label class="mb-3 font-semibold"><?php _e( 'Beschriftung Maximalwert', 'quick-survey' ) ?></label>
                    <input type="text" class="block w-full border border-black" name="qsy[maxtext]" value="<?php echo $value['maxtext'] ?? 'Ich bin voll der Meinung' ?>">
                </div>
            </div>


            <div class="mb-4">

                <label class="mb-5 font-semibold"><?php _e( 'Feedback aktivieren', 'quick-survey' ) ?></label>
                <div class="flex space-x-5 mb-4">
                    <label>
                        <input type="radio" name="qsy[feedback]" value="y" <?php checked( $value['feedback'] ?? 'y', 'y', true ) ?>> <?php _e( 'JA', 'quick-survey' ) ?>
                    </label>
                    <label>
                        <input type="radio" name="qsy[feedback]" value="n" <?php checked( $value['feedback'] ?? 'n', 'n', true ) ?>> <?php _e( 'NEIN', 'quick-survey' ) ?>
                    </label>
                </div>
            </div>


			<?php $answers = Answers::get_asnwers( $post->ID ) ?>


            <div class="mt-10">
                <h3 class="text-xl font-semibold">Antworten</h3>
                <div>
					<?php foreach ( $answers as $answer ): ?>
                        <div class="grid grid-cols-6 gap-3 py-3 border-b border-black odd:bg-gray-300">
                            <div class="px-2"><?php echo $answer->id ?></div>
                            <div class="px-2"><?php echo $answer->user_ip ?></div>
                            <div class="px-2"><?php echo $answer->user_email ?></div>
                            <div class="px-2"><?php echo $answer->answer ?></div>
                            <div class="px-2"><?php echo $answer->feedback ?></div>
                            <div class="px-2"><?php echo $answer->created_at ?></div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>

		<?php
		echo ob_get_clean();
	}

	public function save( $post_id ) {

		$data = $_POST['qsy'];

		if ( $data['type'] == 'range' ) {
			unset( $data['green'] );
			unset( $data['red'] );
		}

		if ( $data['type'] == 'truefalse' ) {
			unset( $data['min'] );
			unset( $data['max'] );
			unset( $data['step'] );
			unset( $data['maxtext'] );
			unset( $data['mintext'] );
			unset( $data['medtext'] );
		}


		update_post_meta( $post_id, 'quick-survey-question', maybe_serialize( $data ) );
	}
}