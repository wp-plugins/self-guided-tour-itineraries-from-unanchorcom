<?php
/*
Plugin Name: Self Guided Tour Itineraries from Unanchor.com
Plugin URI: http://blog.unanchor.com/2011/08/unanchor-wordpress-plugin-for-writers/
Description: Displays a list of Self-Guided Tour Itineraries from Unanchor.com. If an unanchor username is provided, it will only return itineraries written by that user.
Author: Unanchor.com
Version: 1.2.1
Author URI: http://www.unanchor.com/
*/


    /**
      * When the plugin is activated, clear the cache, call the unanchor API and cache the results
      */
    function SGTI_activate() {

        // Create default settings
        if( !get_option("SGTI_options") ) {

            $defaults = array();
            $defaults['title'] = "Unanchor Tour Itineraries";
            $defaults['header_element'] = "h2";

            update_option("SGTI_options", $defaults );
        }

        if( !get_option("SGTI_widget_options") ) {

            $defaults = array();
            $defaults['title'] = "Unanchor Tour Itineraries";
            $defaults['num_display'] = 5;
            $defaults['unanchor_username'] = '';

            update_option('SGTI_widget_options', $defaults );

        }

        // Clear cache
        delete_option('SGTI_cache');

        $options = get_option('SGTI_widget_options');

        // Regenerate cache of unanchor API call
        $itineraries = SGTI_get_itineraries( $options['unanchor_username'] );

    }


    function SGTI_load_includes() {

        echo '<link rel="stylesheet" type="text/css" href="'.get_option('siteurl').'/wp-content/plugins/self-guided-tour-itineraries-from-unanchorcom/main.css" />';

    }



    /**
      Checks the cache data in the wordpress options table
      if the data exists, and it has not expired, it is returned
      if the data does not exist, or it has expired, false is returned
    **/
    function SGTI_get_from_cache() {
        $data = false;

        // check if we have a cached version
        if($cache_info = get_option('SGTI_cache')){

            // make sure the cache has not expired
//            var_dump($cache_info);exit;
            if(!is_numeric($cached_info[1]) || $cached_info[1] < (time()-86400)) {
                $data = false;
            } else {
                $data = $cached_info[0];
            }

        }

        return $data;
    }


    function SGTI_get_itineraries( $unanchor_username = '') {

        // check if we have a cached version, if so, just use that
        if($cache = SGTI_get_from_cache()){
            return json_decode($cache);
        }

        // if we have curl
        if (function_exists('curl_init')) {
            $url = get_permalink($post_id);

            // create a new cURL resource
            $ch = curl_init();

            // set URL and other appropriate options
            if(!empty( $unanchor_username )){
                curl_setopt($ch, CURLOPT_URL, 'http://api.unanchor.com/1.0/itinerary/list.json?username=' . urlencode($unanchor_username));
            } else {
                curl_setopt($ch, CURLOPT_URL, 'http://api.unanchor.com/1.0/itinerary/list.json');
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $api_json_response = curl_exec($ch);

            curl_close($ch);

            // update the cache
            update_option('SGTI_cache', array($api_json_response, time()));

            return json_decode($api_json_response);
        } else {
            echo '<strong>Your server does not have PHP cURL support. Please contact your webhost and ask them to enable cURL for PHP.</strong>';
            exit;
        }

    }


    /********** Widget functions **********/
    function SGTI_widget( $args ) {

        $options = get_option('SGTI_widget_options');


        // if options have been passed in as params, they will overwrite options stored in the database
        if(isset($args['title'])) {
            $options['title'] = $args['title'];
        }
        if(isset($args['num_display'])) {
            $options['num_display'] = $args['num_display'];

        }
        if(isset($args['unanchor_username'])) {
            $options['unanchor_username'] = $args['unanchor_username'];
        }

        $json_object = SGTI_get_itineraries( $options['unanchor_username'] );

        echo $before_widget;
        echo "<div id=\"unanchor-itineraries\">\n";
        echo "<h2>".$options['title']."</h2>\n";
        echo "<ul>\n";

        $i = 0;
        foreach( $json_object->itineraries as $itinerary  ) {
            if($i < $options['num_display']) {
                echo '<li><a href="http://www.unanchor.com/itinerary/'.$itinerary->slug.'.html" title="'.attribute_escape($itinerary->title).'"'.
                        '><img src="http://www.unanchor.com/uploads/itinerary/images/thumbnail/size2/'.$itinerary->image_filepath.'" width="35" height="35"></a> '.
                        '<a href="http://www.unanchor.com/itinerary/'.$itinerary->slug.'.html" title="'.attribute_escape($itinerary->title).'">'.
                        $itinerary->title.'</a></li><div style="clear: both;"></div>';
            } else {
                break;
            }
            $i++;
        }

        echo '</ul></div>';

        echo $after_widget;

    }


    function SGTI_widget_control() {

        $options = $new_options = get_option('SGTI_widget_options');

        if ( $_POST['SGTI_submit'] ) {
            $options['title'] = strip_tags( stripslashes( $_POST['SGTI_widget_title'] ) );
            $options['num_display'] = strip_tags( stripslashes( $_POST['SGTI_num_display'] ) );
            $options['unanchor_username'] = strip_tags( stripslashes( $_POST['SGTI_username'] ) );

            update_option('SGTI_widget_options', $options);

            // Clear cache
            delete_option('SGTI_cache');
        }

        $title = attribute_escape($options['title']);

        $num_display = attribute_escape( $options['num_display'] );
        $unanchor_username = attribute_escape( $options['unanchor_username'] );

    ?>

        <p><label for="pages-title"><?php _e('Title:'); ?></label> <input class="widefat" id="SGTI_widget_title" name="SGTI_widget_title" type="text" value="<?php echo $title; ?>" /></p>
        <p><label for="pages-title"><?php _e('Unanchor Username:'); ?></label> <input class="widefat" id="SGTI_username" name="SGTI_username" type="text" value="<?php echo $unanchor_username; ?>" /></p>
        <p><label for="pages-title"><?php _e('No. of Itineraries to Show:'); ?></label> <input class="widefat" id="SGTI_num_display" name="SGTI_num_display" type="text" value="<?php echo $num_display; ?>" /></p>

        <input type="hidden" id="SGTI_submit" name="SGTI_submit" value="1" />
    <?php
    }

    function SGTI_load_widget() {

        $widget_ops = array('classname' => 'unanchor_self_guided_tour_itineraries', 'description' => __( "Include Unanchor Itineraries in your sidebar") );
        wp_register_sidebar_widget('unanchor_self_guided_tour_itineraries', __('Unanchor Itineraries'), 'SGTI_widget', $widget_ops);
        wp_register_widget_control('unanchor_self_guided_tour_itineraries', __('Unanchor Itinerary display options'), 'SGTI_widget_control' );

    }

    function display_SGTI( $options = array() ) {
       SGTI_widget($options);
    }




/******* Hooks and Filters ************/
register_activation_hook( __FILE__, 'SGTI_activate' );

add_action('widgets_init', 'SGTI_load_widget');
add_action('wp_head', "SGTI_load_includes");

?>
