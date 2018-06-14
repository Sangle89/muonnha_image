<?php
require APPPATH ."/third_party/phpcrawl/libs/PHPCrawler.class.php";
class Crawler extends PHPCrawler {
    
    private $filename = 'test';
    private $folder = 'kenhnhatro';
    private $domain = '';
    private $pathname = '';
    private $ignore_list = array();
    
    function set_filename($filename) {
        $this->filename = $filename;
    }
    function set_folder($folder) {
        $this->folder = $folder;
    }
    function set_domain($domain) {
        $this->domain = $domain;
    }
    function set_pathname($pathname) {
        $this->pathname = $pathname;
    }
    function set_ignore_list($list) {
        $this->ignore_list = $list;
    }
    function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) 
    {
        $CI =&get_instance();
        $CI->load->database();
        // Just detect linebreak for output ("\n" in CLI-mode, otherwise "<br>"). 
        if (PHP_SAPI == "cli") $lb = "\n"; 
        else $lb = "<br />"; 
    
        if ($DocInfo->received == true && !$this->checkUrl($DocInfo->url)){
            //if(strpos($DocInfo->url, "/ho-chi-minh/") != false) {
                $CI->db->insert('crawler_links', array(
                    'url' => $DocInfo->url,
                    'domain' => $this->domain,
                    'pathname' => $this->pathname,
                    'status' => 0,
                    'create_time' => time() 
                ));      
           // }
            
        } else {
            //echo "Content not received".$lb;  
        }
         
        // Now you should do something with the content of the actual 
        // received page or file ($DocInfo->source), we skip it in this example  
         
        //echo $lb; 
        
        flush();
    } 
    function checkUrl($url) {
        $CI =&get_instance();
        $CI->load->database();
        return $CI->db->select('id')->from('crawler_links')->where('url', $url)->get()->row_array();
    }
}