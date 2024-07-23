<?php
/**
 * @package GymBuilder
 */

namespace GymBuilder\Inc\Controllers\Models;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;
use GymBuilder\Inc\Controllers\Helpers\Helper;
use GymBuilder\Inc\Controllers\ShortcodeQuery;
use GymBuilder\Inc\Traits\Constants;

class GymBuilderClass {

	use Constants;


	public static function the_title( $class_id ) {
		echo esc_html( get_the_title( $class_id ) );
	}

	public static function get_the_title( $class_id ) {
		return get_the_title( $class_id );
	}

	public static function the_content( $class_id ) {
		echo esc_html( get_the_content( $class_id ) );
	}

	public static function get_the_content( $class_id ) {
		return get_the_content( $class_id );
	}

	public static function get_classes() {
		return get_posts(
			[
				'post_type'   => self::$class_post_type,
				'post_status' => 'publish',
                'posts_per_page' => -1
			]
		);
	}

	public static function get_the_schedule( $class_id, $schedule_limit = '' ) {
		$schedule = get_post_meta( $class_id, 'gym_builder_class_schedule', true );
		$schedule = ( $schedule != '' ) ? $schedule : array();

		if ( $schedule && ! empty( $schedule_limit ) ) {
			$schedule = array_slice( $schedule, 0, $schedule_limit );
		}

		return $schedule;
	}

	public static function get_the_weekname() {

		$weeknames = array(
			'mon' => esc_html__( 'Mon', 'gym-builder' ),
			'tue' => esc_html__( 'Tue', 'gym-builder' ),
			'wed' => esc_html__( 'Wed', 'gym-builder' ),
			'thu' => esc_html__( 'Thur', 'gym-builder' ),
			'fri' => esc_html__( 'Fri', 'gym-builder' ),
			'sat' => esc_html__( 'Sat', 'gym-builder' ),
			'sun' => esc_html__( 'Sun', 'gym-builder' ),
		);

		return apply_filters( 'gym_builder_weeknames_short', $weeknames );
	}

	public static function get_the_routine_weekname() {

		$weeknames = array(
			'mon' => esc_html__( 'Mon', 'gym-builder' ),
			'tue' => esc_html__( 'Tue', 'gym-builder' ),
			'wed' => esc_html__( 'Wed', 'gym-builder' ),
			'thu' => esc_html__( 'Thur', 'gym-builder' ),
			'fri' => esc_html__( 'Fri', 'gym-builder' ),
			'sat' => esc_html__( 'Sat', 'gym-builder' ),
			'sun' => esc_html__( 'Sun', 'gym-builder' ),
		);

		return apply_filters( 'gym_builder_routine_weeknames_short', $weeknames );
	}

	public static function sort_by_time_as_key( $a, $b ) {
		return (int) ( strtotime( $a ) > strtotime( $b ) );
	}

	public static function sort_by_end_time( $a, $b ) {
		return (int)( strtotime( $a['end_time'] ) > strtotime( $b['end_time'] ) );
	}

