<?php

// http://wordpress.stackexchange.com/questions/59827/using-wp-rewrite-to-rewrite-custom-urls-in-this-scenario

    add_action('rewrite_rules_array', 'rewrite_rules');
    function rewrite_rules($rules) {
        $new_rules = array(
            'product/([0-9]+)/?$' => 'index.php?product=$matches[1]'
        );
        return $rules + $new_rules;
    }

    add_action('query_vars', 'add_query_vars');
    function add_query_vars($vars) {
        array_push($vars, 'product');
        return $vars;
    }

    add_action('template_redirect', 'custom_template_redirect');
    function custom_template_redirect() {
        global $wp_query, $post;
        if (get_query_var('product')) {
            include('product_template.php');
            exit();
        }
    }

    add_action('wp_loaded', 'flush_rules');
    function flush_rules() {
        global $wp_rewrite;
        if (!$rules = get_option('rewrite_rules'))
            $rules = array();
        $new_rules = rewrite_rules(array());
        foreach ($new_rules as $k => $v) {
            if (!isset($rules[$k])) {
                $wp_rewrite->flush_rules();
                break;
            }
        }
    }

    add_action('wp', 'debug_rules');
    function debug_rules() {
        global $wp, $wp_query, $wp_rewrite;
        echo $wp->matched_rule . ' | ' . $wp_rewrite->rules[$wp->matched_rule];
        print_r($wp_rewrite->rules);
        exit();
    }

