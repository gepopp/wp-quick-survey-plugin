<?php

namespace QuickSurvey;

class Answers {


	public function __construct() {

		add_action( 'wp_ajax_qsy_save_answer', [ $this, 'save_answer' ] );
		add_action( 'wp_ajax_nopriv_qsy_save_answer', [ $this, 'save_answer' ] );

		add_action( 'wp_ajax_qsy_save_answer_feedback', [ $this, 'save_answer_feedback' ] );
		add_action( 'wp_ajax_nopriv_qsy_save_answer_feedback', [ $this, 'save_answer_feedback' ] );

		add_action( 'wp_ajax_qsy_get_answers_count', [ $this, 'get_asnwers_count' ] );
		add_action( 'wp_ajax_nopriv_qsy_get_answers_count', [ $this, 'get_asnwers_count' ] );

	}

	public function get_asnwers_count(){
		$post_id = sanitize_text_field($_POST['post_id']);

		wp_die(json_encode(self::answers_for_chart($post_id)));
	}


	public static function get_asnwers($post_id){
		global $wpdb;
		$sql = sprintf('SELECT * FROM %s WHERE post_id = %d', $wpdb->prefix . 'qsy_answers', $post_id);

		return $wpdb->get_results($sql);

	}


	public static function answers_for_chart($post_id){

		$answers = self::get_asnwers($post_id);

		$results = [];
		foreach ( $answers as $answer ) {

			$key = (string) $answer->answer;

			if(!array_key_exists($key, $results)){
				$results[$key] = 0;
			}
			$results[$key]++;

		}
		return $results;

	}


	public function save_answer_feedback() {

		global $wpdb;

		$user = wp_get_current_user();
		if ( $user ) {
			$email = $user->user_email;
		} else {
			$email = sanitize_email( $_POST['email'] );
		}

		$update = $wpdb->update( $wpdb->prefix . 'qsy_answers', [
			'feedback'   => sanitize_text_field( $_POST['feedback'] ),
			'user_email' => $email,
		], [ 'id' => sanitize_text_field( $_POST['answer_id'] ) ] );

		if(is_wp_error($update)){
			wp_die('not saveable', 400);
		}else{
			wp_die('success');
		}

	}


	public function save_answer() {

		$post_id = sanitize_text_field( $_POST['post_id'] );

		$user = wp_get_current_user();

		$ip = $this->get_ip();

		$question_id = $this->get_question_id( $post_id );

		global $wpdb;

		$insert = $wpdb->insert( $wpdb->prefix . 'qsy_answers', [
			'post_id'     => $post_id,
			'question_id' => $question_id,
			'user_id'     => ! $user ? null : $user->ID,
			'user_ip'     => $this->get_ip(),
			'user_email'  => ! $user ? null : $user->user_email,
			'answer'      => sanitize_text_field( $_POST['answer'] ),
		] );

		if ( $insert ) {
			wp_die( $wpdb->insert_id );
		} else {
			wp_die( 'not saveable', 400 );
		}


	}


	public function get_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return apply_filters( 'wpb_get_ip', $ip );
	}


	public function get_question_id( $post_id ) {
		global $wpdb;
		$sql = sprintf( 'SELECT meta_id FROM %spostmeta WHERE post_id = %d AND meta_key = "%s" LIMIT 1', $wpdb->prefix, $post_id, 'quick-survey-question' );
		return $wpdb->get_var( $sql );
	}

}