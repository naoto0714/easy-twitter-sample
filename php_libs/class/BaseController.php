<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseController
 *
 * @author naoto
 */
class BaseController {
    
    protected $type;
    protected $action;
    protected $next_type;
    protected $next_action;
    protected $file;
    protected $auth;
    protected $view;
    protected $title;
    protected $sub_title;
    protected $auth_error_mess;
    protected $message;
    protected $user_id;
    
    protected $MessageController;
    protected $MessageModel;
    


    public function __construct() {
        $this->view_initialize();
    }
    
    private function view_initialize(){
        //画面表示
        $this->view = new Smarty;
        // Smarty関連ディレクトリの設定
        $this->view->template_dir = _SMARTY_TEMPLATES_DIR;
        $this->view->compile_dir  = _SMARTY_TEMPLATES_C_DIR;
        $this->view->config_dir   = _SMARTY_CONFIG_DIR;
        $this->view->cache_dir    = _SMARTY_CACHE_DIR;
        
        //リクエスト変数typeとaction
        if(isset($_REQUEST['type'])){ $this->type = $_REQUEST['type'];}
        if(isset($_REQUEST['action'])){ $this->action = $_REQUEST['action'];}
    }
    
    //------------------------------------
    //  表示画面への組み込み
    //------------------------------------
    
    protected function view_display(){
        
        //templateへの組み込み
        $this->view->assign('title', $this->title);
        $this->view->assign('sub_title', $this->sub_title);
        $this->view->assign('auth_error_mess', $this->auth_error_mess);
        $this->view->assign('type',    $this->next_type);
        $this->view->assign('message',    $this->message);
        $this->view->assign('action',  $this->next_action);
        $this->view->display($this->file);
    }
    
}
