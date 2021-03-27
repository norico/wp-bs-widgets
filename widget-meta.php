<?php
class WP_Widget_Meta_BS5 extends WP_Widget
{
    /**
     * @var string|null
     */
    private $small;
    private $block;
    private $outline;

    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_meta',
            'description' => __('Meta Bootstrap 5.'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('meta', __('Meta'), $widget_ops);
    }

    public function widget($args, $instance)
    {
        $title          = apply_filters('widget_title', empty($instance['title']) ? null : $instance['title'], $instance, $this->id_base);
        $this->block    = empty($instance['block']) ? null : ' btn-block';
        $this->small    = empty($instance['small']) ? null : ' btn-sm';
        $this->outline  = empty($instance['outline']) ? null : 'outline-';

        echo $args['before_widget'];
        if ($title)
        {
             echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <ul class="textwidget p-0">
            <?php $this->wp_register(); ?>
            <?php $this->wp_loginout(); ?>
        </ul>
        <?php
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance )
    {
        $instance            = $old_instance;
        $instance['title']   = sanitize_text_field( $new_instance['title'] );
        $instance['small']   = sanitize_key( $new_instance['small'] );
        $instance['block']   = sanitize_key( $new_instance['block'] );
        $instance['outline'] = sanitize_key( $new_instance['outline'] );
        return $instance;
    }


    public function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'fill' => '', 'size' => '' ) );
        $title   = esc_attr( $instance['title']);
        $block   = checked( $instance['block'], 'on', false);
        $small   = checked( $instance['small'], 'on', false);
        $outline = checked( $instance['outline'], 'on', false);

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'block' ); ?>"  name="<?php echo $this->get_field_name( 'block' );  ?>" type="checkbox" <?php echo $block;?>/>
            <label for="<?php echo $this->get_field_id( 'block' ); ?>"><?php _e( 'Block buttons' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'small' ); ?>"  name="<?php echo $this->get_field_name( 'small' );  ?>" type="checkbox" <?php echo $small;?>/>
            <label for="<?php echo $this->get_field_id( 'small' ); ?>"><?php _e( 'Small buttons' ); ?></label>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id( 'outline' ); ?>"  name="<?php echo $this->get_field_name( 'outline' );  ?>" type="checkbox" <?php echo $outline;?>/>
            <label for="<?php echo $this->get_field_id( 'outline' ); ?>"><?php _e( 'Outline buttons' ); ?></label>
        </p>

        <?php
    }

    public function wp_register($before = '<li class="list-group-item border-0 pl-0 bg-transparent">', $after = '</li>', $echo = true)
    {

        $add_classes = $this->block.$this->small;

        if ( ! is_user_logged_in() ) {
            if ( get_option( 'users_can_register' ) ) {
                $link = $before . '<a class="btn btn-'.$this->outline.'info'.$add_classes.'" href="' . esc_url( wp_registration_url() ) . '">' . __( 'Register' ) . '</a>' . $after;

            } else {
                $link = '';
            }
        } elseif ( current_user_can( 'read' ) ) {
            $link = $before . '<a class="btn btn-'.$this->outline.'warning' .$this->block.$this->small.'" href="' . admin_url() . '">' . __( 'Site Admin' ) . '</a>' . $after;
        } else {
            $link = '';
        }

        $link = apply_filters( 'register', $link );

        if ( $echo ) {
            echo $link;
        }
        return $link;
    }

    public function wp_loginout( $redirect = '', $echo = true ) {
        if ( ! is_user_logged_in() ) {
            $link = '<li class="list-group-item border-0 pl-0 bg-transparent"><a class="btn btn-'.$this->outline.'success' .$this->block.$this->small.'" href="' . esc_url( wp_login_url( $redirect ) ) . '">' . __( 'Log in' ) . '</a></li>';
        } else {
            $link = '<li class="list-group-item border-0 pl-0 bg-transparent"><a class="btn btn-'.$this->outline.'danger' .$this->block.$this->small.'" href="' . esc_url( wp_logout_url( $redirect ) ) . '">' . __( 'Log out' ) . '</a></li>';
        }
        if ( $echo ) {
            echo apply_filters( 'loginout', $link );
        }
        return apply_filters( 'loginout', $link );
    }

}
