<?php

// http://wordpress.stackexchange.com/questions/59435/how-to-get-current-user-name-by-user-id-in-buddypress

    add_action('wp', 'bp_userdata');
    function bp_userdata() {

        $user = get_display_name(1);
        print_r($user);

    }

    function get_display_name($user_id) {

        if (!$user = get_userdata($user_id))
            return false;

        return $user->data->display_name;

    }
