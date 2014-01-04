<?php

// http://wordpress.stackexchange.com/questions/59024/add-html-dot-html-extension-to-custom-post-types

    add_action('rewrite_rules_array', 'rewrite_rules');
    function rewrite_rules($rules) {
        $new_rules = array();
        foreach (get_post_types() as $t)
            $new_rules[$t . '/(.+?)\.html?$'] = 'index.php?post_type=' . $t . '&name=$matches[1]';
        return $new_rules + $rules;
    }

    add_filter('post_link', 'custom_post_permalink');
    function custom_post_permalink ($post_link) {
        global $post;
        $type = get_post_type($post->ID);
        return home_url() . '/' . $type . '/' . $post->post_name . '.html';
    }

    add_filter('redirect_canonical', 'remove_redirect_canonical');
    function remove_redirect_canonical($redirect_url) {
        return false;
    }

    //add_action('wp', 'debug_rules');
    function debug_rules() {
        global $wp, $wp_query, $wp_rewrite;
        //$wp_rewrite->flush_rules();
        echo $wp->matched_rule . ' | ' . $wp_rewrite->rules[$wp->matched_rule];
        print_r($wp_rewrite->rules);
        exit();
    }

