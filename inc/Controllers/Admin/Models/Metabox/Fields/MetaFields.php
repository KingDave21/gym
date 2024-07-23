<?php
/**
 * @package GymBuilder
 */
namespace GymBuilder\Inc\Controllers\Admin\Models\Metabox\Fields;

use GymBuilder\Inc\Controllers\Helpers\Functions;
use GymBuilder\Inc\Traits\FileLocations;

class MetaFields{
	use FileLocations;
    public function display_fields( $fields, $post_id ){
        echo '<table class="gym-builder-postmeta-container">';

        foreach ( $fields as $key => $field ) {
            // Display group field
            if( $field['type'] == 'group' ){
                $parent_key = $key. "['$key']";
                foreach ( $field['value'] as $key2 => $field2 ) {
                    $parent_key = $key. "[$key2]";
                    $default = get_post_meta( $post_id, $key, true );
                    $default = empty( $default[$key2] ) ? false : $default[$key2];
                    $this->display_single_field( $parent_key, $field2, $post_id, $default );
                }
            }
            // Display repeater field
            elseif( $field['type'] == 'repeater' ){
                $this->display_repeater_field( $key, $field, $post_id );
            }
            // Display single field
            else{
                $this->display_single_field( $key, $field, $post_id );
            }
        }

        echo '</table>';
    }

    private function display_repeater_field( $key, $field, $post_id ){
        $meta = get_post_meta( $post_id, $key, true );

        if ( empty( $meta ) ) {
            $meta = array();
        }
        $count = count($meta);

        echo !empty( $field['label'] ) ? '<tr><th colspan="2">'. esc_html( $field['label'] ) .':</th></tr>' : '';
        echo '<tr><td colspan="2" class="gym-builder-postmeta-repeater-wrap" data-num="'.esc_attr($count).'" data-fieldname="'. esc_attr( $key ) .'">';

        // First Hidden Item
        echo '<table class="gym-builder-postmeta-repeater repeater-init">';
        foreach ( $field['value'] as $key2 => $field2 ) {
            $parent_key = $key. "[hidden][$key2]";
            $this->display_single_field( $parent_key, $field2, $post_id, '' );
        }
        echo '</table>';

        // repeatative items
        if ( !empty( $meta ) ){
            foreach ( $meta as $item => $itemvalue ) {
                echo '<table class="gym-builder-postmeta-repeater">';

                foreach ( $field['value'] as $repkey => $repvalue ) {
                    $display_key = $key."[$item]"."[$repkey]";
                    $fieldvalue = isset( $itemvalue[$repkey] ) ? $itemvalue[$repkey] : false;
                    $this->display_single_field( $display_key, $repvalue, $post_id, $fieldvalue );
                }

                echo '</table>';
            }
        }

        $buttontext = empty( $field['button'] ) ? esc_html__( 'Add More', 'gym-builder' ) : $field['button'];
        echo '<div class="gym-builder-postmeta-repeater-addmore"><button>'. esc_html($buttontext) .'</button></div></td></tr>';
    }

    private function display_single_field( $key, $field, $post_id, $default = false ){
        $desc = '';
        if ( !empty( $field['desc'] ) ){
            $desc = '<div class="gym-builder-postmeta-desc">' . wp_kses_post( $field['desc'] ) . '</div>';
        }

        $container_attr = '';
        if ( !empty( $field['required'] ) ) {

            $container_attr .= ' class="gym-builder-postmeta-dependent"';
	        foreach ($field['required'] as  $x=>$v){
				$data_required = $x;
		        $dependent_value = implode(" ", $v);
	        }
            $container_attr .= ' data-required="'. esc_attr($data_required) .'"';
            $container_attr .= ' data-required-value="'.esc_attr($dependent_value).'"';
        }

        // Display Title
        if( $field['type'] == 'header' ){
            $default = empty( $field['default'] ) ? 'h1' : $field['default'];
            echo '<tr'.esc_attr($container_attr).'><td colspan="2"><' . esc_html( $default ) . '>' . esc_html( $field['label'] ) . '</' . esc_html( $default ) . '>' . $desc;
        }
        elseif( empty( $field['label'] ) ){
            echo '<tr'.esc_attr($container_attr).'><td colspan="2">';
        }
        else{
            Functions::print_html('<tr'."$container_attr".'><th><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>',false);
        }

        // Set default value
        if ( !$default ) {
            $default = get_post_meta( $post_id, $key, true );
        }
        
        if ( $field['type'] != 'multi_checkbox' && empty( $default ) && !empty( $field['default'] ) ) {

            $default = $field['default'];
        }

        // class
        if ( !empty( $field['class'] ) ) {
            $class = 'class="gym-builderfm-meta-field '. esc_attr( $field['class'] ). '"';
        }
        else {
            $class = 'class="gym-builderfm-meta-field"';
        }

        // Display input
        if ( method_exists( $this, $field['type'] ) ) {
            $this->{$field['type']}( $key, $field, $default, $class );
            echo wp_kses_post($desc);
        }

        echo '</td></tr>';
    }

