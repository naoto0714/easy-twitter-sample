<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MessageModel
 *
 * @author naoto
 */
class MessageModel extends BaseModel{
    
    //--------------------------------
    //  ユーザーメッセージ取得(個人ページ)
    //--------------------------------
    public function get_all_message($ui){
        
        try{
            $sql = "select * from message where user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $ui, PDO::PARAM_INT);
            $stmt->execute();
            //件数の取得
            $count = $stmt->rowCount();
            //検索結果を多次元配列で受け取る.
            
            $i = 0;
            $data = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                foreach ($row as $key => $value){
                    $data[$i][$key] = $value;
                }
                $i++;
            }
            
        } catch (Exception $ex) {
            echo "エラー:" . $ex->getMessage();
        }        
        return [$data, $count];
    }
    //--------------------------------
    //  ユーザーメッセージ取得(総合)
    //--------------------------------
    public function get_all_messages($ui){
        
        try{
            $sql = "select id,message,name,reg_date from message where user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $ui, PDO::PARAM_INT);
            $stmt->execute();
            //件数の取得
            $count = $stmt->rowCount();
            //検索結果を多次元配列で受け取る.
            
            $i = 0;
            $data = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                foreach ($row as $key => $value){
                    $data[$key] = $value;
                }
                $i++;
            }
            
        } catch (Exception $ex) {
            echo "エラー:" . $ex->getMessage();
        }        
        return [$data];
    }
    
    
    
    //--------------------------------
    //  新しいメッセージの挿入
    //--------------------------------
    public function insert_message($message_info,$userinfo){
        try{
            $this->pdo->beginTransaction();
            $sql = "insert into message (user_id, message, name, reg_date) values (:user_id, :message, :name, now())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $userinfo['id'], PDO::PARAM_INT);
            $stmt->bindValue(':message', $message_info['message'], PDO::PARAM_STR);
            $stmt->bindValue(':name', $userinfo['name'], PDO::PARAM_STR);
            $stmt->execute();
            $this->pdo->commit();
            $stmt->closeCursor();
            $stmt = NULL;
  
        } catch (Exception $ex) {
            $this->pdo->rollBack();
            echo "エラー:" . $ex->getMessage();
        }        
    }
    
    //--------------------------------
    //  メッセージの削除
    //--------------------------------
    public function delete_message($message_info){
        try{
            $this->pdo->begintTransaction();
            $sql = "delete from message where id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $message_info['id'], PDO::PARAM_INT);
            $stmt->execute();
            $this->pdo->commit();
        } catch (Exception $ex) {
            $this->pdo->rollBack();
            echo "エラー:" . $ex->getMessage();

        }
    }
}
