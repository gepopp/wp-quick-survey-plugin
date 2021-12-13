<?php

namespace QuickSurvey;

class CustomPostType {


	public function __construct() {

		add_action( 'init', [$this, 'survey_post_type' ], 0 );

	}


	// Register Custom Post Type
	function survey_post_type() {

		$labels = array(
			'name'                  => _x( 'Quick Surveys', 'Post Type General Name', 'quick-survey' ),
			'singular_name'         => _x( 'Quick Survey', 'Post Type Singular Name', 'quick-survey' ),
			'menu_name'             => __( 'Umfrage', 'quick-survey' ),
			'name_admin_bar'        => __( 'Umfragen', 'quick-survey' ),
			'archives'              => __( 'Umfragen', 'quick-survey' ),
			'attributes'            => __( 'Umfragen Attribute', 'quick-survey' ),
			'parent_item_colon'     => __( '', 'quick-survey' ),
			'all_items'             => __( 'Alle Umfragen', 'quick-survey' ),
			'add_new_item'          => __( 'Neue Umfrage', 'quick-survey' ),
			'add_new'               => __( 'Hinzufügen', 'quick-survey' ),
			'new_item'              => __( 'Neue Umfrage', 'quick-survey' ),
			'edit_item'             => __( 'Umfrage bearbeiten', 'quick-survey' ),
			'update_item'           => __( 'Umfrage speichern', 'quick-survey' ),
			'view_item'             => __( 'Umfrage ansehen', 'quick-survey' ),
			'view_items'            => __( 'Umfrage ansehen', 'quick-survey' ),
			'search_items'          => __( 'Suchen', 'quick-survey' ),
			'not_found'             => __( 'Nichts gefunden', 'quick-survey' ),
			'not_found_in_trash'    => __( 'Nichts im Papierkorb gefunden', 'quick-survey' ),
			'featured_image'        => __( 'Umfrage Bild', 'quick-survey' ),
			'set_featured_image'    => __( 'Bild setzten', 'quick-survey' ),
			'remove_featured_image' => __( 'Bild entfernen', 'quick-survey' ),
			'use_featured_image'    => __( 'Als Umfragebild verwenden', 'quick-survey' ),
			'insert_into_item'      => __( 'Einfügen', 'quick-survey' ),
			'uploaded_to_this_item' => __( 'Zu dieser Umfrage hoch geladen', 'quick-survey' ),
			'items_list'            => __( 'Umfragen Liste', 'quick-survey' ),
			'items_list_navigation' => __( 'Listennavigation', 'quick-survey' ),
			'filter_items_list'     => __( 'Liste filtern', 'quick-survey' ),
		);
		$args = array(
			'label'                 => __( 'Quick Survey', 'quick-survey' ),
			'description'           => __( 'Survey Questions attached to posts', 'quick-survey' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'thumbnail' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
		);
		register_post_type( 'quick_survey', $args );

	}


}