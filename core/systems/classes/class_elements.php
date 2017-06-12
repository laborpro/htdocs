<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01.03.2017
 * Time: 21:00
 */
class elements{
    public function button($caption, $id = '', $class = '', $style = '', $br = '', $option = ''){
        $html = '<div class="button '.$class.'" id="'.$id.'" style="'.$style.'" '.$option.'>'.$caption.'</div>'.($br != '' ? '<br>' : '');

        return $html;
    }

    public function menu_button($caption, $id = '', $class = '', $style = '', $br = '', $link = ''){
        $html = ($link != '' ? '<a href="'.$link.'">' : '').'<div class="menu_button '.$class.'" id="'.$id.'" style="'.$style.'">'.$caption.'</div>'.($link != '' ? '</a>' : '' ).($br != '' ? '<br>' : '');

        return $html;
    }

    public function title($title, $id = ''){
        $html = '<div id="'.$id.'" class="page_title">'.$title.'</div>';

        return $html;
    }

    public function small_title($title, $id = ''){
        $html = '<div id="'.$id.'" class="small_title">'.$title.'</div>';

        return $html;
    }

    public function input($caption = '', $id = '', $class = '', $catch_enter = '', $after_catch_function = '', $password = '', $style = '', $maxlength = '', $other = ''){
        $html = '';
        if ($caption != ''){
            $html = '<div class="caption" style="margin-top: 5px;">'.$caption.'</div>';
        }
        $html .= '<input type="'.($password != '' ? 'password' : 'text').'" id="'.$id.'" class="input '.$class.'" '.($maxlength != '' ? 'maxlength='.$maxlength : '').' style="'.$style.'" '.($other != '' ? $other : '').' '.($catch_enter != '' ? 'onkeydown = "if (event.keyCode == 13) '.$after_catch_function.'()"' : '').'><br>';

        return $html;
    }

    public function date_input($caption = '', $id = '', $class = '', $style = '', $value = ''){
        $html = '';
        if ($caption != ''){
            $html = '<div class="caption" style="margin-top: 5px;">'.$caption.'</div>';
        }
        $html .= '<input id="'.$id.'" type="date" class="input '.$class.'" name="calendar" style="'.$style.'" value="' . ($value == '' ? '' : date('Y-m-d', strtotime(date('Y-m-d').$value))) . '"><br>';

        return $html;
    }

    public function company_item($caption, $id = '', $class = '', $style = '', $option = '', $can_delete = 1){
        $html = '<div class="list_item" id="'.$id.'" style="'.$style.'" '.$option.'>';
        $html .= '<div style="display: inline-block;vertical-align: middle;">';
        $html .= $this->button('Включить управление', '', 'company_turn_control '.$class, 'margin-right: 10px;margin-top: 5px;');
        $html .= '</div>';
        $html .= '<div style="display: inline-block;vertical-align: middle;">';
        $html .= $caption;
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function item($caption, $id = '', $class = '', $style = '', $option = '', $can_delete = 1){
        $html = '<div class="list_item" id="'.$id.'" class="'.$class.'" style="'.$style.'" '.$option.'>'.$caption;
        $html .= '</div>';

        return $html;
    }

    public function item_with_checkbox($caption, $id = '', $class = '', $style = '', $option = '', $can_delete = 1){
        $html = '<div class="list_item" id="'.$id.'" class="'.$class.'" style="'.$style.'" '.$option.'>';

        $html .= '<div style="display: inline-block;vertical-align: middle;margin-right: 7px;">';
        $html .= '<input type="checkbox" style="zoom: 2;" '.($class != '' ? 'class="'.$class.'"' : '').'/>';
        $html .= '</div>';

        $html .= '<div style="display: inline-block;vertical-align: middle;">';
        $html .= $caption;
        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

    public function combobox($caption = '', $options_array, $id = '', $class = '', $style = '', $option = ''){
        $html = '';
        if ($caption != ''){
            $html .= '<div class="caption '.$class.'" style="margin-top: 5px;">'.$caption.'</div>';
        }
        $html .= '<select id="'.$id.'" class="'.$class.'" '.$option.' style="'.$style.'">';
        foreach($options_array as $options){
            $html .= '<option value="'.$options['value'].'" '.($options['selected'] != '' ? 'selected' : '').' '.($options['disabled'] != '' ? 'disabled' : '').'>'.$options['caption'].'</option>';
        }
        $html .= '</select>';

        return $html;
    }



//    public function avatar($id = '', $class = '', $style = '', $br = '', $option = ''){
//        global $current_template;
//        $img_name = "deactivated_200.png";
//        $url ='/templates/' .$current_template . '/images/' . $img_name;
//        $html = '<div class="avatar '.$class.'" src="'.$url.'" id="'.$id.'" style="'.$style.'" '.$option.'>';
//        return $html;
//    }
}