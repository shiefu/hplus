<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model{

    public function __construct(){
        parent::__construct();

        // 定義網站的常數
        $this->define_constant();
        // 定義當前語系
        //$this->lang_define();
        // 定義 CSRF 基本常數
        //$this->security_define();
        // 定義登入 Session 資訊
        //$this->login_define();
    }

    // 定義網站的常數
    public function define_constant(){
        // =============================================================================================================
        // 通用常數
        // =============================================================================================================
        // 網站中文名
        define('SITE_CNAME', '虹彩预测专家');
        // 網站英文名
        define('SITE_ENAME', 'Rainbow Predict');
        // 網頁協定
        define('PROTOCOL', is_https() ? 'https' : 'http');
        // 網站根目錄
        define('HTTP_ROOT', (PROTOCOL . '://' . $_SERVER['HTTP_HOST']));
        // 當前 CI Controller
        define('CURRENT_CONTROLLER', $this->router->class);
        // 當前 CI Method
        define('CURRENT_METHOD', $this->router->method);
        // =============================================================================================================
        // 前台常數
        // =============================================================================================================
        // 實體根目錄
        define('ROOT', dirname(dirname(dirname(__FILE__))));
        // 應用程式目錄 ( 如果 CI 資料夾非頂端資料夾的話, 才會有值 )
        define('APP_DIR', substr(str_replace($_SERVER['DOCUMENT_ROOT'], '', ROOT), 1));
        // 資源目錄
        define('ASSETS_DIR', HTTP_ROOT.'/assets');
        // =============================================================================================================
        // 後台常數
        // =============================================================================================================
        // 後台網站實體根目錄
        define('ADMIN_ROOT', ROOT.DIRECTORY_SEPARATOR .'admin');
        // 後台網站的相對目錄
        define('ADMIN_DIR', '/admin');
        // 後台資源目錄
        define('ADMIN_ASSETS_DIR', HTTP_ROOT.'/assets/admin');
    }

    // 定義當前語系
    public function lang_define(){
        if(!isset($_SESSION['lang'])) $_SESSION['lang'] = 'zh-tw';
        define('LANG', $_SESSION['lang']);
    }

    // 定義 CSRF 基本常數
    public function security_define(){
        define('CSRF_NAME', $this->security->get_csrf_token_name());
        define('CSRF_HASH', $this->security->get_csrf_hash());
    }

    // 定義登入 Session 資訊
    public function login_define($data = null){
        // 定義前台 Session 結構
        if($_SESSION['logged'] === null){
            $_SESSION = array(
                'lang'   => $_SESSION['lang'],
                'logged' => false
            );
        }
        // 定義後台 Session 結構
        if($_SESSION['admin'] === null){
            $_SESSION['admin'] = array(
                'logged' => false
            );
        }
        if(!stristr(current_url(), '/admin')){ // 前台 ----------------------------------------
            if(!$_SESSION['logged']){ // 未登入處理轉向
                $white = array( // 前台不需要登入也可以訪問的白名單頁面
                    'main/login',
                    'main/lang'
                );
                if(!in_array($this->router->class . '/' . $this->router->method, $white)){
                    @header('Location:' . HTTP_ROOT . '/main/login');
                }
            }else{ // 已登入, 寫入 Session 資料
                if($data != null){
                    $_SESSION['member_id'] = $data['id'];
                    $_SESSION['name']      = $data['name'];
                    $_SESSION['email']     = $data['email'];
                    @header('Location:' . HTTP_ROOT . '/main');
                }
            }
        }else{ // 後台 ------------------------------------------------------------------------
            if(!$_SESSION['admin']['logged']){ // 未登入處理轉向
                $white = array( // 後台不需要登入也可以訪問的白名單頁面
                    'main/login',
                    'main/lang',
                    'captcha/index'
                );
                if(!in_array($this->router->class . '/' . $this->router->method, $white)){
                    @header('Location:' . HTTP_ROOT . '/admin/main/login');
                }
            }else{ // 已登入, 寫入 Session 資料
                if($data != null){
                    $_SESSION['admin']['admin_id'] = $data['id'];
                    $_SESSION['admin']['name']     = $data['name'];
                    $_SESSION['admin']['email']    = $data['email'];
                    @header('Location:' . HTTP_ROOT . '/admin/main');
                }
            }
        }
    }

}

?>