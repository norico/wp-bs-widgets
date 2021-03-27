<?php
class WP_Widget_Recent_Posts_BS5 extends WP_Widget {
    public function __construct() {
        $widget_ops = array(
            'classname'                   => 'widget_recent_entries',
            'description'                 => __( 'Your site&#8217;s most recent Posts.' ) . ' (Bootstrap)',
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'recent-posts', __( 'Recent Posts' ), $widget_ops );
        $this->alt_option_name = 'widget_recent_entries';
    }

    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : null;
        $title         = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number         = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) { $number = 5; }

        $show_date      = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;

        $r = new WP_Query(
            apply_filters(
                'widget_posts_args',
                array(
                    'posts_per_page'      => $number,
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ),
                $instance
            )
        );

        if ( ! $r->have_posts() ) {
            return;
        }
        ?>

        <?php echo $args['before_widget']; ?>

        <?php
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';
        $format = apply_filters( 'navigation_widgets_format', $format );

        if ( 'html5' === $format ) {
            $title      = trim( strip_tags( $title ) );
            $aria_label = $title ? $title : $default_title;
            echo '<nav role="navigation" aria-label="' . esc_attr( $aria_label ) . '">';
        }

        foreach ( $r->posts as $recent_post ) :
            $post_title   = get_the_title( $recent_post->ID );
            $title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
            $aria_current = '';

            if ( get_queried_object_id() === $recent_post->ID ) {
                $aria_current = ' aria-current="page"';
            }

            plugin_get_template( 'recent_posts.php', array(
                        "thumbnail_size"    => 75,
                        "title"             => $title,
                        "recent_post_ID"    => $recent_post->ID,
                        "aria_current"      => $aria_current,
                        "show_date"         => $show_date,
                        "show_thumbnail"    => $show_thumbnail
            ));
        endforeach;
        ?>


        <?php
        if ( 'html5' === $format ) {
            echo '</nav>';
        }

        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance                   = $old_instance;
        $instance['title']          = sanitize_text_field( $new_instance['title'] );
        $instance['number']         = (int) $new_instance['number'];
        $instance['show_date']      = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
        return $instance;
    }

    public function form( $instance ) {
        $title          = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number         = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date      = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : false;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>

        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_thumbnail ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label>
        </p>

        <?php
    }
}