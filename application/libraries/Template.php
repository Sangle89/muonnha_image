<?php
/*
Authot: Le Van Sang
Email: slevan89@gmail.com
Phone: 0906 493 124
*/
class Template {
    private $CI;
    private $viewdata = array();
    private $template = "default";
    private $layout = 'default_template';
    function __construct() {
        $this->CI =& get_instance();
        $this->viewdata = array(
            'title'       => '',
            'keywords'    => '',
            'description' => '',
            'stylesheet'  => array(),
            'javascript'  => array()
        );
    }
    
    function _Set_Template($template = 'default') {
        $this->template = $template;
    }
    
    function _Set_Default_Layout($layout = 'default_template') {
        $this->layout = $layout;
    }
    
    function _Set_Stylesheet($data, $media = 'screen') {
        if(is_array($data)) {
            foreach($data as $style) {
                $this->viewdata['stylesheet'][] = array(
                    'file_path' => $style,
                    'media' => $media
                );
            }
        }else{
            $this->viewdata['stylesheet'][] = array(
                'file_path' => $data,
                'media' => $media
            );
        }
    }
    
    function _Get_Stylesheet() {
        return $this->viewdata['stylesheet'];
    }
    
    //Set javascript
    function _Set_Javascript($data) {
        if(is_array($data)) {
            foreach($data as $js) {
                $this->viewdata['javascript'][] = array(
                    'file_path' => $js
                );
            }
        }else{
            $this->viewdata['stylesheet'][] = array(
                'file_path' => $data
            );
        }
    }
    
    function _Set_Script($data) {
        $script = '<script type="text/javascript">
//<![CDATA[
' . $data . '
//]]>
</script>';
        $this->viewdata['scripts'][] = $script;
    }
    
    function _Set_Config($config) {
        foreach($config as $key => $value) {
            if($key == 'title') {
                $this->viewdata[$key] = $value;
            }elseif($key == 'keywords') {
                $this->viewdata[$key] = $value;
            }elseif($key == 'description') {
                $this->viewdata[$key] = $value;
            }
        }
    }
    
    //Lay toan bo file javascript
    function _Get_Javascript() {
        return $this->viewdata['javascript'];
    }
    
    function _Set_Title($title = '') {
        $this->viewdata['title'] = $title;
    }
    
    function _Get_Title() {
        return $this->viewdata['title'];
    }
    
    function _Set_Keywords($string = '') {
        $this->viewdata['keywords'] = string_sanitize($string);
    }
    
    function _Set_Description($string = '') {
        $this->viewdata['description'] = $string;
    }
    
    function _Set_View($view, $data = array()) {
        $content = $this->CI->load->view($this->template . '/' . $view, $data, TRUE);
        $this->viewdata['content_for_website'] = $content;
    }
    
    function _Get_View() {
        return $this->viewdata['content_for_website'];
    }
    
    function _Set_Data($key, $value, $appent = false) {
        $this->viewdata[$key] = $value;
    }
    
    function _Get_Data($key) {
        return $this->viewdata[$key];
    }
    
    function _Render() {
        $this->CI->load->view($this->template . '/' . $this->layout, $this->viewdata);
    }
}