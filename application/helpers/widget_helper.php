<?php
function slider() {
    $CI=&get_instance();
    $data['slides'] = $CI->slider_model->_Get_All(99,0,'active');
    $CI->load->view('default/modules/slider', $data);
}
function banner($position) {
    $CI=&get_instance();
    $data['banners'] = $CI->banner_model->_Get_All(99,0,'active');
    $CI->load->view('default/modules/banner', $data);
}
function box_trienlam() {
    $CI=&get_instance();
    $data['all_expo'] = $CI->expo_model->_Get_All_Expo();
    $CI->load->view('default/widget/box_trienlam', $data);
}

function box_news() {
    $CI=&get_instance();
    $data['all_news'] = $CI->content_model->_Get_All_Content_By_Category(11,15,0,'active');
    $CI->load->view('default/widget/box_news', $data);
}

function box_doitact() {
    $CI=&get_instance();
    $data['all_parner'] = $CI->logo_model->_Get_All_Logo(9999,0,'active');
    $CI->load->view('default/widget/box_doitac', $data);
}