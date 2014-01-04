<?php

// http://wordpress.stackexchange.com/questions/59462/trying-to-display-category-specific-comments-in-sidebar

    add_action('wp', 'display_category_comments');
    function display_category_comments() {

        global $cat;
        if (!$category_id = intval($cat))
            return false;

        $comments = get_category_comments($category_id);
        ?>
        <ul>
        <?php foreach ($comments as $c) : $post = get_post($i = $c->comment_post_ID); ?>
            <li>
                <?php echo $c->comment_author; ?> - <a href="<?php echo get_permalink($post->ID); ?>" title="Permalink to <?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php
    }

    function get_category_comments($category_id, $limit = 5) {

        global $wpdb;

        $sql = "
            SELECT {$wpdb->comments}.comment_ID
            FROM
                {$wpdb->comments},
                {$wpdb->posts},
                {$wpdb->term_taxonomy},
                {$wpdb->term_relationships}
            WHERE 1=1
                AND {$wpdb->comments}.comment_post_ID = {$wpdb->posts}.ID
                AND {$wpdb->term_relationships}.object_id = {$wpdb->posts}.ID
                AND {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
                AND {$wpdb->comments}.comment_approved = '1'
                AND {$wpdb->term_taxonomy}.term_id = '{$category_id}'
            ORDER BY {$wpdb->comments}.comment_date DESC
            LIMIT 0, {$limit}
        ";
        $comments = array();
        foreach ($wpdb->get_results($sql) as $c)
            $comments[] = get_comment($c->comment_ID);
        return $comments;

    }
