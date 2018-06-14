<?php

function admin_menu() {
    $CI=&get_instance();
    
    $data['admin_menu'] = array(
        array(
            'id' => 'home',
            'controller' => 'home',
            'title' => 'Trang chủ',
            'href' => admin_url(),
            'icon' => 'fa fa-lg fa-fw fa-home',
            'class' => 'menu-item-parent',
            'childs' => array()
        )
    );
    
    $data['admin_menu'][] = array(
        'id' => 'module',
        'controller' => 'module',
        'title' => 'Tin đăng',
        'href' => admin_url('real_estate'),
        'icon' => 'fa fa-puzzle-piece',
        'class' => 'menu-item-parent',
        'childs' => array(
            array(
                'id' => 'category',
                'controller' => 'category',
                'title' => 'Danh mục tin đăng',
                'href' => admin_url('category'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'real_estate',
                'controller' => 'real_estate',
                'title' => 'Tin đăng',
                'href' => admin_url('real_estate'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'project',
                'controller' => 'project',
                'title' => 'Dự án',
                'href' => admin_url('project'),
                'icon' => '',
                'class' => ''
            ),
        )
    );
    
    $data['admin_menu'][] = array(
        'id' => 'module',
        'controller' => 'module',
        'title' => 'Slider',
        'href' => admin_url('slider'),
        'icon' => 'fa fa-puzzle-piece',
        'class' => 'menu-item-parent'
    );
    $data['admin_menu'][] = array(
        'id' => 'city',
        'controller' => 'city',
        'title' => 'Quản lý City',
        'href' => admin_url('city'),
        'icon' => 'fa fa-map-marker',
        'class' => 'menu-item-parent'
    );
    
    $data['admin_menu'][] = array(
        'id' => 'contact',
        'controller' => 'contact',
        'title' => 'Liên hệ',
        'href' => admin_url('contact'),
        'icon' => 'fa fa-envelope',
        'class' => 'menu-item-parent',
        'childs' => array()
    );
    $data['admin_menu'][] = array(
        'id' => 'user',
        'controller' => 'user',
        'title' => 'Quản lý user',
        'href' => admin_url('user'),
        'icon' => 'fa fa-user',
        'class' => 'menu-item-parent'
    );
    
    $data['admin_menu'][] = array(
        'id' => 'config',
        'controller' => 'website_config',
        'title' => 'Cài đặt',
        'href' => admin_url('website_config'),
        'icon' => 'fa fa-cog',
        'class' => 'menu-item-parent',
        'childs' => array(
            array(
                'id' => 'website_config',
                'controller' => 'setting/website',
                'title' => 'Cấu hình website',
                'href' => admin_url('setting/website'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'email_config',
                'controller' => 'setting/email',
                'title' => 'Cấu hình Email',
                'href' => admin_url('setting/email'),
                'icon' => '',
                'class' => ''
            )
        )
    );
    
    $CI->load->view(ADMIN_FOLDER . '/require/menu', $data);
}