<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author naoto
 */
class UserController extends BaseController{
    
    public function run() {
        // セッション開始　認証に利用
        $this->auth = new Auth();
        $this->auth->set_authname(_USER_AUTHINFO);
        $this->auth->set_sessname(_USER_SESSNAME);
        $this->auth->start();
        
        if ($this->auth->check()){
            // 認証済み
            $this->menu_member();
        }else{
            // 未認証
            $this->menu_guest();
        }
    }
    //----------------------------------------------------
    // 会員用メニュー
    //----------------------------------------------------
    public function menu_member() {
        $MessageController = new MessageController();
        
        switch ($this->type) {
            case "logout":
                $this->auth->logout();
                $this->screen_login();
                break;
            case "me":
                $this->show_me();
                break;
            case "users":
                $this->show_user();
                break;
            default:
                $MessageController->top();
        }
    }
    //----------------------------------------------------
    // ゲスト用メニュー
    //----------------------------------------------------
    public function menu_guest() {
        switch ($this->type) {
            case "regist":
                $this->screen_regist();
                break;
            case "authenticate":
                $this->do_authenticate();
                break;
            default:
                $this->screen_login();
        }
    }
    //----------------------------------------------------
    // ログイン画面表示
    //----------------------------------------------------
    public function screen_login(){
        
        $this->title = 'ログイン';
        $this->next_type = 'authenticate';
        $this->file = "login.tpl";
        $this->view_display();
    }
    
    public function do_authenticate(){
        // データベースを操作します。
        $UserModel = new UserModel();
        $userdata = $UserModel->get_authinfo($_POST['username']);
        if(!empty($userdata['password']) && $this->auth->check_password($_POST['password'], $userdata['password'])){
            $this->auth->auth_ok($userdata);
            $MessageController = new MessageController();
            $MessageController->top();
        } else {
            $this->auth_error_mess = $this->auth->auth_no();
            $this->screen_login();
        }
    }
    
    //----------------------------------------------------
    // 会員情報登録画面
    //----------------------------------------------------
    public function screen_regist(){
        $UserModel = new UserModel();
        $auth = new Auth();
        
        $this->next_action = "complete";
        $this->file = "userinfo_form.tpl";
        
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) 
                && $_POST['action'] === "complete"){
            $userdata = $_POST;
            if($UserModel->check_username($userdata) && ($_SESSION[_USER_AUTHINFO]['username'] != $userdata['username'])){
                $this->message = "メールアドレスは登録済みです。";
                $this->title = '会員登録';
                
            }else{
                $userdata['password'] = $auth->get_hashed_password($userdata['password']);
                $UserModel->regist_user($userdata);
                $this->run();
            }
        }
        $this->view_display();
    }
    //----------------------------------------------------
    // ユーザー自ページ
    //----------------------------------------------------
    public function show_me(){
        
        $userdata = $_GET;
        $userinfo = $_SESSION['userinfo'];
        
        //ユーザーデータの取得
        $UserModel = new UserModel();
        $userdata = $UserModel->get_id_info($userdata['id']);
        $MessageModel = new MessageModel;
        list($data, $count) = $MessageModel->get_all_message($userdata['id']);

        //ツイート・フォロー・フォロワー値の取得
        list($userdatas_fing, $fwing_count, $userdatas_fwer, $fwer_count)  
                = $this->follow_list($userdata['id']);
        $count = ['fwing' => $fwing_count, 'folw' => $fwer_count, 'tweet' => $count];
        
        
        
        //画面変数
        $this->view->assign('follower', $userdatas_fwer);
        $this->view->assign('following', $userdatas_fing);
        $this->view->assign('userdata', $userdata);
        $this->view->assign('userinfo', $userinfo);
        $this->view->assign('data', $data);
        $this->view->assign('count', $count);
        $this->file = "show_myinfo.tpl";
        $this->view_display();
    }
    //----------------------------------------------------
    // 他ユーザー個人ページ
    //----------------------------------------------------
    public function show_user(){
        $userdata = $_GET;
        $userinfo = $_SESSION['userinfo'];
        
        //ユーザーデータの取得
        $UserModel = new UserModel();
        $userdata = $UserModel->get_id_info($userdata['id']);
        $MessageModel = new MessageModel;
        list($data, $count) = $MessageModel->get_all_message($userdata['id']);
        
        //フォロー・フォロワーの取得
        list($userdatas_fing, $fwing_count, $userdatas_fwer, $fwer_count)  
                = $this->follow_list($userdata['id']);

        //カウント結合
        $count = ['fwing' => $fwing_count, 'folw' => $fwer_count, 'tweet' => $count];
        
        //フォロー処理
        $f_result = $this->following($_SESSION['userinfo']['id'], $userdata['id']);

        
        //画面変数
        $this->view->assign('follower', $userdatas_fwer);
        $this->view->assign('f_result', $f_result);
        $this->view->assign('following', $userdatas_fing);
        $this->view->assign('userinfo', $userinfo);
        $this->view->assign('userdata', $userdata);
        $this->view->assign('data', $data);
        $this->view->assign('count', $count);
        $this->file = "show_user.tpl";
        $this->view_display();
    }
    
    //----------------------------------------------------
    // おすすめユーザー
    //----------------------------------------------------
    public function user_rondom_list(){

        $UserModel =new UserModel();        
        $rondom_user_data = $UserModel->user_rondom_list();
        return $rondom_user_data;   
    }

    //----------------------------------------------------
    // フォローの表示
    //----------------------------------------------------
    public function follow_list($id){
  
        $UserModel =new UserModel();
        //following
        $fing_data = [];
        list($fing_data, $fwing_count) = $UserModel->following_id_get($id);  
        $userdatas_fing = [];
        for ($i=0; $i < $fwing_count; $i++){
            $userdatas_fing[$i] = $UserModel->get_id_info($fing_data[$i]['following_id']);
        }
        
        //follower
        $fwer_data = [];
        list($fwer_data, $fwer_count) = $UserModel->follower_id_get($id);
        $userdatas_fwer = [];
        for ($i=0; $i < $fwer_count; $i++){
            $userdatas_fwer[$i] = $UserModel->get_id_info($fwer_data[$i]['user_id']);
        }
        
        return [$userdatas_fing, $fwing_count, $userdatas_fwer, $fwer_count];
    }
    //----------------------------------------------------
    //  フォロー処理
    //----------------------------------------------------
    public function following($u_id, $f_id){
        if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) 
                && $_POST['action'] === "following"){
            $UserModel = new UserModel();
            $f_result = $UserModel->set_following($u_id, $f_id);
            return $f_result;
        }
        if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) 
                && $_POST['action'] === "not_following"){
            $UserModel = new UserModel();
            $f_result = $UserModel->delete_following($u_id, $f_id);
            return $f_result;
        }
    }

}
