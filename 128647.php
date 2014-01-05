<?php

// http://wordpress.stackexchange.com/questions/128647/retrieve-each-widget-in-a-sidebar-separately

    add_action( 'wp', 'widgets_run' );
    function widgets_run() {

        global $wp_registered_widgets;

        $sidebars_widgets = wp_get_sidebars_widgets();
        if ( empty( $sidebars_widgets ) )
            return false;

        foreach ( (array) $sidebars_widgets as $sidebar_id => $sidebar_widgets ) {

            foreach( $sidebar_widgets as $sidebar_widget ) {

                if ( ! isset( $wp_registered_widgets[ $sidebar_widget ] ) )
                    continue;

                $classname_ = '';
                    foreach ( (array) $wp_registered_widgets[ $sidebar_widget ]['classname'] as $cn ) {
                    if ( is_string($cn) )
                        $classname_ .= '_' . $cn;
                    elseif ( is_object($cn) )
                        $classname_ .= '_' . get_class($cn);
                }
                $classnames[] = ltrim($classname_, '_');

            }

        }

        print_r($classnames); // here you are the class names

    }
