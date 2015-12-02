<?php

class template_model extends Model {

    function get_view_name($template_name) {
        return "templates/" . $template_name . "/index";
    }

    /**
     * Load template meta
     * @param string $template_name Name of template
     * @return array|false containing meta of template
     */
    function get_meta($template_name) {
        if (empty($template_name)) {
            return false;
        }
        $template_path = APPPATH . "views/templates/" . $template_name;
        $meta_path = $template_path . "/meta.json";

        if (!(is_dir($template_path) && is_file($meta_path))) {
            return false;
        }

        $meta_json = file_get_contents($meta_path);

        if (!$meta_json) {
            return false;
        }
        return json_decode($meta_json, true);
    }

    function get_template_list() {
        $file_list = scandir(APPPATH . 'views/templates/');
        foreach ($file_list as $i=>$file) {
            if (!is_dir(APPPATH . "views/templates/$file")
                || strpos($file, '.') === 0
            ) {
                unset($file_list[$i]);
            } else {
                $file_list[$i] = pathinfo($file)['filename'];
            }
        }
        return array_values(array_unique($file_list));
    }

}
