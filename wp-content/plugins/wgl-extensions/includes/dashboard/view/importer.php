<?php

$wp_content_url = trailingslashit(wp_normalize_path((is_ssl() ? str_replace('http://', 'https://', WGL_CORE_URL) : WGL_CORE_URL)));
$dir_name = 'includes/wgl_importer/';
$demo_data_url = trailingslashit($wp_content_url . $dir_name) . 'demo-data/';

echo '<div class="wgl_importer">';
$nonce = wp_create_nonce("wgl_importer");
$imported = false;
$field['wgl_demo_imports'] = apply_filters("wgl_importer_files", array());
echo '<div class="theme-browser">';
    echo '<div class="themes" data-nonce="' . $nonce . '">';
        if (!empty($field['wgl_demo_imports'])) {

            $get_licence = get_option('wgl_licence_validated');
            $get_licence = empty($get_licence) ? get_option(WGL_Theme_Verify::get_instance()->item_id) : $get_licence;

            if (!empty($get_licence)) {
                $extra_class = 'not-imported';
            } else {
                $extra_class = 'not-licence';
            }
            echo '<div class="themes__sidebar">';
            foreach ($field['wgl_demo_imports'] as $section => $imports) {
                if (empty($imports)) {
                    continue;
                }

                if ('full' === $imports['directory']
                ) {
                    echo '<div class="theme-screenshot">';
                    if (isset($imports['image']) && !empty($imports['image'])) {
                        echo '<img class="wgl_image" src="' . esc_attr(esc_url($demo_data_url . $imports['directory'] . '/' . $imports['image'])) . '"/>';
                    }
                    echo '</div>';
                }
            }
            echo '</div>';

            echo '<div class="themes__content">';

            echo '<div class="import__select">';
                echo '<h2>'.esc_html__('Demo Importer', 'wgl-extensions').'</h2>';
                echo '<p>'.esc_html__('To avoid any conflicts an Installation should be done in clear environments.', 'wgl-extensions').'</p>';
                echo '<p>'.esc_html__('Images are not included in demo import. If you want to use images from demo content, you should check the license for every single image.', 'wgl-extensions').'</p>';
                                
                echo '<div class="wgl-importer-choose">';
                echo '<label class="control-label">', esc_html__('Import Option', 'wgl-extensions'), '</label>';
                echo '<select class="form-control input-sm select2 wgl_import_option" name="wgl_import_option">';

                $all = '<option value="all">'. esc_html__('Entire demo data', 'wgl-extensions') . '</option>';
                $all = apply_filters( 'wgl_all_entire_demo', $all );

                $partial = '<option value="partial">'.  esc_html__('Partial', 'wgl-extensions') . '</option>';
                $partial = apply_filters( 'wgl_partial_demo', $partial );

                echo $all;
                echo $partial;
                
                echo '</select>';
                echo '</div>';

                foreach ($field['wgl_demo_imports'] as $section => $imports) {
                    if (empty($imports)) {
                        continue;
                    }

                    if(
                        'cpt' !== $imports['directory']
                        && 'full'!== $imports['directory']
                        && 'pages'!== $imports['directory']
                    ){
                        if (isset($imports['custom_pages'])) {
                            $class_directory = $imports['directory'];
                            echo '<div class="wrap-importer pages_importer pages' . '_' . $class_directory . ' ' .  $extra_class . '">';

                            echo '<div class="theme-list wgl-custom-pages custom-pages'.$class_directory.'">';
                            echo '<div class="container">';
                            echo '<label class="control-label">' . esc_html__('Which pages do you want to import?', 'wgl-extensions') . '</label>';
                            echo '<select class="form-control input-sm select2 select2-multiple select2-custom-pages" multiple>';
                            foreach ($imports['custom_pages'] as $f) {
                                $f = str_replace('.xml', '', $f);
                                echo '<option value="' . $f . '">' . strtoupper($f) . '</option>';
                            }
                            echo '</select>';
                            echo '</div>';
                            echo '</div>';

                            echo '</div>';
                        }
                        echo '<div class="wrap-importer partial-options_' . $imports['directory'] . '"><label class="checkbox-container">' . esc_html__('Widgets', 'wgl-extensions') . '<input type="checkbox" id="widgets'.$imports['directory'].'" name="widgets"><span class="checkmark"></span></label></div>';
                        echo '<div class="wrap-importer partial-options_' . $imports['directory'] . '"><label class="checkbox-container">' . esc_html__('Theme Options', 'wgl-extensions') . '<input type="checkbox" id="options'.$imports['directory'].'" name="options"><span class="checkmark"></span></label></div>';
                        if (class_exists('RevSlider')) {
                            echo '<div class="wrap-importer partial-options_' . $imports['directory'] . '"><label class="checkbox-container">' . esc_html__('Revolution Sliders', 'wgl-extensions') . '<input type="checkbox" id="rev-slider'.$imports['directory'].'" name="rev-slider"><span class="checkmark"></span></label></div>';
                        }
                        if (isset($imports['cpt'])) {
                            echo '<div class="wrap-importer partial-options_' . $imports['directory'] . ' сpt-wrapper'.$imports['directory'].'">';
                            echo '<label class="control-label cpt-label__headings">' . esc_html__('Which Custom post types do you want to import?', 'wgl-extensions') . '</label>';
                            foreach ($imports['cpt'] as $k => $f) {
                                $f = str_replace('.xml', '', $f);
                                echo '<label class="checkbox-container">' . esc_html__($f, 'wgl-extensions') . '<input type="checkbox" name="' . esc_attr($f) . '" data-folder="' . esc_attr($k) . '"><span class="checkmark"></span></label>';
                            }
                            echo '</div>';
                        }
                    }

                    $class_directory = ' ' . $imports['directory'];
                    echo '<div class="wrap-importer ' . $extra_class . $class_directory . '"  data-type="' . esc_attr($imports['directory']) . '" data-demo-id="' . esc_attr($section) . '">';
                    if ('pages' === $imports['directory']
                    ) {
                        echo '<div class="theme-list wgl-custom-pages custom-pages">';
                        echo '<div class="container">';
                        echo '<label class="control-label">' . esc_html__('Which pages do you want to import?', 'wgl-extensions') . '</label>';
                        echo '<select class="form-control input-sm select2 select2-multiple select2-custom-pages" multiple>';
                        foreach ($imports['custom_pages'] as $f) {
                            $f = str_replace('.xml', '', $f);
                            echo '<option value="' . $f . '">' . strtoupper($f) . '</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }

                echo '<div class="wrap-importer partial-options"><label class="checkbox-container">' . esc_html__('Widgets', 'wgl-extensions') . '<input type="checkbox" id="widgets" name="widgets"><span class="checkmark"></span></label></div>';
                echo '<div class="wrap-importer partial-options"><label class="checkbox-container">' . esc_html__('Theme Options', 'wgl-extensions') . '<input type="checkbox" id="options" name="options"><span class="checkmark"></span></label></div>';
                if (class_exists('RevSlider')) {
                    echo '<div class="wrap-importer partial-options"><label class="checkbox-container">' . esc_html__('Revolution Sliders', 'wgl-extensions') . '<input type="checkbox" id="rev-slider" name="rev-slider"><span class="checkmark"></span></label></div>';
                }
                foreach ($field['wgl_demo_imports'] as $section => $imports) {
                    if (empty($imports)) {
                        continue;
                    }
                    if ('cpt' === $imports['directory']
                    ) { 
                        echo '<div class="wrap-importer partial-options сpt-wrapper">';
                        echo '<label class="control-label cpt-label__headings">' . esc_html__('Which Custom post types do you want to import?', 'wgl-extensions') . '</label>';
                        foreach ($imports['cpt'] as $k => $f) {
                            $f = str_replace('.xml', '', $f);
                            echo '<label class="checkbox-container">' . esc_html__($f, 'wgl-extensions') . '<input type="checkbox" name="'.esc_attr($f).'" data-folder="'.esc_attr($k).'"><span class="checkmark"></span></label>';
                        }
                        echo '</div>';
                    }
                }

                echo '<div class="theme-actions">';
                    echo '<div class="wgl-importer-buttons">';
                        if (!empty($get_licence)) {
                            echo '<span class="spinner">' . esc_html__('Please Wait...', 'wgl-extensions') . '</span>';
                            echo '<span class="button-primary importer-button import-demo-data">' . __('Import Demo', 'wgl-extensions') . '</span>';
                        }else{
                            echo '<span class="button-primary not-license"  data-url="'.esc_url( admin_url( 'admin.php?page=wgl-activate-theme-panel' ) ).'">' . __( 'Unlock', 'thepascal-core' ) . '</span>';
                        }
                    echo '</div>';
                echo '</div>';
                echo '<div class="overlay__import"></div>';
            echo '</div>';

            echo '<div id="info-opt-info-error">';
                echo '<i class="fa fa-exclamation-circle"></i>';
                echo '<div class="error_message"></div>';
                echo '<div class="error_description">';
                    echo '<a target="_blank" href="https://www.wpbeginner.com/wp-tutorials/how-to-fix-curl-error-28-connection-timed-out-after-x-milliseconds/">';
                    echo esc_html__('Read this article to resolve this issue: ', 'wgl-extensions');
                    echo '</a>';
                    echo '<span>';
                    echo esc_html__( 'or install demo content without images' , 'wgl-extensions' );
                    echo '</span>';
                    echo '<div class="error_description-checkbox without_img"><label class="checkbox-error"><input type="checkbox" id="without_image" name="without_image"><span class="checkmark"></span>' . esc_html__('Install demo-content without images', 'wgl-extensions') . '</label></div>';
                echo '</div>';
            echo '</div>';

            echo '<div class="importer_status clear" style="opacity:0;">';
                echo '<div class="progressbar">';
                    echo '<div class="progressbar_content" style="width: 0%;">';
                        echo '<div class="progressbar_value">0%</div>';
                    echo '</div>';
                    echo '<div class="progressbar_condition">';
                        echo '<div class="progressbar_filled" style="width: 0%;"></div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            echo '<div id="info-opt-info-success">';
                echo '<i class="fa fa-check"></i>';
                esc_html_e('Import is completed', 'wgl-extensions');
            echo '</div>';

            echo '</div>';
        } else {
            echo "<h5>" . esc_html__('No Demo Data Provided', 'wgl-extensions') . "</h5>";
        }
    echo '</div>';
echo '</div>';
echo '<div class="clear"></div>';
