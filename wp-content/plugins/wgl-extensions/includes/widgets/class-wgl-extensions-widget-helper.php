<?php
if (!class_exists('WGL_Widgets_Helper')) {
    /**
     * WGL_Widgets_Helper
     *
     *
     * @package wgl-extensions\includes\widgets
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    abstract class WGL_Widgets_Helper extends WP_Widget
    {

        private $slug;
        private $label;
        private $options;

        public function __construct() {
            $this->create_widget();

            // call WP_Widget to create the widget
            parent::__construct( $this->slug, $this->label, $this->options );
        }

        abstract public function create_widget();

        /**
         * Create Widget
         *
         * Creates a new widget and sets it's labels, description, fields and options
         *
         * @access   public
         * @param    array
         * @return   void
         * @since    1.0
         */
        public function create($args = [])
        {
            // settings some defaults
            $defaults = array(
                'label'        => '',
                'description'  => '',
                'fields'       => array(),
                'options'      => array(),
             );

            // parse and merge args with defaults
            $args = wp_parse_args( $args, $defaults );

            // extract each arg to its own variable
            extract( $args, EXTR_SKIP );

            // set the widget vars
            $this->slug    = sanitize_title( $label );
            $this->fields  = $fields;
            $this->label   = $label;

            // check options
            $this->options = array( 'classname' => $this->slug, 'description' => $description );
            if ( ! empty( $options ) ) $this->options = array_merge( $this->options, $options );
        }

        /**
         * Form
         *
         * Creates the settings form.
         *
         * @access   public
         * @param    array
         * @return   void
         * @since    1.0
         */

        public function form($instance)
        {
            $this->instance = $instance;
            $form = $this->create_fields();

            echo $form;
        }

        /**
         * Update Fields
         *
         * @access   public
         * @param    array
         * @param    array
         * @return   array
         * @since    1.0
         */

        public function update($new_instance, $old_instance)
        {
            $instance = $old_instance;

            $this->before_update_fields();

            foreach ($this->fields as $key) {
                $slug = $key['id'];
                if (isset($key['validate'])) {
                    if (
                        isset($new_instance[$slug])
                        && false === $this->validate($key['validate'], $new_instance[$slug])
                    )
                    {
                        return $instance;
                    }
                }

                if (isset($key['filter']))
                    $instance[$slug] = isset($new_instance[$slug]) ? $this->filter($key['filter'], $new_instance[$slug]) : '';
                else
                    $instance[$slug] = isset($new_instance[$slug]) ? strip_tags($new_instance[$slug]) : '';
            }

            return $this->after_validate_fields($instance);
        }

        /**
         * Before Validate Fields
         *
         * Allows to hook code on the update.
         *
         * @access   public
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function before_update_fields()
        {
            return;
        }

        /**
         * After Validate Fields
         *
         * Allows to modify the output after validating the fields.
         *
         * @access   public
         * @param    string
         * @return   string
         * @since    1.0
         */

        public function after_validate_fields($instance = "")
        {
            return $instance;
        }

        /**
         * Validate
         *
         * @access   public
         * @param    string
         * @param    string
         * @return   boolean
         * @since    1.0
         */

        public function validate($rules, $value)
        {
            $rules = explode('|', $rules);

            if (empty($rules) || count($rules) < 1)
                return true;

            foreach ($rules as $rule) {
                if (false === $this->do_validation($rule, $value))
                    return false;
            }

            return true;
        }

        /**
         * Filter
         *
         * @access   public
         * @param    string
         * @param    string
         * @return   void
         * @since    1.0
         */

        public function filter($filters, $value)
        {
            $filters = explode('|', $filters);

            if (empty($filters) || count($filters) < 1)
                return $value;

            foreach ($filters as $filter)
                $value = $this->do_filter($filter, $value);

            return $value;
        }


        /**
         * Do Validation Rule
         *
         * @access   public
         * @param    string
         * @param    string
         * @return   boolean
         * @since    1.0
         */
        public function do_validation($rule, $value = "")
        {
            switch ($rule) {

                case 'alpha':
                    return ctype_alpha($value);
                    break;

                case 'alpha_numeric':
                    return ctype_alnum($value);
                    break;

                case 'alpha_dash':
                    return preg_match('/^[a-z0-9-_]+$/', $value);
                    break;

                case 'numeric':
                    return ctype_digit($value);
                    break;

                case 'integer':
                    return (bool) preg_match('/^[\-+]?[0-9]+$/', $value);
                    break;

                case 'boolean':
                    return is_bool($value);
                    break;

                case 'email':
                    return is_email($value);
                    break;

                case 'decimal':
                    return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $value);
                    break;

                case 'natural':
                    return (bool) preg_match('/^[0-9]+$/', $value);
                    return;

                case 'natural_not_zero':
                    if (!preg_match('/^[0-9]+$/', $value)) return false;
                    if ($value == 0) return false;
                    return true;
                    return;

                default:
                    if (method_exists($this, $rule))
                        return $this->$rule($value);
                    else
                        return false;
                    break;
            }
        }


        /**
         * Do Filter
         *
         * @access   public
         * @param    string
         * @param    string
         * @return   boolean
         * @since    1.0
         */
        public function do_filter($filter, $value = "")
        {
            switch ($filter) {
                case 'strip_tags':
                    return strip_tags($value);
                    break;

                case 'wp_strip_all_tags':
                    return wp_strip_all_tags($value);
                    break;

                case 'esc_attr':
                    return esc_attr($value);
                    break;

                case 'esc_url':
                    return esc_url($value);
                    break;

                case 'esc_textarea':
                    return esc_textarea($value);
                    break;

                default:
                    if (method_exists($this, $filter))
                        return $this->$filter($value);
                    else
                        return $value;
                    break;
            }
        }

        /**
         * Create Fields
         *
         * Creates each field defined.
         *
         * @access   public
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_fields($out = "")
        {

            $out = $this->before_create_fields($out);

            if (!empty($this->fields)) {
                foreach ($this->fields as $key)
                    $out .= $this->create_field($key);
            }

            $out = $this->after_create_fields($out);

            return $out;
        }

        /**
         * Before Create Fields
         *
         * Allows to modify code before creating the fields.
         *
         * @access   public
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function before_create_fields($out = "")
        {
            return $out;
        }

        /**
         * After Create Fields
         *
         * Allows to modify code after creating the fields.
         *
         * @access   public
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function after_create_fields($out = "")
        {
            return $out;
        }

        /**
         * Create Fields
         *
         * @access   public
         * @param    string
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field($key, $out = "")
        {

            /* Set Defaults */
            $key['std'] = isset($key['std']) ? $key['std'] : "";

            $slug = $key['id'];

            if (isset($this->instance[$slug]))
                $key['value'] = empty($this->instance[$slug]) ? '' : strip_tags($this->instance[$slug]);
            else
                unset($key['value']);

            /* Set field id and name  */
            $key['_id'] = $this->get_field_id($slug);
            $key['_name'] = $this->get_field_name($slug);

            /* Set field type */
            if (!isset($key['type'])) $key['type'] = 'text';

            /* Prefix method */
            $field_method = 'create_field_' . str_replace('-', '_', $key['type']);

            /* Check for <p> Class */
            $p = (isset($key['class-p'])) ? '<p class="' . $key['class-p'] . '">' : '<p>';

            /* Run method */
            if (method_exists($this, $field_method))
                return $p . $this->$field_method($key) . '</p>';
        }

        /**
         * Field Text
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_text($key, $out = "")
        {
            $out .= $this->create_field_label($key['name'], $key['_id']) . '<br/>';

            $out .= '<input type="text" ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            $value = isset($key['value']) ? $key['value'] : $key['std'];

            $out .= 'id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '" value="' .  esc_attr($value) . '" ';

            if (isset($key['size']))
                $out .= 'size="' . esc_attr($key['size']) . '" ';

            $out .= ' />';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }


        /**
         * Field Textarea
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_textarea($key, $out = "")
        {
            $out .= $this->create_field_label($key['name'], $key['_id']) . '<br/>';

            $out .= '<textarea ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            if (isset($key['rows']))
                $out .= 'rows="' . esc_attr($key['rows']) . '" ';

            if (isset($key['cols']))
                $out .= 'cols="' . esc_attr($key['cols']) . '" ';

            $value = isset($key['value']) ? $key['value'] : $key['std'];

            $out .= 'id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '">' . esc_html($value);

            $out .= '</textarea>';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }


        /**
         * Field Media Upload
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_media_image($key, $out = "")
        {
            $out .= $this->create_field_label($key['name'], $key['_id']) . '<br/>';

            $value = isset($key['value']) ? $key['value'] : $key['std'];
            $out .= '<img class="wgl_extensions_media_upload" src="'.(!empty($value) ? esc_url($value ) : '').'" style="max-width: 100%" />';
            $out .= '<input type="text" ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            $out .= 'id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '" value="' .  esc_attr($value) . '" ';

            $out .= ' />';

            $out .= '<a href="#" class="button wgl_extensions_media_upload">'.esc_html__('Upload', 'wgl-extensions').'</a>';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }


        /**
         * Field Checkbox
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_checkbox($key, $out = "")
        {
            $out .= $this->create_field_label($key['name'], $key['_id']);

            $out .= ' <input type="checkbox" ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            $out .= 'id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '" value="1" ';

            if ((isset($key['value']) && $key['value'] == 1) or (!isset($key['value']) && $key['std'] == 1))
                $out .= ' checked="checked" ';

            $out .= ' /> ';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }


        /**
         * Field Select
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_select($key, $out = "")
        {
            $out .= $this->create_field_label($key['name'], $key['_id']) . '<br/>';

            $out .= '<select id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '" ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            $out .= '> ';

            $selected = isset($key['value']) ? $key['value'] : $key['std'];

            foreach ($key['fields'] as $field => $option) {

                $out .= '<option value="' .  esc_attr($option['value']) . '" ';

                if (esc_attr($selected) == $option['value'])
                    $out .= ' selected="selected" ';

                $out .= '> ' . esc_html($option['name']) . '</option>';
            }

            $out .= ' </select> ';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }


        /**
         * Field Select with Options Group
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_select_group($key, $out = "")
        {

            $out .= $this->create_field_label($key['name'], $key['_id']) . '<br/>';

            $out .= '<select id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '" ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            $out .= '> ';

            $selected = isset($key['value']) ? $key['value'] : $key['std'];

            foreach ($key['fields'] as $group => $fields) {

                $out .= '<optgroup label="' . $group . '">';

                foreach ($fields as $field => $option) {
                    $out .= '<option value="' . esc_attr($option['value']) . '" ';

                    if (esc_attr($selected) == $option['value'])
                        $out .= ' selected="selected" ';

                    $out .= '> ' . esc_html($option['name']) . '</option>';
                }

                $out .= '</optgroup>';
            }

            $out .= '</select>';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }


        /**
         * Field Number
         *
         * @access   public
         * @param    array
         * @param    string
         * @return   string
         * @since    1.0
         */
        public function create_field_number($key, $out = "")
        {
            $out .= $this->create_field_label($key['name'], $key['_id']);

            $out .= '<input type="number" ';

            if (isset($key['class']))
                $out .= 'class="' . esc_attr($key['class']) . '" ';

            $value = isset($key['value']) ? $key['value'] : $key['std'];

            $out .= 'id="' . esc_attr($key['_id']) . '" name="' . esc_attr($key['_name']) . '" value="' .  esc_attr($value) . '" ';

            if (isset($key['size']))
                $out .= 'size="' . esc_attr($key['size']) . '" ';

            $out .= ' />';

            if (isset($key['desc']))
                $out .= '<br/><small class="description">' . esc_html($key['desc']) . '</small>';

            return $out;
        }

        /**
         * Field Label
         *
         * @access   public
         * @name     string
         * @id       string
         * @return   string
         * @since    1.0
         */
        public function create_field_label($name = "", $id = "")
        {
            return '<label for="' . esc_attr($id) . '">' . esc_html($name) . ':</label>';
        }

        /**
         * Register WGL Widgets
         *
         * @access   public
         *
         * @since    1.0
         */
        public function register()
        {
            register_widget(get_class($this));
        }
    } // class
}

if ( ! function_exists( 'wgl_widgets_helper_register' ) ) {
	function wgl_widgets_helper_register() {
        $widgets = apply_filters( 'wgl/register_widget_filter', [] );

		if (!empty($widgets)){
			foreach ( $widgets as $widget ) {
                (wgl_extensions_global())->add_widget(new $widget());
			}
		}
	}

	add_action('wgl/widgets_register', 'wgl_widgets_helper_register');
}