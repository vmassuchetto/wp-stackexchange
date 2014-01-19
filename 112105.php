<?php

// http://wordpress.stackexchange.com/questions/112105/sort-child-pages-on-admin

    // Use a query variable to control when to change the main admin query
    add_filter( 'query_vars', 'custom_admin_list_query_vars', 10, 1 );
    function custom_admin_list_query_vars( $vars ) {
        array_push( $vars, 'custom_admin_list_children' );
        return $vars;
    }

    add_action( 'pre_get_posts', 'custom_admin_pre_get_posts' );
    function custom_admin_pre_get_posts( $query ) {

        global $post_type;

        // Change query only if it's a user-triggered query in admin
        if ( ! is_admin()
            || 'page' != $post_type
            || $query->get( 'custom_admin_list_children' ) )
            return false;

        // Query only parents in date order
        $query->set( 'post_parent', 0 );
        $query->set( 'orderby', 'post_date' );
        $query->set( 'order', 'desc' );

    }

    // Query the children of the parents above
    add_action( 'wp', 'custom_admin_list_wp' );
    function custom_admin_list_wp() {

        global $post_type, $wp_query;

        if ( ! is_admin() || 'page' != $post_type )
            return false;

        $args = array(
            'post_type' => 'page',
            'numberposts' => -1,
            'custom_admin_list_children' => true,
            'meta_key' => 'section_id',
            'orderby' => 'meta_value_num',
            'order' => 'asc'
        );

        // Get children
        $children = array();
        for( $i = 0; $i < count( $wp_query->posts ); $i++ ) {
            $args['post_parent'] = $wp_query->posts[ $i ]->ID;
            $children[ $i ] = get_posts( $args );
        }

        // Flag as a children with a '--' in front of the title
        foreach( $children as &$c ) {
            if ( !empty( $c->post_title ) )
                $c->post_title = '&mdash;&nbsp;' . $c->post_title;
        }

        // Put everything together
        $posts = array();
        for( $i = 0; $i < count( $wp_query->posts ); $i++ ) {
            $posts[] = $wp_query->posts[ $i ];
            $posts = array_merge( $posts, $children[ $i ] );
        }

        $wp_query->posts = $posts;
        $wp_query->post_count = count( $posts );

    }

