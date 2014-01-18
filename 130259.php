<?php

// http://wordpress.stackexchange.com/questions/130259/get-custom-post-type-categories

    global $wpdb;

    // set the target relationship here
    $post_type = 'post';
    $taxonomy = 'category';

    $terms_ids = $wpdb->get_col( $wpdb->prepare( "
        SELECT
            tt.term_id
        FROM
            {$wpdb->term_relationships} tr,
            {$wpdb->term_taxonomy} tt,
            {$wpdb->posts} p
        WHERE 1=1
            AND tr.object_id = p.id
            AND p.post_type = '%s'
            AND p.post_status = 'publish'
            AND tr.term_taxonomy_id = tt.term_taxonomy_id
            AND tt.taxonomy ='%s'
        ", $post_type, $taxonomy ) );

    // here you are
    $terms = get_terms( $taxonomy, array(
        'include' => $terms_ids,
        'orderby' => 'name',
        'order' => 'ASC'
    ) );
