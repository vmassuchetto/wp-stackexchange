<?php

// http://wordpress.stackexchange.com/questions/56284/limit-the-number-of-pages-created-by-the-paging

    add_filter('pre_get_posts', 'limit_pages');
    function limit_pages($query) {

        $query->max_num_pages = 5;
        $query->found_posts = 5 * get_option('posts_per_page');
        if ($query->query_vars['paged'] >= 5) {
            $query->query_vars['paged'] = 5;
            $query->query['paged'] = 5;
        }

        return $query;
    }

