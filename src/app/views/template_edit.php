<?php
    function get_field($name, $meta, $data) {
        $opt = empty($meta['optional']) ? 'required' : '';
        $required_notice = empty($meta['optional']) ? '(*)' : '(optional)';

        $out = "<label for='$name'>$meta[name] $required_notice: ";

        switch ($meta['type']) {
            case 'text':
                default:
                    $out .= "<input type='text' name='data[$name]' id='$name' value='" . htmlentities($data, ENT_QUOTES) . "' $opt/>";
        }
        return $out . "</label>";
    }
?>
<?php
    if (is_array($template_meta)):
        foreach ($template_meta as $name => $meta):
            echo get_field($name, $meta, $data[$name]);
        endforeach;
    endif;
?>
