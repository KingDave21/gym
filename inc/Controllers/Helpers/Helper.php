<?php
/**
 * @package GymBuilder
 */

namespace GymBuilder\Inc\Controllers\Helpers;

use GymBuilder\Inc\Traits\Constants;
use GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Models\ThumbnailSizeGenerator;

class Helper {
	use Constants;

	public static function wp_set_temp_query( $query ) {
		global $wp_query;
		global $post;
		$temp     = $wp_query;
		$wp_query = $query;

		return $temp;
	}

	public static function wp_reset_temp_query( $temp ) {
		global $wp_query;
		$wp_query = $temp;
		wp_reset_postdata();
	}

	public static function get_the_terms( $post_id, $taxonomy ) {
		return get_the_terms( $post_id, $taxonomy );
	}

	public static function orderbyQueryOptions() {
		$options = [
			'none'       => 'No order',
			'title'      => 'Order by title',
			'ID'         => 'Order by post id',
			'name'       => 'Order by post name ',
			'date'       => 'Order by date',
			'rand'       => 'Random order',
			'menu_order' => 'Order by Page Order',
		];

		return apply_filters( 'gym_builder_orderby_query_options', $options );
	}

	public static function get_primary_color() {
		return apply_filters( 'gym_builder_primary_color', SettingsApi::get_option( 'gym_builder_primary_color', 'gym_builder_style_settings' ) ?: '#005dd0' );
	}

	public static function get_secondary_color() {
		return apply_filters( 'gym_builder_secondary_color', SettingsApi::get_option( 'gym_builder_secondary_color', 'gym_builder_style_settings' ) ?: '#0a4b78' );
	}

	public static function slider_layouts() {
		return [
			'class_archive_style'   => [ 'layout-2' ],
			'trainer_archive_style' => [ 'layout-3' ]
		];
	}

	public static function slider_layout_search( $needleKey, $needleValue, $haystack ) {
		foreach ( $haystack as $key => $value ) {
			if ( $key == $needleKey ) {
				$result = in_array( $needleValue, $value );
				if ( $result !== false ) {
					return true;
				}
			}
		}

		return false;
	}

	public static function is_slider_layout( $settings, $layout ) {
		$layouts = self::slider_layouts();

		return self::slider_layout_search( $settings, $layout, $layouts );
	}

	public static function gbClassMetaScBuilder( $meta ) {
		$custom_thumb_size = [
			'width'  => ! empty( $meta['gb_class_shortcode_thumb_width'][0] ) ? absint( $meta['gb_class_shortcode_thumb_width'][0] ) : '570',
			'height' => ! empty( $meta['gb_class_shortcode_thumb_height'][0] ) ? absint( $meta['gb_class_shortcode_thumb_height'][0] ) : '400',
			'crop'   => ! empty( $meta['gb_class_shortcode_thumb_crop'][0] ) ? esc_attr( $meta['gb_class_shortcode_thumb_crop'][0] ) : 'hard',
		];
		$metas             = [
			'time_format'       => ! empty( $meta['gb_class_shortcode_time_format'][0] ) ? absint( $meta['gb_class_shortcode_time_format'][0] ) : '12',
			'layout'            => ! empty( $meta['gb_class_shortcode_layout'][0] ) ? esc_attr( $meta['gb_class_shortcode_layout'][0] ) : 'layout-1',
			'posts_per_page'    => ! empty( $meta['gb_class_shortcode_posts_per_page'][0] ) ? absint( $meta['gb_class_shortcode_posts_per_page'][0] ) : '',
			'grid_columns'      => ! empty( $meta['gb_class_shortcode_grid_columns'][0] ) ? esc_attr( $meta['gb_class_shortcode_grid_columns'][0] ) : '3',
			'custom_image_size' => $custom_thumb_size,
			'post_in'           => ! empty( $meta['gb_class_include_shortcode'][0] ) ? $meta['gb_class_include_shortcode'][0] : [],
			'post_not_in'       => ! empty( $meta['gb_class_exclude_shortcode'][0] ) ? $meta['gb_class_exclude_shortcode'][0] : [],
			'categories'        => ! empty( $meta['gb_class_categories_shortcode'][0] ) ? $meta['gb_class_categories_shortcode'][0] : [],
			'order_by'          => ! empty( $meta['gb_class_order_by_shortcode'][0] ) ? $meta['gb_class_order_by_shortcode'][0] : null,
			'order'             => ! empty( $meta['gb_class_order_shortcode'][0] ) ? $meta['gb_class_order_shortcode'][0] : null,
			'more_btn'          => ! empty( $meta['gb_class_shortcode_more_btn'][0] ) ? esc_attr( $meta['gb_class_shortcode_more_btn'][0] ) : '',
			'more_btn_text'     => ! empty( $meta['gb_class_shortcode_more_btn_text'][0] ) ? esc_attr( $meta['gb_class_shortcode_more_btn_text'][0] ) : __( 'More Classes', 'gym-builder' ),
			'more_btn_url'      => ! empty( $meta['gb_class_shortcode_more_btn_url'][0] ) ? esc_url( $meta['gb_class_shortcode_more_btn_url'][0] ) : '#'

		];

		return apply_filters( 'gb_class_meta_sc_builder', $metas, $meta );
	}

