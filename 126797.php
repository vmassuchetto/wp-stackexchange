<?php

// http://wordpress.stackexchange.com/questions/126797/distributing-and-packaging-plugins

    add_action( 'query_vars', 'add_query_vars' );
    function add_query_vars( $vars ) {
        array_push( $vars, 'form_id' );
        return $vars;
    }

    add_action( 'rewrite_rules_array', 'add_rewrite_rules' );
    function add_rewrite_rules( $rules ) {
        $new_rules = array(
            'forms/([^/]+)/?$' => 'index.php?form_id=$matches[1]'
        );
        return $new_rules + $rules;
    }

    add_action( 'wp', 'custom_wp_query' );
    function custom_wp_query( $wp ) {

        // Don't do anything for other queries
        if ( ! $form_id = get_query_var('form_id') )
            return false;

        global $wp_query;

        // Let's respond this request with this function
        $func = 'form_' . str_replace( '-', '_', $form_id );

        // Throw a 404 if there's no function to deal with this request
        if ( ! function_exists( $func ) ) {
            $wp_query->is_404 = true;
            return false;
        }

        // Set as a valid query for this case
        $wp_query->is_404 = false;
        $wp_query->is_single = true;
        $wp_query->found_posts = 1;

        // Call the function
        $post = call_user_func( $func );

        // Stick this post into the query
        $wp_query->posts = array( $post );
        $wp_query->post = $post;

    }

    function form_some_form() {
        return (object) array(

            // Put a negative ID as they don't exist
            'ID' => rand() * -1,

            // What matters for us
            'post_title' => 'Form title',
            'post_content' => 'Some post content (the form itself, presumably)',

            // It is important to maintain the same URL structure of 'add_rewrite_rules',
            // otherwise wrong links will be displayed in the template
            'post_name' => 'forms/' . get_query_var( 'form_id' ),
            'post_guid' => home_url( '?form_id=' . get_query_var( 'form_id' ) ),

            // Admin
            'post_author' => 1,

            // Straighforward stuff
            'post_date' => date( 'mysql' ),
            'post_date_gmt' => date( 'mysql' ),
            'post_status' => 'publish',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        );
    }
