<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MessageController
 *
 * @author naoto
 */
class MessageController extends BaseController {
    
    
    public function run(){
        $this->top();
    }
    
    //----------------------
    //ログイン後のトップ画面
    //----------------------
    public function top(){
        
        $userinfo = $_SESSION['userinfo'];
        
        //ランダムでおすすめユーザー表示
        $UserController = new UserController();
        $user_rmdom_data = $UserController->user_rondom_list();
        
        //フォローしているユーザーの取得
        list($userdatas_fing, $fwing_count, $userdatas_fwer, $fwer_count) = 
                $UserController->follow_list($userinfo['id']);
        
        //フォローユーザー含めたtweetの取得
        $posts = $this->message_all_list($userinfo['id'], $userdatas_fing);
        
        //ツイート・フォロー・フォロワー値の取得
        $MessageModel = new MessageModel;
        list($data, $count) = $MessageModel->get_all_message($userinfo['id']);
        $count = ['fwing' => $fwing_count, 'folw' => $fwer_count, 'tweet' => $count];
        
        //tweetの登録
        $this->insert_message_info();
        
        //tweetの削除
        $this->delete_message();
       
        
        //画面変数
        $this->view->assign('posts', $posts);//tweet一覧総合
        $this->view->assign('count', $count);
        $this->view->assign('userdatas_fing', $userdatas_fing);
        $this->view->assign('user_rmdom_data', $user_rmdom_data);
        $this->view->assign('userinfo', $userinfo);
        $this->title = "49erz";
        $this->file = 'message_top.tpl';
        $this->view_display();
    }
    
    //-------------------------------
    // tweet の一覧表示(総合)
    //-------------------------------
    public function message_all_list($id, $userdatas_fing){
        
        //モデルよりデータの取得
        $MessageModel = new MessageModel;
        
        for ($i = 0; $i < count($userdatas_fing); $i++){
            $u_id[$i] =  $userdatas_fing[$i]['id'];
        }
        $u_id[] = $id;

        for ($i=0; $i < count($u_id); $i++){
            $posts[] = $MessageModel->get_all_messages($u_id[$i]);
        }      
     
        return $posts;

    }
    //-------------------------------
    // tweet の登録
    //-------------------------------
    public function insert_message_info(){
        
        //モデルオブジェクトの生成
        $MessageModel = new MessageModel;
        
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action']) 
                && $_POST['action'] === "insert"){
            $message_info = $_POST;
            $userinfo = $_SESSION['userinfo'];
            $MessageModel->insert_message($message_info,$userinfo); 
        }
    }
    //-------------------------------
    // tweet の削除
    //-------------------------------
    public function delete_message(){
        //モデルオブジェクトの生成
        $MessageModel = new MessageModel;
        
        if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['action']) 
                && $_GET['action'] === "delete"){
            $message_info = $_SERVER;
            $MessageModel->delete_message($message_info);
        }
    }
}
