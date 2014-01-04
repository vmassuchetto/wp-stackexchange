<?php

// http://wordpress.stackexchange.com/questions/59828/how-do-you-query-posts-with-nothing-in-common/59830#59830

    add_action('wp', 'test_multiple_posts');
    function test_multiple_posts() {
        $query = new WP_Query(array(
            'post__in' => array(23,18,2,199,6,8)
        ));
        while ( $query->have_posts() ) {
            $query->the_post();
            the_title();
        }
        print_r($query);
    }