    public function text( $key, $field, $default, $class ){
        echo '<input type="text"'. esc_attr($class) .
        ' name="' . esc_attr( $key ) . '"'.
        ' id="' . esc_attr( $key ) . '"'.
        ' value="' . esc_attr( $default ) . ''.
        '" />';
    }

	public function multi_select( $key, $field, $default, $class ){
		if ( empty( $default ) ) {
			$default = array();
		}
		echo '<select class="gym-builder-multi-select gym-builder-select2" data-placeholder=" ' . esc_attr__( 'Click here to select options', 'gym-builder' ) . '" multiple="multiple"'.
		     ' name="' . esc_attr( $key ) . '[]"'.
		     ' id="' . esc_attr( $key ) . '">';
		foreach ( $field['options'] as $key => $value ) {
			echo '<option',
			in_array( $key, $default ) ? ' selected="selected"' : '',
				' value="' . esc_attr( $key ) . '"'.
				'>' .
				esc_html( $value ) .
				'</option>';
		}
		echo '</select>';
	}

    public function number( $key, $field, $default, $class ){
        echo '<input type="number"'. esc_attr($class) .
        ' name="' . esc_attr( $key ) . '"'.
        ' id="' . esc_attr( $key ) . '"'.
        ' value="' . esc_attr( $default ) . '"'.
        ' step="any"'.
        ' />';
    }

    public function textarea( $key, $field, $default, $class ){
        echo '<textarea '. esc_attr($class) .
        ' name="' . esc_attr( $key ) . '"'.
        ' id="' . esc_attr( $key ) . '"'.
        '>'.
        esc_textarea( $default ) . 
        '</textarea>';
    }

    public function textarea_html( $key, $field, $default, $class ){
        echo '<textarea '. esc_attr($class) .
        ' name="' . esc_attr( $key ) . '"'.
        ' id="' . esc_attr( $key ) . '"'.
        '>'.
        esc_textarea( $default ) . 
        '</textarea>';
    }

    public function select( $key, $field, $default, $class ){
        echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '"'. esc_attr($class).'>';
        foreach ( $field['options'] as $key => $value ) {
            echo '<option',
            $default == $key ? ' selected="selected"' : '',
            ' value="' . esc_attr( $key ) . '"'.
            '>' .
            esc_html( $value ) . 
            '</option>';
        }
        echo '</select>';
    }

	public function radio( $key, $field, $default, $class ){

		foreach ( $field['options'] as $value => $title ) {
			$id = $key . '_' . $value;

			echo '<span class="gym-builder-postmeta-radio"><input type="radio"'. $class .
			     ' name="' . esc_attr( $key ) . '"'.
			     ' id="' . esc_attr( $id ) . '"'.
			     ' value="' . esc_attr( $value ) . '"',
			$default == $value ? ' checked="checked"' : '',
				' /> '.
				'<label '.
				'for="' . esc_attr( $id ) . '">'.
				esc_html( $title ).
				'</label></span>';
		}
	}
	public function image_radio( $key, $field, $default, $class ){

		foreach ( $field['options'] as $index => $value ) {

				$id = $key . '_' . $value['title'];
				$img_path=self::get_file_locations('plugin_url').'assets/admin/layouts/'.$value['img_source'].'.png';
				echo '<span class="gym-builder-postmeta-image-radio"><input type="radio"'. $class .
				     ' name="' . esc_attr( $key ) . '"'.
				     ' id="' . esc_attr( $id ) . '"'.
				     ' value="' . esc_attr( $index ) . '"',
				$default == $index ? ' checked="checked"' : '',
					' /> '.
					'<div class="image"><img src="'.$img_path.'" alt="'.$value['title'].'"/></div>'.
					'<label class="image-radio-label" '.
					'for="' . esc_attr( $id ) . '">'.
					esc_html( $value['title'] ).
					'</label></span>';


		}

	}

	public function checkbox( $key, $field, $default, $class ){

		echo '<input type="checkbox"'.
		     ' name="' . esc_attr( $key ) . '"'.
		     ' id="' . esc_attr( $key ) . '"',
		$default ? ' checked="checked"' : '',
		'/>';
	}
	public function color_picker( $key, $field, $default, $class ){
        echo '<input type="text" class="gym-builder-metabox-picker gym-builder-metabox-colorpicker" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $default ) . '" />';
    }

    public function date_picker( $key, $field, $default, $class ){
        $format = isset( $field['format'] ) ? $field['format'] : 'MM dd, yy';
        echo '<input type="text" data-format="' . esc_attr($format) . '" class="gym-builder-metabox-picker gym-builder-metabox-datepicker" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $default ) . '" />';
    }

    public function time_picker( $key, $field, $default, $class ){
        echo '<input type="text" class="gym-builder-metabox-picker gym-builder-metabox-timepicker" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $default ) . '" />';
    }

    public function time_picker_24( $key, $field, $default, $class ){
        echo '<input type="text" class="gym-builder-metabox-picker gym-builder-metabox-timepicker-24" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $default ) . '" />';
    }
}