	public static function gbFitnessCalcMetaBuilder( $meta ) {

		$metas = [
			'calculator_shortcode_types' => ! empty( $meta['gb_fitness_calculator_shortcode_types'][0] ) ? esc_attr( $meta['gb_fitness_calculator_shortcode_types'][0] ) : 'bmi',
			'calculator_heading_text'    => ! empty( $meta['gb_fitness_calc_heading'][0] ) ? esc_html( $meta['gb_fitness_calc_heading'][0] ) : __( 'Fitness Calculators', 'gym-builder' ),
			'calculator_des'             => ! empty( $meta['gb_fitness_calc_des'][0] ) ? esc_html( $meta['gb_fitness_calc_des'][0] ) : '',
			'calculator_unit'            => ! empty( $meta['gb_fintess_calc_unit'][0] ) ? esc_attr( $meta['gb_fintess_calc_unit'][0] ) : 'metric',
			'calculator_btn_text'        => ! empty( $meta['gb_fitness_calc_btn_text'][0] ) ? esc_html( $meta['gb_fitness_calc_btn_text'][0] ) : __( 'Calculator', 'gym-builder' ),
			'bmi_layout'                 => ! empty( $meta['gb_bmi_calc_layout'][0] ) ? esc_html( $meta['gb_bmi_calc_layout'][0] ) : 'layout-1',
			'body_fat_layout'            => ! empty( $meta['gb_body_fat_layout'][0] ) ? esc_html( $meta['gb_body_fat_layout'][0] ) : 'layout-1',
			'protien_intake_layout'      => ! empty( $meta['protien_intake_layout'][0] ) ? esc_html( $meta['protien_intake_layout'][0] ) : 'layout-1',
			'water_intake_layout'        => ! empty( $meta['water_intake_layout'][0] ) ? esc_html( $meta['water_intake_layout'][0] ) : 'layout-1',
		];

		return apply_filters( 'gb_fitness_calc_meta_sc_builder', $metas, $meta );
	}

	public static function fitness_calc_type_layout( $metas ) {
		$calc_types = $metas['calculator_shortcode_types'];
		if ( 'bmi' === $calc_types ) {
			$calc_types_layout = 'bmi/' . $metas['bmi_layout'];
		} elseif ( 'body_fat' === $calc_types ) {
			$calc_types_layout = 'body-fat/' . $metas['body_fat_layout'];
		} elseif ( 'protien_intake' === $calc_types ) {
			$calc_types_layout = 'protien-intake/' . $metas['protien_intake_layout'];
		} elseif ( 'water_intake' === $calc_types ) {
			$calc_types_layout = 'water-intake/' . $metas['water_intake_layout'];
		}

		return $calc_types_layout;
	}

