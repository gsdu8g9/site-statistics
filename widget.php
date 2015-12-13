<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.11.2015
 * Time: 19:26
 */

class Site_Statistics extends WP_Widget {

    /**
     *
     */
    public  function __construct(){
        $widget_ops = apply_filters( 'site_statistics', array(
            'classname' => 'site_statistics',
            'description' => __( 'Add site statistics', 'site-statistics' )
        ) );

        parent::__construct( false, __( 'Site statistics', 'site-statistics'  ), $widget_ops );
    }

    /**
     *  Displays the output, the statistics
     */
    public function widget( $args = array(), $instance = array() ){

        $title = apply_filters( 'widget_title', $instance['title'] );

        //get number topics in the forum
        $post = get_post();
        if( $post->post_type === 'forum' ) {
            $post_parent = $post->ID;
        } else {
            $post_parent = $post->post_parent;
        }
        $args_topic = array(
            'numberposts'     => -1,
            'post_type' => 'topic',
            'post_parent'=> $post_parent
        );
        $topics = new WP_Query( $args_topic );

        $counter_topics = 0;
        $counter_children = 0;
        $users_count = 0;

        if ( $topics->have_posts() ) {
            while ( $topics->have_posts() ) {
                $topics->the_post();
                //get number messages in the forum
                $args_children = array(
                    'numberposts'     => -1,
                    'post_type' => 'reply',
                    'post_parent'=> get_the_ID(),
                    'post_status' => 'publish'
                );
                $children = new WP_Query( $args_children );
                if ( $children->have_posts() ) {
                    $counter_children += $children->post_count;
                }
            }
            $counter_topics = $topics->post_count;
        }

        //get the number of articles in all blogs multisite
        $args_post = array(
            'numberposts'     => -1,
            'post_type' => 'post',
        );
        $query = new WP_Query( $args_post );

        // Setup args for querying members.
        $members_args = array(
            'user_id'         => 0,
            'type'            => 'online',
            'per_page'        => $settings['max_members'],
            'max'             => $settings['max_members'],
            'populate_extras' => true,
            'search_terms'    => false,
        );

        include( 'widget-output.phtml' );
    }

    /**
     * Update the widget options
     *
     * @param array $new_instance The new instance options
     * @param array $old_instance The old instance options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ){
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /**
     *  Output the site statistics widget options form
     *
     * @param array $instance saved instance
     *
     */
    public function form( $instance ){

        if ( isset( $instance[ 'title' ] ) ) {
            $title = __( $instance[ 'title' ], 'site-statistics' );
        }
        else {
            $title = __( 'Statistics', 'site-statistics' );
        }

        include( 'statistics-admin-form.phtml' );
    }
}

/**
 *  Register the widget
 */
function register_my_widget(){
    register_widget( 'Site_statistics' );
}
add_action( 'widgets_init', 'register_my_widget' );