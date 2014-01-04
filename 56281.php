<?php

// http://wordpress.stackexchange.com/questions/56281/do-not-allow-to-search-certain-words/56286

    add_action('wp', 'check_search');
    function check_search() {

        global $wp_query;

        if (!$s = get_search_query())
            return false;

        if (preg_match('/news/', $s)) {
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit();
        }

    }