	public static function gbTrainerMetaScBuilder( $meta ) {
		$custom_thumb_size = [
			'width'  => ! empty( $meta['gb_trainer_shortcode_thumb_width'][0] ) ? absint( $meta['gb_trainer_shortcode_thumb_width'][0] ) : '570',
			'height' => ! empty( $meta['gb_trainer_shortcode_thumb_height'][0] ) ? absint( $meta['gb_trainer_shortcode_thumb_height'][0] ) : '400',
			'crop'   => ! empty( $meta['gb_trainer_shortcode_thumb_crop'][0] ) ? esc_attr( $meta['gb_trainer_shortcode_thumb_crop'][0] ) : 'hard',
		];
		$metas             = [
			'layout'            => ! empty( $meta['gb_trainer_shortcode_layout'][0] ) ? esc_attr( $meta['gb_trainer_shortcode_layout'][0] ) : 'layout-1',
			'posts_per_page'    => ! empty( $meta['gb_trainer_shortcode_posts_per_page'][0] ) ? absint( $meta['gb_trainer_shortcode_posts_per_page'][0] ) : '',
			'grid_columns'      => ! empty( $meta['gb_trainer_shortcode_grid_columns'][0] ) ? esc_attr( $meta['gb_trainer_shortcode_grid_columns'][0] ) : '3',
			'custom_image_size' => $custom_thumb_size,
			'post_in'           => ! empty( $meta['gb_trainer_include_shortcode'][0] ) ? $meta['gb_trainer_include_shortcode'][0] : [],
			'post_not_in'       => ! empty( $meta['gb_trainer_exclude_shortcode'][0] ) ? $meta['gb_trainer_exclude_shortcode'][0] : [],
			'categories'        => ! empty( $meta['gb_trainer_categories_shortcode'][0] ) ? $meta['gb_trainer_categories_shortcode'][0] : [],
			'order_by'          => ! empty( $meta['gb_trainer_order_by_shortcode'][0] ) ? $meta['gb_trainer_order_by_shortcode'][0] : null,
			'order'             => ! empty( $meta['gb_trainer_order_shortcode'][0] ) ? $meta['gb_trainer_order_shortcode'][0] : null,
			'more_btn'          => ! empty( $meta['gb_trainer_shortcode_more_btn'][0] ) ? esc_attr( $meta['gb_trainer_shortcode_more_btn'][0] ) : '',
			'more_btn_text'     => ! empty( $meta['gb_trainer_shortcode_more_btn_text'][0] ) ? esc_attr( $meta['gb_trainer_shortcode_more_btn_text'][0] ) : __( 'More Trainer', 'gym-builder' ),
			'more_btn_url'      => ! empty( $meta['gb_trainer_shortcode_more_btn_url'][0] ) ? esc_url( $meta['gb_trainer_shortcode_more_btn_url'][0] ) : '#'

		];

		return apply_filters( 'gb_trainer_meta_sc_builder', $metas, $meta );
	}

	public static function CustomImageReSize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		$thumbResize = new ThumbnailSizeGenerator();

