<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Breadcrumb Class
 * CẦN tạo file theo đường dẫn application/config/breadcrumb.php, với nội dung như bên dưới
 * ----------------------------------------------------------------------------------------
  |  $config['divider']          = '';
  |  $config['tag_open']         = '<ul class="breadcrumb">';
  |  $config['tag_close']        = '</ul>';
  |  $config['item_open']        = '<li>';
  |  $config['item_close']       = '<span class="divider">&nbsp;&#8250;&nbsp;</span></li>';
  |  $config['last_item_open']   = '<li class="current">';
  |  $config['last_item_close']  = '</li>';
 * ---------------------------------------------------------------------------------------
 * This class manages the breadcrumb object
 *
 * @package		Breadcrumb
 * @version		1.0
 * @author 		Richard Davey <info@richarddavey.com>
 * @copyright 	        Copyright (c) 2011, Richard Davey
 * @link		https://github.com/richarddavey/codeigniter-breadcrumb
  |-------------------------------------------------------
  | HƯỚNG DẪN SỬ DỤNG:
  |  $this->load->library('breadcrumb');
  |  $this->breadcrumb->append_crumb('Home','link_home');
  |  $this->breadcrumb->append_crumb('News','link_news');
  |  $this->breadcrumb->append_crumb('Page','link_page');
  |  echo $this->breadcrumb->output();
  |-------------------------------------------------------
 */
class CI_Breadcrumb {

    /**
     * Breadcrumbs stack
     *
     */
    private $breadcrumbs = array();

    /**
     * Options
     *
     */
    private $_divider = '';
    private $_tag_open = '<ul id="breadcrumb">';
    private $_tag_close = '</ul>';
    private $_item_open = '<li>';
    private $_item_close = '</li>';
    private $_last_item_open = '<li>';
    private $_last_item_close = '</li>';

    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */
    public function __construct($params = array()) {
        if (count($params) > 0) {
            $this->initialize($params);
        }
        log_message('debug', "Breadcrumb Class Initialized");
    }

    // --------------------------------------------------------------------
    /**
     * Initialize Preferences
     *
     * @access	public
     * @param	array	initialization parameters
     * @return	void
     */
    private function initialize($params = array()) {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->{'_' . $key})) {
                    $this->{'_' . $key} = $val;
                }
            }
        }
    }

    // --------------------------------------------------------------------
    /**
     * Append crumb to stack
     *
     * @access	public
     * @param	string $title
     * @param	string $href
     * @return	void
     */
    function append_crumb($title, $href) {
        // no title or href provided
        if (!$title OR !$href)
            return;
        // add to end
        $this->breadcrumbs[] = array('title' => $title, 'href' => $href);
    }

    // --------------------------------------------------------------------
    /**
     * Prepend crumb to stack
     *
     * @access	public
     * @param	string $title
     * @param	string $href
     * @return	void
     */
    function prepend_crumb($title, $href) {
        // no title or href provided
        if (!$title OR !$href)
            return;
        // add to start
        array_unshift($this->breadcrumbs, array('title' => $title, 'href' => $href));
    }

    // --------------------------------------------------------------------
    /**
     * Generate breadcrumb
     *
     * @access	public
     * @return	string
     */
    function output() {
        // breadcrumb found
        if ($this->breadcrumbs) {
            // set output variable
            $output = $this->_tag_open;
            // add html to output
            foreach ($this->breadcrumbs as $key => $crumb) {
                // add divider
                if ($key)
                    $output .= $this->_divider;
                // if last element
                if (end(array_keys($this->breadcrumbs)) == $key) {
                    $output .= $this->_last_item_open . '<a class="current" href="' . $crumb['href'] . '">' . $crumb['title'] . '</a>' . $this->_last_item_close;
                    // else add link and divider
                } else {
                    if ($crumb['href'] == "")
                        $output .= $this->_item_open . $crumb['title'] . $this->_item_close;
                    else
                        $output .= $this->_item_open . '<a href="' . $crumb['href'] . '">' . $crumb['title'] . '</a>' . $this->_item_close;
                    //$output .= $this->_item_open . $crumb['title'] . $this->_item_close;
                }
            }
            // return html
            return $output . $this->_tag_close . PHP_EOL;
        }
        // return blank string
        return '';
    }

}

// END Breadcrumb Class
/* End of file Breadcrumb.php */
/* Location: ./application/libraries/Breadcrumb.php */