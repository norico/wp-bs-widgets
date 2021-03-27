<?php
$thumbnail_size = (isset($thumbnail_size) ) ? $thumbnail_size : 75 ;
$post_id        = (isset($args['recent_post_ID']) ) ? $args['recent_post_ID'] : 'error' ;
$aria_current   = (isset($aria_current) ) ? $aria_current : '' ;
$title          = (isset($title) ) ? $title  : get_the_title($post_id ) ;
$show_thumbnail = (isset($show_thumbnail) ) ? $show_thumbnail  : false ;
$show_date      = (isset($show_date) ) ? $show_date  : '' ;


if ( $show_thumbnail === true ) {
    $post_thumbnail = get_the_post_thumbnail($post_id , array( $thumbnail_size, $thumbnail_size));
    if (!$post_thumbnail) {
        $post_thumbnail = '<div style="min-height:'.$thumbnail_size.'px; min-width:'.$thumbnail_size.'px; background-color:#efefef "></div>';
    }
}
?>

<div class="d-flex mr-1 mb-2">
    <?php if ( $show_thumbnail === true ) { ?>
        <div class="pr-2">
            <?php echo $post_thumbnail ?>
        </div>
    <?php } ?>
    <span class="d-block w-100">
        <h5 class="my-0"><a class="text-decoration-none" href="<?php the_permalink( $post_id  ); ?>"<?php echo $aria_current; ?>><?php echo $title; ?></a></h5>
        <?php if ( $show_date  ) : ?>
        <div class="text-right mr-3 "><small class="post-date"><?php echo get_the_date( '', $post_id  ); ?></small></div>
        <?php endif; ?>
    </span>
</div>