	public static function get_schedule_routine( int $shortcode_id, array $metas, string $layout_id ) {

		$html             = null;
		$containerAttr    = null;
		$weeknames        = self::get_the_routine_weekname();
		$schedule         = array();
		$routines_info    = array();
		$class_query_info = array();
		$available_weeks  = array();
		$html             .= '<div class="' . esc_attr( $layout_id . ' ' . $metas['layout'] ) . ' gym-builder-routine">';
		$query            = ( new ShortcodeQuery() )->buildArgs( $shortcode_id, $metas, self::$class_post_type, self::$class_taxonomy )->get_gb_shortcode_posts();
		$temp             = Helper::wp_set_temp_query( $query );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$class_id                         = get_the_ID();
				$class_info                       = get_post_meta( $class_id, 'gym_builder_class_schedule', true );
				$class_info                       = ( $class_info != '' ) ? $class_info : array();
				$class_query_info[ get_the_ID() ] = get_the_title();
				foreach ( $class_info as $meta ) {
					if ( empty( $meta['week'] ) || $meta['week'] == 'none' || empty( $meta['start_time'] ) ) {
						continue;
					}
					$start_time = strtotime( $meta['start_time'] );
					$end_time   = ! empty( $meta['end_time'] ) ? strtotime( $meta['end_time'] ) : false;

					if ( $metas['time_format'] == '24' ) {
						$start_time = date( "H:i", $start_time );
						$end_time   = $end_time ? date( "H:i", $end_time ) : '';
					} else {
						$start_time = date( "g:ia", $start_time );
						$end_time   = $end_time ? date( "g:ia", $end_time ) : '';
					}

					if ( ! in_array( $meta['week'], $available_weeks ) ) {
						$available_weeks[] = $meta['week'];
					}
					$schedule[ $start_time ][ $meta['week'] ][] = array(
						'id'         => $class_id,
						'class'      => get_the_title(),
						'start_time' => $start_time,
						'end_time'   => $end_time,
					);
				}

			}
			foreach ( $weeknames as $key => $value ) {
				if ( ! in_array( $key, $available_weeks ) ) {
					unset( $weeknames[ $key ] );
				}
			}
			uksort( $schedule, array( GymBuilderClass::class, 'sort_by_time_as_key' ) );
			$routines_info['schedule']          = $schedule;
			$routines_info['weeknames']         = $weeknames;
			$routines_info['class_query_info'] = $class_query_info;
			ob_start();
			$html .= ob_get_contents();
			ob_end_clean();
			$html .= Functions::render('shortcode/class/layouts/'.$metas['layout'],$routines_info,true);
			ob_start();
			$html .= ob_get_contents();
			ob_end_clean();

		} else {
			$html .= '<p>' . esc_html__( 'No posts found.', 'gym-builder' ) . '</p>';
		}
		Helper::wp_reset_temp_query( $temp );
		$html .= '</div>';

		return $html;
	}

	public static function exclude_global_query_layout() {
		return [
			'layout-2',
		];
	}

	public static function print_routine( $routine ) {
		usort( $routine, array( GymBuilderClass::class, 'sort_by_end_time' ) );
		?>
		<?php foreach ( $routine as $each_routine ): ?>
			<?php
			$class     = 'gym-builder-routine gym-builder-routine-id-' . $each_routine['id'];
			$permalink = get_the_permalink( $each_routine['id'] );
			$start_tag = '<div class="' . $class . '">';
			$end_tag   = '</div>';
			?>
			<?php echo $start_tag; ?>
            <div class="gym-builder-routine-time">
                <span><?php echo esc_html( $each_routine['start_time'] ); ?></span>
				<?php if ( ! empty( $each_routine['end_time'] ) ): ?>
                    <span>- <?php echo esc_html( $each_routine['end_time'] ); ?></span>
				<?php endif; ?>
            </div>
            <h4 class="gym-builder-routine-title"><a
                        href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $each_routine['class'] ); ?></a>
            </h4>
			<?php echo $end_tag; ?>
		<?php endforeach; ?>
		<?php
	}

	public static function time_picker_format() {
		return SettingsApi::get_option( 'class_time_format', 'gym_builder_class_settings' ) == 24 ? '24' : '12';
	}

	public static function get_schedule_time( $start_time, $end_time ) {

		$start_time = ! empty( $start_time ) ? strtotime( $start_time ) : false;
		$end_time   = ! empty( $end_time ) ? strtotime( $end_time ) : false;

		if ( self::time_picker_format() == '24' ) {
			$start_time = $start_time ? date_i18n( "H:i", $start_time ) : '';
			$end_time   = $end_time ? date_i18n( "H:i", $end_time ) : '';
		} else {
			$start_time = $start_time ? date_i18n( "g:ia", $start_time ) : '';
			$end_time   = $end_time ? date_i18n( "g:ia", $end_time ) : '';
		}

		return [
			'start_time' => $start_time,
			'end_time'   => $end_time
		];

	}

	public static function get_shortcode_schedule_time( $start_time, $end_time, $time_format ) {

		$start_time = ! empty( $start_time ) ? strtotime( $start_time ) : false;
		$end_time   = ! empty( $end_time ) ? strtotime( $end_time ) : false;

		if ( $time_format == '24' ) {
			$start_time = $start_time ? date_i18n( "H:i", $start_time ) : '';
			$end_time   = $end_time ? date_i18n( "H:i", $end_time ) : '';
		} else {
			$start_time = $start_time ? date_i18n( "g:ia", $start_time ) : '';
			$end_time   = $end_time ? date_i18n( "g:ia", $end_time ) : '';
		}

		return [
			'start_time' => $start_time,
			'end_time'   => $end_time
		];

	}

	public static function get_schedule_meta( array $schedules, array $weeknames, bool $show_trainer = true, bool $show_schedule_title = true, bool $shortcode_time_format = false, string $time_format = '12' ) {
		?>
        <ul class="class-meta">
			<?php
			foreach ( $schedules as $schedule_info ) {

				if ( ! empty( $schedule_info['week'] ) && ! empty( $schedule_info['start_time'] ) ) {

					$start_time = $schedule_info['start_time'];

					$end_time = ! empty( $schedule_info['end_time'] ) ? $schedule_info['end_time'] : false;

					$type = ! empty( $schedule_info['trainer'] ) ? get_post_type( $schedule_info['trainer'] ) : '';

					if ( $type == self::$trainer_post_type ) {
						$trainer_name = get_the_title( $schedule_info['trainer'] );
					}

					if ( $shortcode_time_format ) {
						$class_schedule_time = self::get_shortcode_schedule_time( $start_time, $end_time, $time_format );
					} else {
						$class_schedule_time = self::get_schedule_time( $start_time, $end_time );
					}


					$full_time = $class_schedule_time['start_time'] . "-" . $class_schedule_time['end_time']

					?>
                    <li>
						<?php if ( ! empty( $trainer_name ) && $show_trainer === true ): ?>
                            <span class="trainer">
		                            <span class="trainer-title"><?php esc_html_e( 'Trainer : ', 'gym-builder' ); ?></span>
		                            <span class="trainer-name"><?php echo esc_html( $trainer_name ); ?></span>
		                        </span>
						<?php endif; ?>
                        <span class="schedule">
                            <?php if ( $show_schedule_title === true ) {
	                            ?>
                                <span class="schedule-title"><?php esc_html_e( 'Schedule : ', 'gym-builder' ); ?></span>
                            <?php } ?>
		                        <span class="day"><?php echo esc_html( $weeknames[ $schedule_info['week'] ] ); ?>:</span>
		                        <span class="time"><?php echo esc_html( $full_time ); ?></span>
		                    </span>
                    </li>
				<?php }

			}
			?>
        </ul>
		<?php
	}

	public static function get_category_html_format( $class_id ) {
		$term_lists = get_the_terms( $class_id, self::$class_taxonomy );
		$i          = 1;
		if ( $term_lists ) {
			?>
            <div class="class-category">
				<?php
				foreach ( $term_lists as $term_list ) {
					$link = get_term_link( $term_list->term_id, self::$class_taxonomy ); ?>
					<?php if ( $i > 1 ) {
						echo esc_html( ', ' );
					} ?>
                    <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $term_list->name ); ?></a>
					<?php $i ++;
				} ?>
            </div>
		<?php }
	}

	public static function get_button_html_format( $class_id ) {
		$button_text = get_post_meta( $class_id, 'gym_builder_class_button_text', true );
		$button_link = get_post_meta( $class_id, 'gym_builder_class_button_url', true ) ?: '#';
		if ( $button_text ) {
			?>
            <div class="class-button">
                <a class="gym-builder-btn"
                   href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_text ); ?>
                </a>
            </div>
		<?php }
	}

	public static function get_categories_array() {
		$categories_list = [];
		$terms           = get_terms( [
			'taxonomy'   => self::$class_taxonomy,
			'hide_empty' => false
		] );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories_list[ $term->term_id ] = $term->name;
			}
		}

		return apply_filters( "gym_builder_array_classes_category_list", $categories_list );
	}

	public static function get_slider_settings() {
		$slides_per_view = SettingsApi::get_option( 'slides_per_view', 'gym_builder_class_settings' ) ?: '3';
		$slider_autoplay = SettingsApi::get_option( 'slider_autoplay', 'gym_builder_class_settings' ) === 'on';
		$slider_loop     = SettingsApi::get_option( 'slider_loop', 'gym_builder_class_settings' ) === 'on';
		$centered_slider = SettingsApi::get_option( 'centered_slider', 'gym_builder_class_settings' ) === 'on';
		$slider_settings = array(
			'slidesPerView'       => $slides_per_view,
			'loop'                => $slider_loop,
			'spaceBetween'        => 20,
			'slidesPerGroup'      => 1,
			'centeredSlides'      => $centered_slider,
			'slideToClickedSlide' => true,
			'autoplay'            => array(
				'delay' => 2000,
			),
			'speed'               => 2000,
			'breakpoints'         => array(
				'0'    => array( 'slidesPerView' => 1 ),
				'576'  => array( 'slidesPerView' => 1 ),
				'768'  => array( 'slidesPerView' => 1 ),
				'992'  => array( 'slidesPerView' => 2 ),
				'1200' => array( 'slidesPerView' => 2 ),
				'1600' => array( 'slidesPerView' => $slides_per_view )
			),
			'auto'                => $slider_autoplay
		);

		return apply_filters( 'gym_builder_class_slider_settings', $slider_settings );
	}

}