<?php

namespace Listings\Jobs\Widgets;

use Listings\Widgets\Widget;

class FeaturedJobs extends Widget {

    /**
     * Constructor
     */
    public function __construct() {
        global $wp_post_types;

        $this->widget_cssclass    = 'job_manager widget_featured_jobs';
        $this->widget_description = __( 'Display a list of featured listings on your site.', 'wp-job-manager' );
        $this->widget_id          = 'widget_featured_jobs';
        $this->widget_name        = sprintf( __( 'Featured %s', 'wp-job-manager' ), $wp_post_types['job_listing']->labels->name );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => sprintf( __( 'Featured %s', 'wp-job-manager' ), $wp_post_types['job_listing']->labels->name ),
                'label' => __( 'Title', 'wp-job-manager' )
            ),
            'number' => array(
                'type'  => 'number',
                'step'  => 1,
                'min'   => 1,
                'max'   => '',
                'std'   => 10,
                'label' => __( 'Number of listings to show', 'wp-job-manager' )
            )
        );
        $this->register();
    }

    /**
     * widget function.
     *
     * @see WP_Widget
     * @access public
     * @param array $args
     * @param array $instance
     * @return void
     */
    public function widget( $args, $instance ) {
        if ( $this->get_cached_widget( $args ) ) {
            return;
        }

        ob_start();

        extract( $args );

        $title  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $number = absint( $instance['number'] );
        $jobs   = get_job_listings( array(
            'posts_per_page' => $number,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'featured'       => true
        ) );

        if ( $jobs->have_posts() ) : ?>

            <?php echo $before_widget; ?>

            <?php if ( $title ) echo $before_title . $title . $after_title; ?>

            <ul class="job_listings">

                <?php while ( $jobs->have_posts() ) : $jobs->the_post(); ?>

                    <?php listings_get_template_part( 'content-widget', 'job_listing' ); ?>

                <?php endwhile; ?>

            </ul>

            <?php echo $after_widget; ?>

        <?php else : ?>

            <?php listings_get_template_part( 'content-widget', 'no-jobs-found' ); ?>

        <?php endif;

        wp_reset_postdata();

        $content = ob_get_clean();

        echo $content;

        $this->cache_widget( $args, $content );
    }
}