<?php
/**
 * @package Gym Builder/templates/class/loop
 * @var array $args
 * @var int   $class_id
 * @version 1.0.0
 */

$button_text = get_post_meta( $class_id, 'gym_builder_class_button_text', true );
$button_link = get_post_meta( $class_id, 'gym_builder_class_button_url', true ) ?:'#';

if($button_text){
?>
<div class="class-button">
	<a class="gym-builder-btn" href="<?php echo esc_url( $button_link); ?>"><?php  echo esc_html($button_text);  ?>
    </a>
</div>
<?php } ?>