		return $thumbResize->process( $url, $width, $height, $crop, $single, $upscale );
	}

	public static function getFeatureImage( $post_id = null, $gbImgSize = 'medium', $customImgSize = [] ) {
		$imgHtml = $imgSrc = $attachment_id = null;
		$cSize   = false;

		if ( $gbImgSize == 'gym_builder_custom' ) {
			$gbImgSize = 'full';
			$cSize     = true;
		}

		$aID        = get_post_thumbnail_id( $post_id );
		$post_title = get_the_title( $post_id );
		$img_alt    = trim( wp_strip_all_tags( get_post_meta( $aID, '_wp_attachment_image_alt', true ) ) );
		$alt_tag    = ! empty( $img_alt ) ? $img_alt : trim( wp_strip_all_tags( $post_title ) );

		$attr = [
			'class' => 'gym-builder-feature-img ',
			'alt'   => $alt_tag,
		];

		$actual_dimension = wp_get_attachment_metadata( $aID, true );


		$actual_w = ! empty( $actual_dimension['width'] ) ? $actual_dimension['width'] : '';
		$actual_h = ! empty( $actual_dimension['height'] ) ? $actual_dimension['height'] : '';

		if ( $aID ) {
			$imgHtml       = wp_get_attachment_image( $aID, $gbImgSize, false, $attr );
			$attachment_id = $aID;
		}


		if ( $imgHtml && $cSize ) {
			preg_match( '@src="([^"]+)"@', $imgHtml, $match );

			$imgSrc = array_pop( $match );
			$w      = ! empty( $customImgSize['width'] ) ? absint( $customImgSize['width'] ) : null;
			$h      = ! empty( $customImgSize['height'] ) ? absint( $customImgSize['height'] ) : null;
			$c      = ! empty( $customImgSize['crop'] ) && $customImgSize['crop'] == 'soft' ? false : true;

			if ( $w && $h ) {
				if ( $w >= $actual_w || $h >= $actual_h ) {
					$w = 150;
					$h = 150;
					$c = true;
				}

				$image = self::CustomImageReSize( $imgSrc, $w, $h, $c, false );

				if ( ! empty( $image ) ) {

					list( $src, $width, $height ) = $image;

					$hwstring    = image_hwstring( $width, $height );
					$attachment  = get_post( $attachment_id );
					$attr        = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $gbImgSize );
					$attr['src'] = $src;
					$attr        = array_map( 'esc_attr', $attr );
					$imgHtml     = rtrim( "<img $hwstring" );

					foreach ( $attr as $name => $value ) {
						$imgHtml .= " $name=" . '"' . $value . '"';
					}

					$imgHtml .= ' />';

				}
			}
		}

		return $imgHtml;
	}

	public static function get_membership_package_types_array( $slug = false ) {
		$type_list = [];
		$terms     = get_terms( [
			'taxonomy'   => self::$membership_package_taxonomy,
			'hide_empty' => false
		] );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( $slug ) {
					$type_list[ $term->slug ] = $term->name;
				} else {
					$type_list[ $term->term_id ] = $term->name;
				}
			}
		}

		return apply_filters( "gym_builder_array_membership_packages_type_list", $type_list );
	}

	public static function get_membership_package_type_slug() {
		$cat_slug = '';
		$terms    = get_the_terms( get_the_ID(), self::$membership_package_taxonomy );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$slug_list = array();
			foreach ( $terms as $term ) {
				$slug_list[] = $term->slug;
			}
			$cat_slug = join( " ", $slug_list );
		}

		return $cat_slug;
	}

	public static function get_membership_package_type_html_format( $package_id, $linkable = true ) {
		$term_lists = get_the_terms( $package_id, self::$membership_package_taxonomy );
		$i          = 1;
		if ( $term_lists ) {
			?>
            <span class="inner-package-type">
				<?php
				foreach ( $term_lists as $term_list ) {
					$link = get_term_link( $term_list->term_id, self::$membership_package_taxonomy ); ?>
					<?php if ( $i > 1 ) {
						echo esc_html( ', ' );
					}
					if ( $linkable ) {
						?>
                        <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $term_list->name ); ?></a>
					<?php } else {
						?>
                        <span class="seperator"> <?php echo esc_html( "/" ); ?></span>
                        <span><?php echo esc_html( $term_list->name ); ?></span>
					<?php }
					$i ++;
				} ?>
			</span>
		<?php }
	}

	public static function fitness_calculator_translatable_text() {

		$text = [
			'heightCentimeter'       => __( 'Centimeter', 'gym-builder' ),
			'weightKilogram'         => __( 'Kilogram', 'gym-builder' ),
			'heightFeet'             => __( 'Feet', 'gym-builder' ),
			'weightPound'            => __( 'Pound', 'gym-builder' ),
			'unitLTR'                => __( 'Ltr', 'gym-builder' ),
			'unitOz'                 => __( 'Oz', 'gym-builder' ),
			'unitLBS'                => __( 'lbs', 'gym-builder' ),
			'unitGram'               => __( 'gram', 'gym-builder' ),
			'bmiUnderweight'         => __( 'Underweight', 'gym-builder' ),
			'bmiNormalweight'        => __( 'Normal Weight', 'gym-builder' ),
			'bmiOverweight'          => __( 'Overweight', 'gym-builder' ),
			'bmiClass1'              => __( '(Class I Obese)', 'gym-builder' ),
			'bmiClass2'              => __( '(Class II Obese)', 'gym-builder' ),
			'bmiClass3'              => __( '(Class III Obese)', 'gym-builder' ),
			'requireField'           => __( 'Required Fields', 'gym-builder' ),
			'numberOnly'             => __( 'Numbers Only', 'gym-builder' ),
			'positiveNumberOnly'     => __( 'Positive Numbers Only', 'gym-builder' ),
			'nonNegativeNumberOnly'  => __( 'Non Negative Numbers Only', 'gym-builder' ),
			'integerOnly'            => __( 'Integers Only', 'gym-builder' ),
			'positiveIntegerOnly'    => __( 'Positive Integers Only', 'gym-builder' ),
			'nonNegativeIntegerOnly' => __( 'Non Negative Integres Only', 'gym-builder' ),
		];

		return apply_filters( "fitness_calculator_translatable_text", $text );

	}

	public static function gym_builder_get_options() {
		$options_keys = [
			'gym_builder_page_settings',
			'gym_builder_permalinks_settings',
			'gym_builder_class_settings',
			'gym_builder_trainer_settings',
			'gym_builder_style_settings',
			'gym_builder_global_settings'
		];

		$settings = ['options' => []];

		foreach ($options_keys as $key) {
			$settings['options'][$key] = get_option($key) ?? [];
		}

		return $settings;

	}
	public static function class_page_layout() {
		$layout = [
			'layout-1' => [
				'title'      => 'Layout 1',
				'img_source' => 'layout-1'
			],
			'layout-2' => [
				'title'      => 'Slider Layout',
				'img_source' => 'layout-2'
			]
		];

		return apply_filters( 'gym_builder_class_page_layout', $layout );
	}
}