<?php

namespace QuickSurvey;

class MetaBox {


	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'add_survey_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );

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

		$value = maybe_unserialize( get_post_meta( $post->ID, 'quick-survey-question', true ) );

		//echo var_dump($value);

		ob_start();
		?>
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
        <div class="mb-4">
            <label class="mb-3 font-semibold"><?php _e( 'Ja/Nein Frage zu diesem Beitrag', 'quick-survey' ) ?></label>
            <input type="text" class="block w-full border border-black" name="qsy[question]" value="<?php echo $value['question'] ?? '' ?>">
        </div>
        <div class="mb-4">
            <label class="mb-3 font-semibold"><?php _e( 'Beschreibung der Frage', 'quick-survey' ) ?></label>
            <textarea class="block w-full border border-black" name="qsy[description]"><?php echo trim( $value['description'] ?? '' ) ?></textarea>
        </div>
        <div class="mb-4">
            <label class="mb-3 font-semibold"><?php _e( 'Text auf dem grünen Button', 'quick-survey' ) ?></label>
            <input type="text" class="block w-full border border-black" name="qsy[green]" value="<?php echo $value['green'] ?? 'Ja' ?>">
        </div>
        <div class="mb-4">
            <label class="mb-3 font-semibold"><?php _e( 'Text auf dem rotem Button', 'quick-survey' ) ?></label>
            <input type="text" class="block w-full border border-black" name="qsy[red]" value="<?php echo $value['red'] ?? 'Nein' ?>">
        </div>
        <div class="mb-4">
            <label class="mb-3 font-semibold">
                <input type="checkbox" class="block w-full border border-black" name="qsy[feedback]" <?php checked( $value['feedback'] ?? '0', '1', true ) ?> value="1">
				<?php _e( 'Feedback aktivieren', 'quick-survey' ) ?></label>
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

		$data = maybe_serialize( $_POST['qsy'] );

		update_post_meta( $post_id, 'quick-survey-question', $data );
	}
}