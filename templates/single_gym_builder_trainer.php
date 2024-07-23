<?php
/**
 * @package GymBuilder
 */

use \GymBuilder\Inc\Controllers\Helpers\Functions;
use \GymBuilder\Inc\Controllers\Models\GymBuilderTrainer;
use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;


defined('ABSPATH') || exit;
global $post;

Functions::get_header($wp_version);

$page_layout            = SettingsApi::get_option('trainer_single_page_layout','gym_builder_trainer_settings') ?:'full-width';
$single_page_sidebar    = Functions::is_trainer() ? 'trainer-sidebar':'';
$sidebar                = Functions::$trainer_single_sidebar;

$trainer_id        =  get_the_ID();
$thumb_size      ='thumbnail';

/**
 * Hook: gym_builder_before_main_content_wrapper.
 *
 * @hooked output_main_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action('gym_builder_before_main_content_wrapper');

/**
 * Hook: gym_builder_before_main_content.
 *
 * @hooked output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action('gym_builder_before_main_content');

?>
<div class="gym-builder-single-trainer-wrapper <?php echo esc_attr($page_layout); ?>">

    <?php if('left-sidebar'==$page_layout && Functions::is_active_sidebar($sidebar)===true){ ?>
        <aside <?php Functions::sidebar_class($single_page_sidebar); ?>>
            <?php
            dynamic_sidebar($sidebar);
            ?>
        </aside>
    <?php } ?>
    <div class="post-wrapper">
        <div class="gym-builder-trainer-info">
            <div class="thumb">
                <?php
                if ( has_post_thumbnail() ){
                    the_post_thumbnail($thumb_size);
                } else {
                    echo '<img class="wp-post-image" src="' . Functions::get_img( 'noimage_1210X584.jpg' ) . '" alt="'. the_title_attribute( array( 'echo'=> false ) ) .'">';
                }
                ?>
            </div>
            <div class="trainer-content">
                <h2 class="entry-title"><?php the_title(); ?></h2>
                <div class="trainer-designation"><?php GymBuilderTrainer::the_trainer_designation( $trainer_id);?></div>
                <?php
                    GymBuilderTrainer::the_trainer_socials($trainer_id);
                ?>
            </div>
        </div>
        <div id="post-<?php the_ID(); ?>" <?php post_class( 'trainer-single' ); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
        </div>
    </div>
    <?php if('right-sidebar'==$page_layout && Functions::is_active_sidebar($sidebar)===true){ ?>
        <aside <?php Functions::sidebar_class($single_page_sidebar); ?>>
            <?php
            dynamic_sidebar($sidebar);
            ?>
        </aside>
    <?php } ?>
</div>

<?php
/**
 * Hook: gym_builder_after_main_content.
 *
 * @hooked output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('gym_builder_after_main_content');

/**
 * Hook: gym_builder_after_main_content_wrapper.
 *
 * @hooked output_main_wrapper_end - 10 (outputs closing divs for the wrapper content)
 */
do_action('gym_builder_after_main_content_wrapper');

Functions::get_footer($wp_version);