<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserModel
 *
 * @author naoto
 */
class UserModel extends BaseModel{
    //----------------------------------------------------
    // 会員登録処理
    //----------------------------------------------------
    public function regist_user($userdata){
        try{
            $this->pdo->beginTransaction();
            $sql = "insert into user (username, password, name, reg_date) values (:username, :password, :name, now())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':username',   $userdata['username'],   PDO::PARAM_STR );
            $stmt->bindValue(':password',   $userdata['password'],   PDO::PARAM_STR );
            $stmt->bindValue(':name',  $userdata['name'],  PDO::PARAM_STR );
            $stmt->execute();
            $this->pdo->commit();
        } catch (Exception $ex) {
            $this->pdo->rollBack();
            echo "エラー：" . $ex->getMessage();

        }
    }
    //----------------------------------------------------
    // 会員のメールアドレスと同じものがないか調べる。
    //----------------------------------------------------
    public function check_username($userdata){
        try {
            $sql= "SELECT * FROM user WHERE username = :username ";
            $stmh = $this->pdo->prepare($sql);
            $stmh->bindValue(':username',  $userdata['username'], PDO::PARAM_STR );
            $stmh->execute();
            $count = $stmh->rowCount();
        } catch (PDOException $Exception) {
            print "エラー：" . $Exception->getMessage();
        }
        if($count >= 1){
            return true;
        }else{
            return false;
        }
    }
    //----------------------------------------------------
    // 会員情報をユーザー名（メールアドレス）で検索
    //----------------------------------------------------
    public function get_authinfo($username){
        $data = [];
        try {
            $sql= "SELECT * FROM user WHERE username = :username limit 1";
            $stmh = $this->pdo->prepare($sql);
            $stmh->bindValue(':username',  $username,  PDO::PARAM_STR );
            $stmh->execute();
            $data = $stmh->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $Exception) {
            print "エラー：" . $Exception->getMessage();
        }
        return $data;
    }
    //----------------------------------------------------
    // 会員情報をIDで検索
    //----------------------------------------------------
    public function get_id_info($ud){
        $data = [];
        try {
            $sql= "SELECT * FROM user WHERE id = :id limit 1";
            $stmh = $this->pdo->prepare($sql);
            $stmh->bindValue(':id',  $ud,  PDO::PARAM_INT );
            $stmh->execute();
            $data = $stmh->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $Exception) {
            print "エラー：" . $Exception->getMessage();
        }
        return $data;
    }
    //----------------------------------------------------
    // ランダムユーザーの取得
    //----------------------------------------------------
    public function user_rondom_list(){
        $data = [];
        try{
            $sql= "SELECT id,name FROM user order by rand() limit 5";
            $stmt = $this->pdo->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            print "エラー：" . $ex->getMessage();
        }
        return $data;
    }
    //----------------------------------------------------
    // フォローの取得
    //----------------------------------------------------
    public function following_id_get($fuid){
        $data = [];
        try{
            $sql= "SELECT following_id FROM following where user_id = :user_id";
            $stmh = $this->pdo->prepare($sql);
            $stmh->bindValue(':user_id',  $fuid,  PDO::PARAM_INT );
            $stmh->execute();
            $data = [];
            $data = $stmh->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmh->rowCount();
        } catch (Exception $ex) {
            print "エラー：" . $ex->getMessage();
        }
        return [$data, $count];
    }
    //----------------------------------------------------
    // フォロワーの取得
    //----------------------------------------------------
    public function follower_id_get($fuid){
        $data = [];
        try{
            $sql= "SELECT user_id FROM following where following_id = :following_id";
            $stmh = $this->pdo->prepare($sql);
            $stmh->bindValue(':following_id',  $fuid,  PDO::PARAM_INT );
            $stmh->execute();
            $data = [];
            $data = $stmh->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmh->rowCount();
        } catch (Exception $ex) {
            print "エラー：" . $ex->getMessage();
        }
        return [$data, $count];
    }
    //----------------------------------------------------
    // フォローの処理
    //----------------------------------------------------
    public function set_following($u_id, $f_id){
        try{
            $this->pdo->beginTransaction();
            $sql = "insert into following (user_id, following_id) values (:user_id, :following_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $u_id, PDO::PARAM_INT);
            $stmt->bindValue(':following_id', $f_id, PDO::PARAM_INT);
            $stmt->execute();
            $this->pdo->commit();
            $result = 1;
        } catch (Exception $ex) {
            $this->pdo->rollBack();
            echo "エラー：" . $ex->getMessage();
        }
        
        if ($result === 1){
            return 'not_following';
        }else{
            return 'following';
        }
    }
    public function delete_following($u_id, $f_id){
        try{
            $this->pdo->beginTransaction();
            $sql = "delete from  following where user_id = :user_id and following_id = :following_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $u_id, PDO::PARAM_INT);
            $stmt->bindValue(':following_id', $f_id, PDO::PARAM_INT);
            $stmt->execute();
            $this->pdo->commit();
            $result = 0;
        } catch (Exception $ex) {
            $this->pdo->rollBack();
            echo "エラー：" . $ex->getMessage();
        }
        
        if ($result === 1){
            return 'not_following';
        }else{
            return 'following';
        }
    }
}
    


