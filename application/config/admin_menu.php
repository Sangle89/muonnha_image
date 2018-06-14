<?php
$config['admin_menu'] = array(
    array(
        'id' => 'home',
        'controller' => 'home',
        'title' => 'Trang chủ',
        'href' => admin_url(),
        'icon' => 'fa fa-lg fa-fw fa-home',
        'class' => 'menu-item-parent',
        'childs' => array()
    ),
    array(
        'id' => 'content',
        'controller' => 'content',
        'title' => 'Nội dung',
        'href' => admin_url('content'),
        'icon' => 'fa fa-book',
        'class' => 'menu-item-parent',
        'childs' => array(
            array(
                'id' => 'content',
                'title' => 'Nhóm nội dung',
                'controller' => 'content_category',
                'href' => admin_url('content_category'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'content',
                'title' => 'Giới thiệu',
                'controller' => 'content',
                'href' => admin_url('content/index/20/0'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'content',
                'title' => 'Mẹo làm đẹp da',
                'controller' => 'content',
                'href' => admin_url('content/index/6/0'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'content',
                'title' => 'Chia sẻ kiến thức',
                'controller' => 'content',
                'href' => admin_url('content/index/5/0'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'page',
                'title' => 'Trang nội dung',
                'controller' => 'page',
                'href' => admin_url('page'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'tags',
                'title' => 'Từ khóa',
                'controller' => 'tags',
                'href' => admin_url('tags'),
                'icon' => '',
                'class' => ''
            ),
        )
    ),
    array(
        'id' => 'product',
        'controller' => 'product',
        'title' => 'Sản phẩm',
        'href' => admin_url('product'),
        'icon' => 'fa fa-puzzle-piece',
        'class' => 'menu-item-parent',
        'childs' => array(
            array(
                'id' => 'product',
                'title' => 'Danh mục',
                'controller' => 'product_category',
                'href' => admin_url('product_category'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'product',
                'title' => 'Sản phẩm',
                'controller' => 'product',
                'href' => admin_url('product'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'property_group',
                'title' => 'Thuộc tính',
                'controller' => 'property_group',
                'href' => admin_url('property_group'),
                'icon' => '',
                'class' => ''
            )
        )
    ),
    array(
        'id' => 'album',
        'controller' => 'album',
        'title' => 'Album',
        'href' => admin_url('album'),
        'icon' => 'fa fa-puzzle-piece',
        'class' => 'menu-item-parent',
        'childs' => array()
    ),
    array(
        'id' => 'module',
        'controller' => 'module',
        'title' => 'Module',
        'href' => admin_url('slider'),
        'icon' => 'fa fa-puzzle-piece',
        'class' => 'menu-item-parent',
        'childs' => array(
            array(
                'id' => 'Product category',
                'title' => 'Danh mục Sản phẩm',
                'controller' => 'module/product_category',
                'href' => admin_url('module/product_category'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'slider1',
                'title' => 'Banner Slider',
                'controller' => 'slider',
                'href' => admin_url('slider'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'banner',
                'controller' => 'banner',
                'title' => 'Banner QC',
                'controller' => 'banner',
                'href' => admin_url('banner'),
                'icon' => '',
                'class' => ''
            ),
            /*array(
                'id' => 'popup',
                'controller' => 'popup',
                'title' => 'Popup',
                'controller' => 'popup',
                'href' => admin_url('popup'),
                'icon' => '',
                'class' => ''
            ),
            array(
                'id' => 'support',
                'controller' => 'support',
                'title' => 'Hỗ trợ',
                'controller' => 'support',
                'href' => admin_url('support'),
                'icon' => '',
                'class' => ''
            )*/
        )
    ),
    
    array(
        'id' => 'contact',
        'controller' => 'contact',
        'title' => 'Liên hệ',
        'href' => admin_url('contact'),
        'icon' => 'fa fa-envelope',
        'class' => 'menu-item-parent',
        'childs' => array()
    ),
   array(
        'id' => 'newsletter',
        'controller' => 'newsletter',
        'title' => 'Newsletter',
        'href' => admin_url('newsletter'),
        'icon' => 'fa fa-envelope',
        'class' => 'menu-item-parent',
        'childs' => array()
    ),
    array(
        'id' => 'city',
        'controller' => 'city',
        'title' => 'Khu vực',
        'href' => admin_url('city'),
        'icon' => 'fa fa-map-marker',
        'class' => 'menu-item-parent',
        'childs' => array(
            array(
                'id' => 'city',
                'title' => 'Tỉnh/thành phố',
                'controller' => 'city',
                'href' => admin_url('city'),
                'icon' => '',
                'class' => ''
            )
        )
    ),
    array(
        'id' => 'order',
        'controller' => 'order',
        'title' => 'Đơn hàng',
        'href' => admin_url('order'),
        'icon' => 'fa fa-shopping-cart',
        'class' => 'menu-item-parent',
        'childs' => array()
    ),
    array(
        'id' => 'user',
        'controller' => 'user',
        'title' => 'Quản lý user',
        'href' => admin_url('user'),
        'icon' => 'fa fa-user',
        'class' => 'menu-item-parent'
    ),
    array(
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
    )
);