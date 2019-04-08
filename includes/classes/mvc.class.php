<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class cmsMVC extends cmsCore{

  private static $instance;

  public $module;
	public $action;
	public $routes;
  public $app;

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
  * Launch the app (MVC)
  * @return boot
  */
  public function initApps(){


		$this->setSlash($_SERVER['REQUEST_URI']);

		$this->module = 'error404';
    $this->action = 'main';

    $this->getHome();

    $app = $this->getApp();

    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/router.php')){
      include($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/router.php');
    }

		$params = array();

    if($this->routes){
		foreach ($this->routes as $map){
				$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
				if (preg_match($map['pattern'], $url_path, $matches)){
					array_shift($matches);
					foreach ($matches as $index => $value){
						$request[$map['aliases'][$index]] = $value;
					}

					$this->module = $map['do'];
					if(isset($map['method'])){
						$this->action = $map['method'];
					}
					break;
				}
		}
    }

		if($this->module == "error404"){
			$this->error404();
		}

    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/model.php')){
      include($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/model.php');
    }

    $db = cmsDatabase::getInstance();
		$classname = $app.'Model';
		$model = new $classname($db);
    unset($db);

		$do = $this->module;
		$action = $this->action;
    $theme = 'default';

    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/controller.php')){
      include($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/controller.php');
    }
    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/theme/'.$do.'.tpl.php')){
      include($_SERVER['DOCUMENT_ROOT'].'/apps/'.$app.'/theme/'.$do.'.tpl.php');
    }

	}

  /**
  * Checking, if it homepage or not
  * @return mixed
  */
  public function getHome(){
    if($_SERVER['REQUEST_URI'] == '/'){
      $this->module = 'login';
      $this->action = 'login';
      $this->app = 'login';
    }
  }

  /**
  * Checking, what the app now
  * @return string
  */
  public function getApp(){
    if($this->app){
      return $this->app;
    }else{
      $component = explode('/',$_SERVER['REQUEST_URI']);
      return $component[1];
    }
  }

  /**
  * Add slash to url at the end
  * @param string $url
  * @return boot
  */
	public function setSlash($url){
		preg_replace("/\?.*/i",'', $url);
		if (strlen($url)>1) {
			if (rtrim($url,'/')!=$url) {
				self::redirect('http://'.$_SERVER['SERVER_NAME'].str_replace($url, rtrim($url,'/'), $_SERVER['REQUEST_URI']));
				self::halt();
			}
		}
	}

}
