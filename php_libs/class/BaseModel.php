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
class BaseModel {
    protected $pdo;
    
    public function __construct() {
        $this->db_connect();
    }
    
    protected function db_connect(){
        try{
            $this->pdo = new PDO(_DSN, _DB_USER, _DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            die("エラー:" . $ex->getMessage()); 
        }
    }
}
