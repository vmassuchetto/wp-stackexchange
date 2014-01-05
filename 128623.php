<?php

// http://wordpress.stackexchange.com/questions/128623/how-to-set-the-jpg-image-compression-for-specific-thumbnail-sizes

    add_theme_support( 'post-thumbnails' ); 
    add_image_size( 'newsbox-thumb', 520, 9999 ); // masonry news box-images =260px (520 retina) and unlimited height
    add_image_size( 'fprelease-thumb', 112, 9999 ); // fprelese feed logo, 56px (112px retina)


    add_filter( 'jpeg_quality', create_function( '$quality', 'return 100;' ) );
    add_action( 'added_post_meta', 'ad_update_jpeg_quality', 10, 4 );

    function ad_update_jpeg_quality( $meta_id, $attach_id, $meta_key, $attach_meta ) {

        if ( $meta_key != '_wp_attachment_metadata' )
            return false;

        if ( ! $post = get_post( $attach_id ) )
            return false;

        if ( 'image/jpeg' != $post->post_mime_type )
            return false;

        $original = array(
            'original' => array(
                'file' => $attach_meta['file'],
                'width' => $attach_meta['width'],
                'height' => $attach_meta['height']
            )
        );
        $sizes = !empty( $attach_meta['sizes'] ) && is_array( $attach_meta['sizes'] )
            ? $attach_meta['sizes']
            : array();
        $sizes = array_merge( $sizes, $original );

        $pathinfo = pathinfo( $attach_meta['file'] );
        $uploads = wp_upload_dir();
        $dir = $uploads['basedir'] . '/' . $pathinfo['dirname'];

        foreach ( $sizes as $size => $value ) {

            $image = 'original' == $size
                ? $uploads['basedir'] . '/' . $value['file']
                : $dir . '/' . $value['file'];
            $resource = imagecreatefromjpeg( $image );

            if ( $size == 'original' )
                $q = 70; // quality for the original image
            elseif ( $size == 'newsbox-thumb' )
                $q = 60;
            elseif ( $size == 'fprelease-thumb' )
                $q = 85;
            else
                $q = 80;

            imagejpeg( $resource, $image, $q );
            imagedestroy( $resource );

        }

    }
