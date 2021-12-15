<?php

namespace QuickSurvey;

class SponsorMetaBox {


	public function __construct() {

		add_action( 'add_meta_boxes', [ $this, 'add_survey_sponsor_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );

	}


	public function add_survey_sponsor_meta_box( $post_type ) {

		if($post_type != 'quick_survey') return;


		add_meta_box(
			'quick_sruvey_sponsor_meta_box',
			__( 'Survey Sponsor', 'quick-survey' ),
			[ $this, 'render_sponsor_meta_box_content' ],
			$post_type,
			'advanced',
			'high'
		);
	}


	public function render_sponsor_meta_box_content( $post ) {

		$value = get_post_meta( $post->ID, 'quick-survey-sponsor', true );

		$value = maybe_unserialize( $value );

//        var_dump($value);

		ob_start();
		?>
        <div id="quick-survey-admin-sponsor">
            <div class="p-10">
                <label class="mb-3 font-semibold"><?php _e( 'Sponsor Logo anzeigen', 'quick-survey' ) ?></label>
                <div class="block w-full mb-10">
                    <label>
                        <input type="radio" name="qsy-sponsor[active]" value="no" <?php checked( $value['active'] ?? 'no', 'no', true ) ?>>&nbsp;
						<?php _e( 'Nicht anzeigen', 'quick-survey' ) ?>
                    </label>
                    <label>
                        <input type="radio" name="qsy-sponsor[active]" value="yes" <?php checked( $value['active'] ?? 'no', 'yes', true ) ?>>&nbsp;
						<?php _e( 'Anzeigen', 'quick-survey' ) ?>
                    </label>
                </div>

                <label class="mb-3 font-semibold"><?php _e( 'Sponsor Logo', 'quick-survey' ) ?></label>

				<?php
				if ( $image = wp_get_attachment_image_src( $value['logo'] ?? null ) ): ?>

                    <a href="#" class="misha-upl">
                        <div class="p-3 bg-primary-100 rounded-full">
                            <img src="<?php echo $image[0] ?>" class="w-10 h-10"/>
                        </div>
                    </a>
                    <a href="#" class="misha-rmv underline"><?php _e( 'Bild entfernen', 'quick-survey' ) ?></a>
                    <input type="hidden" name="qsy-sponsor[logo]" value="<?php echo $value['logo'] ?? 0 ?>">

				<?php else: ?>

                    <a href="#" class="misha-upl underline"><?php _e( 'Logo auswählen', 'quick-survey' ) ?></a>
                    <a href="#" class="misha-rmv underline" style="display:none"><?php _e( 'Bild entfernen', 'quick-survey' ) ?></a>
                    <input type="hidden" name="qsy-sponsor[logo]" value="">

				<?php endif; ?>
                <div class="mt-10">
                    <label class="mb-3 font-semibold"><?php _e( 'Begleittext', 'quick-survey' ) ?></label>
                    <input type="text" class="block w-full border border-black" name="qsy-sponsor[text]" value="<?php echo $value['text'] ?? 'Powered by' ?>">
                </div>
                <div class="mt-10">
                    <label class="mb-3 font-semibold"><?php _e( 'Sponsor Link', 'quick-survey' ) ?></label>
                    <input type="url" class="block w-full border border-black" name="qsy-sponsor[url]" value="<?php echo $value['url'] ?? '' ?>">
                </div>


            </div>
            <script>
                jQuery(function ($) {

                    // on upload button click
                    $('body').on('click', '.misha-upl', function (e) {

                        e.preventDefault();

                        var button = $(this),
                            custom_uploader = wp.media({
                                title: 'Insert image',
                                library: {
                                    // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                                    type: 'image'
                                },
                                button: {
                                    text: 'Use this image' // button label text
                                },
                                multiple: false
                            }).on('select', function () { // it also has "open" and "close" events
                                var attachment = custom_uploader.state().get('selection').first().toJSON();
                                button.html('<img src="' + attachment.url + '">').next().show().next().val(attachment.id);
                            }).open();

                    });

                    // on remove button click
                    $('body').on('click', '.misha-rmv', function (e) {

                        e.preventDefault();

                        var button = $(this);
                        button.next().val(''); // emptying the hidden field
                        button.hide().prev().html('Upload image');
                    });

                });
            </script>
        </div>
		<?php
		echo ob_get_clean();
	}

	public function save( $post_id ) {

		if ( ! isset( $_POST['qsy-sponsor'] ) ) {
			return;
		}

		update_post_meta( $post_id, 'quick-survey-sponsor', maybe_serialize( $_POST['qsy-sponsor'] ) );

	}

}