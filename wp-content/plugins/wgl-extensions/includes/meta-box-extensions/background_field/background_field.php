<?php
if (class_exists('RWMB_Field')) {
    /**
     * The background field.
     *
     * @package Meta Box.
     */

    /**
     * The Background field.
     */
    class RWMB_WGL_Background_Field extends RWMB_Field
    {
        /**
         * Enqueue scripts and styles.
         */
        public static function admin_enqueue_scripts()
        {
            wp_enqueue_style( 'rwmb-background', RWMB_CSS_URL . 'background.css', '', RWMB_VER );

            RWMB_Color_Field::admin_enqueue_scripts();
            wp_enqueue_script( 'rwmb-file-bg-input', plugin_dir_url( __FILE__ ) . '/background.js', ['jquery'], RWMB_VER, true );
        }

        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field settings.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
            $meta = wp_parse_args($meta, [
                'repeat' => '',
                'size' => '',
                'attachment' => '',
                'position' => '',
                'image' => '',
                'color' => '',
            ]);

            $output = '<div class="rwmb-background-row rwmb-background-select">';

            // Repeat.
            $repeat = RWMB_Select_Field::normalize([
                'type' => 'select',
                'id' => "{$field['id']}_repeat",
                'field_name' => "{$field['field_name']}[repeat]",
                'placeholder' => esc_attr__('-- Background Repeat --', 'meta-box'),
                'options' => [
                    'no-repeat' => esc_html__('No Repeat', 'meta-box'),
                    'repeat' => esc_html__('Repeat All', 'meta-box'),
                    'repeat-x' => esc_html__('Repeat Horizontally', 'meta-box'),
                    'repeat-y' => esc_html__('Repeat Vertically', 'meta-box'),
                    'inherit' => esc_html__('Inherit', 'meta-box'),
                ],
            ]);
            $output .= RWMB_Select_Field::html($meta['repeat'], $repeat);

            // Size.
            $size = RWMB_Select_Field::normalize([
                'type' => 'select',
                'id' => "{$field['id']}_size",
                'field_name' => "{$field['field_name']}[size]",
                'placeholder' => esc_attr__('-- Background Size --', 'meta-box'),
                'options' => [
                    'inherit' => esc_html__('Inherit', 'meta-box'),
                    'cover' => esc_html__('Cover', 'meta-box'),
                    'contain' => esc_html__('Contain', 'meta-box'),
                ],
            ]);
            $output .= RWMB_Select_Field::html($meta['size'], $size);

            // Attachment.
            $attachment = RWMB_Select_Field::normalize([
                'type' => 'select',
                'id' => "{$field['id']}_attachment",
                'field_name' => "{$field['field_name']}[attachment]",
                'placeholder' => esc_attr__('-- Background Attachment --', 'meta-box'),
                'options' => [
                    'fixed' => esc_html__('Fixed', 'meta-box'),
                    'scroll' => esc_html__('Scroll', 'meta-box'),
                    'inherit' => esc_html__('Inherit', 'meta-box'),
                ],
            ]);
            $output .= RWMB_Select_Field::html($meta['attachment'], $attachment);

            // Position.
            $position = RWMB_Select_Field::normalize([
                'type' => 'select',
                'id' => "{$field['id']}_position",
                'field_name' => "{$field['field_name']}[position]",
                'placeholder' => esc_attr__('-- Background Position --', 'meta-box'),
                'options' => [
                    'top left' => esc_html__('Top Left', 'meta-box'),
                    'top center' => esc_html__('Top Center', 'meta-box'),
                    'top right' => esc_html__('Top Right', 'meta-box'),
                    'center left' => esc_html__('Center Left', 'meta-box'),
                    'center center' => esc_html__('Center Center', 'meta-box'),
                    'center right' => esc_html__('Center Right', 'meta-box'),
                    'bottom left' => esc_html__('Bottom Left', 'meta-box'),
                    'bottom center' => esc_html__('Bottom Center', 'meta-box'),
                    'bottom right' => esc_html__('Bottom Right', 'meta-box'),
                ],
            ]);
            $output .= RWMB_Select_Field::html($meta['position'], $position);

            $output .= '</div><!-- .rwmb-background-row -->';
            $output .= '<div class="rwmb-background-row rwmb-background-image">';

            // Image.
            $image  = RWMB_File_Input_Field::normalize([
                'type' => 'file_input',
                'id' => "{$field['id']}_image",
                'field_name' => "{$field['field_name']}[image]",
                'placeholder' => esc_attr__('Background Image', 'meta-box'),
            ]);
            $output .= RWMB_File_Input_Field::html($meta['image'], $image);

            if (!empty($meta['image'])) {
                $output .= '<div class="metabox-screenshot"><img class="metabox-option-image" src="' . $meta['image'] . '"></div>';
            }

            $output .= '</div><!-- .rwmb-background-row -->';

            $output .= '<div class="rwmb-background-row rwmb-background-color">';

            // Color.
            $color  = RWMB_Color_Field::normalize([
                'type' => 'color',
                'id' => "{$field['id']}_color",
                'field_name' => "{$field['field_name']}[color]",
            ]);
            $output .= RWMB_Color_Field::html($meta['color'], $color);

            $output .= '</div><!-- .rwmb-background-row -->';
            $attributes = self::get_attributes($field);
            $output .= '<div class="background-attributes" ' . self::render_attributes($attributes) . '></div>';

            return $output;
        }

        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
            $output = '';
            $value = array_filter($value);
            foreach ($value as $key => $subvalue) {
                $subvalue = 'image' === $key ? 'url(' . esc_url($subvalue) . ')' : $subvalue;
                $output   .= 'background-' . $key . ': ' . $subvalue . ';';
            }

            return $output;
        }

        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The attribute value.
         * @return array
         */
        public static function get_attributes($field, $value = null)
        {
            $attributes = parent::get_attributes($field, $value);
            $attributes = wp_parse_args($attributes, []);

            return $attributes;
        }
    }
}
