<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class cmsTheme extends cmsCore{

  private static $instance;

  public $theme = 'default';

  // ============================================================================ //
  // ============================================================================ //

  protected function __construct(){}

  /**
  * Load function from class self
  * @return bool
  */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
  * Function get a theme for site
  * @param string $mvc
  * @return boot
  */
  public function getTheme($mvc){
    $app = $mvc->getApp();
    if(file_exists(cmsCore::getInstance()->config['theme'])){
      include(cmsCore::getInstance()->config['theme']);
    }

    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/head.php')){
      include($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/head.php');
    }

    if(isset($head[$_SERVER['REQUEST_URI']])){
      $head = $head[$_SERVER['REQUEST_URI']];
    }else{
      $head = '';
    }

    if(isset($theme[$app])){
      $style = $theme[$app];
    }else{
      $style = $this->theme;
    }

    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/theme/'.$style.'/main.tpl.php')){
      include($_SERVER['DOCUMENT_ROOT'].'/theme/'.$style.'/main.tpl.php');
    }
  }

  /**
  * Sets the page title.
  * @param string
  * @return $this
  */
  public function setTitle($title){
      $this->title = strip_tags($title);
      return $this;
  }

  /**
  * Print the head for page
  * @param string
  * @return $this
  */
  public function printHead($head){
    $db = cmsDatabase::getInstance();
    $settings = $db->get_fields('btc_settings','id=id');
    // Page title for home
    if(isset($settings['title']) && isset($head['title'])){
      $head['title'] = $head['title'].' - '.$settings['title'];
    }else{
      $head = array('title'=>$settings['title']);
    }

    if(isset($head['title'])){
      // Page Title
      echo '<title>', htmlspecialchars($head['title']), '</title>',"\n";
    }
    if(isset($head['keys'])){
      // Keywords
      echo '<meta name="keywords" content="', htmlspecialchars($head['keys']), '" />',"\n";
    }
    if(isset($head['desc'])){
      // Description
      echo '<meta name="description" content="',htmlspecialchars($head['desc']),'" />',"\n";
    }
  }


}
