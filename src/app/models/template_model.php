<?php

class template_model extends Model {

    /**
     * Load template meta
     * @param string $template_name Name of template
     * @return array containing meta of template
     */
    function get_meta($template_name) {
        if (empty($template_name)) {
            return false;
        }
        $template_path = APPPATH . "views/templates/$template_name";
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

}
