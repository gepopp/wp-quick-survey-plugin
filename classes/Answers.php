<?php

namespace QuickSurvey;

use function Clue\StreamFilter\fun;

class Answers {


	public function __construct() {

		add_action( 'wp_ajax_qsy_save_answer', [ $this, 'save_answer' ] );
		add_action( 'wp_ajax_nopriv_qsy_save_answer', [ $this, 'save_answer' ] );

		add_action( 'wp_ajax_qsy_load_survey_answers', [ $this, 'load_survey_answers' ] );
		add_action( 'wp_ajax_nopriv_qsy_load_survey_answers', [ $this, 'load_survey_answers' ] );

		add_action( 'wp_ajax_qsy_delete_answer', [ $this, 'qsy_delete_answer' ] );

	}


	public function qsy_delete_answer(){

		global $wpdb;
		$delete = $wpdb->delete($wpdb->prefix . 'qsy_answers', ['id' => $_POST['id']]);

		if($delete){
			wp_die('delted');
		}else{
			wp_die('not deleted', 400);
		}

	}


	public static function load_all_answers($survey_id){

		global $wpdb;
		return $wpdb->get_results(sprintf('SELECT * FROM %s WHERE survey_id = %d', $wpdb->prefix . 'qsy_answers', $survey_id));
	}

	public function load_survey_answers(){
		wp_die( self::load_answers_by_survey($_POST['survey_id']) );
	}


	public static function load_answers_by_survey( $survey_id ) {


		global $wpdb;

		$answers_raw = self::load_all_answers($survey_id);
		$answers = [];

		foreach ($answers_raw as $answer){

			$key = $answer->question_id . '_' . sanitize_title($answer->answer);

			if(!array_key_exists( $key, $answers)){
				$answers[$key] = (object) [
					'answer' => $answer->answer,
					'count'  => 0,
					'question_id' => $answer->question_id
				];
			}

			foreach ($answers as &$a){
				if($answer->question_id == $a->question_id && $answer->answer == $a->answer){
					$a->count++;
				}
			}
		}

		$answers = array_values( $answers );

		$sql = sprintf( 'SELECT `question_id` FROM %s WHERE survey_id = %d AND user_ip = "%s" GROUP BY question_id', $wpdb->prefix . 'qsy_answers', $survey_id, self::get_ip() );


		$answered = $wpdb->get_col( $sql );

		$results = [
			'answers' => $answers,
			'answered' => $answered,
		];


		if ( is_wp_error( $results ) ) {
			wp_die( var_dump( $results ), 400 );
		}

		return json_encode( $results );

	}


	public function save_answer() {

		$survey_id   = sanitize_text_field( $_POST['survey_id'] );
		$question_id = sanitize_text_field( $_POST['question_id'] );

		$user = wp_get_current_user();

		global $wpdb;

		$answer = [
			'survey_id'   => $survey_id,
			'question_id' => $question_id,
			'user_id'     => ! $user ? null : $user->ID,
			'user_ip'     => self::get_ip(),
			'user_email'  => ! $user ? null : $user->user_email,
			'answer'      => sanitize_text_field( $_POST['answer'] ),
		];

		$insert = $wpdb->insert( $wpdb->prefix . 'qsy_answers', $answer );

		if ( $insert ) {
			wp_die( json_encode( $answer ) );
		} else {
			wp_die( 'not saveable', 400 );
		}
	}


	public static function get_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return apply_filters( 'wpb_get_ip', $ip );
	}

}