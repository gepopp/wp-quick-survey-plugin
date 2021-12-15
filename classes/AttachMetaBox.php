<?php

namespace QuickSurvey;

class AttachMetaBox {


	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'add_survey_attach_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );

	}


	public function add_survey_attach_meta_box( $post_type ) {

		if ( $post_type != 'post' ) {
			return;
		}


		add_meta_box(
			'quick_sruvey_attach_meta_box',
			__( 'Umfrage Verknüpfen', 'quick-survey' ),
			[ $this, 'render_attach_meta_box_content' ],
			$post_type,
			'side',
			'high'
		);
	}


	public function render_attach_meta_box_content( $post ) {

		$value = get_post_meta( $post->ID, 'quick-survey-attach', true );

		$value = maybe_unserialize( $value );

		//       var_dump($value);

		$surveys = get_posts( [ 'post_type' => 'quick_survey' ] );

		ob_start();
		?>

        <div class="mb-5">
            <label class="font-semibold"><?php echo __( 'Umfrage wählen', 'quick-survey' ) ?></label>
            <select name="qsy-attach[survey]" class="block">
                <option value="null">Bitte wählen</option>
				<?php foreach ( $surveys as $survey ): ?>
                    <option value="<?php echo $survey->ID ?>" <?php selected( $value['survey'] ?? null, $survey->ID, true ) ?>><?php echo $survey->post_title ?></option>
				<?php endforeach; ?>
            </select>
        </div>
        <div class="mb-5">
            <label class="font-semibold block"><?php echo __( 'Vorschaubild ersetzen', 'quick-survey' ) ?></label>
            <div class="flex space-x-5">
                <label>
                    <input type="radio" name="qsy-attach[replacepreview]" value="yes" <?php checked( $value['replacepreview'] ?? 'yes', 'yes', true ) ?>>&nbsp;
                    Ja
                </label>
                <label>
                    <input type="radio" name="qsy-attach[replacepreview]" value="no" <?php checked( $value['replacepreview'] ?? 'yes', 'no', true ) ?>>&nbsp;
                    Nein
                </label>
            </div>
        </div>
        <div class="mb-5">
            <label class="font-semibold block"><?php echo __( 'Layout', 'quick-survey' ) ?></label>
            <div class="flex space-x-5">
                <label>
                    <input type="radio" name="qsy-attach[layout]" value="paginate" <?php checked( $value['layout'] ?? 'paginate', 'paginate', true ) ?>>&nbsp;
                    Seiten
                </label>
                <label>
                    <input type="radio" name="qsy-attach[layout]" value="list" <?php checked( $value['layout'] ?? 'paginate', 'list', true ) ?>>&nbsp;
                    Liste
                </label>
            </div>
        </div>


		<?php
		echo ob_get_clean();
	}

	public function save( $post_id ) {

		if ( ! isset( $_POST['qsy-attach'] ) ) {
			return;
		}

		update_post_meta( $post_id, 'quick-survey-attach', maybe_serialize( $_POST['qsy-attach'] ) );

	}

}