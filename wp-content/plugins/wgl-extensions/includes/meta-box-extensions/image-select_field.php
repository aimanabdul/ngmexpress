<?php
if ( class_exists( 'RWMB_Field' ) ){
    /**
     * The image select field which behaves similar to the radio field but uses images as options.
     *
     * @package Meta Box
     */

    /**
     * The image select field class.
     */
    class RWMB_WGL_Image_Select_Field extends RWMB_Field {
        public static $field_attr;

        /**
         * Enqueue scripts and styles.
         */
        public static function admin_enqueue_scripts() {
            wp_enqueue_style( 'rwmb-image-select', RWMB_CSS_URL . 'image-select.css', array(), RWMB_VER );
            wp_enqueue_script( 'rwmb-image-select', RWMB_JS_URL . 'image-select.js', array( 'jquery' ), RWMB_VER, true );
        }

        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html( $meta, $field ) {
            $html = array();
            $tpl  = '<label class="rwmb-image-select"><img src="%s"><input type="%s" class="rwmb-image_select" name="%s" value="%s"%s%s><div class="rwmb-image_select-desc">%s</div></label>';

            $meta = (array) $meta;
            foreach ( $field['options'] as $value => $image ) {
                $html[] = sprintf(
                    $tpl,
                    $image,
                    $field['multiple'] ? 'checkbox' : 'radio',
                    $field['field_name'],
                    $value,
                    checked( in_array( $value, $meta ), true, false ),
                    self::render_attributes( $field['attributes'] ),
                    $value
                );
            }

            $attributes = self::get_attributes( $field );
            // $html['attr'] = '<div class="rwmb-image-select-attributes" '.self::render_attributes( $attributes ).'></div>';

            return '<div class="rwmb-image-select-wrap">'.implode( ' ', $html ).'</div>';
        }

        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize( $field ) {
            $field = parent::normalize( $field );
            $field['field_name'] .= $field['multiple'] ? '[]' : '';

            return $field;
        }


            /**
             * Get the attributes for a field.
             *
             * @param array $field The field parameters.
             * @param mixed $value The attribute value.
             * @return array
             */
            public static function get_attributes( $field, $value = null ) {
                $attributes           = parent::get_attributes( $field, $value );
                $attributes = wp_parse_args( $attributes, array() );

                return $attributes;
            }

        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value( $field, $value, $args, $post_id ) {
            return sprintf( '<img src="%s">', esc_url( $field['options'][ $value ] ) );
        }
    }
}

?>