<?php
  class User {
    private $table = 'ishin4554_users';
    protected $conn;
    protected $username;
    public function __construct($connection, $name = 0){
      $this->conn = $connection;
      $this->username = $name;
    }
    function checkUser(){
      $sql = "SELECT * FROM $this->table WHERE username = '$this->username'";
      $result = $this->conn->query($sql);
      return empty($result);
    }
    function createUser($password, $nickname){
      $sql = "INSERT INTO $this->table(username, password, nickname) VALUES('$this->username','$password','$nickname')";
      $result = $this->conn->query($sql);
      return empty($result);
    }
    function readUserByName(){
      $sql = "SELECT * FROM $this->table WHERE username = '$this->username'";
      $result = $this->conn->query($sql);
      return $result->fetch_assoc();
    }
    function readUserById($id){
      $sql = "SELECT * FROM $this->table WHERE id = '$id'";
      $result = $this->conn->query($sql);
      return $result->fetch_assoc();
    }
  }

  class Session extends User {
    private $table = 'ishin4554_users_certificate';
    private $id;
    public function __construct($connection, $name, $sessionID = 0){
      parent::__construct($connection, $name);
      $this->id = $sessionID;
    }

    public function readSession() {
      $sql = "SELECT * FROM $this->table WHERE id = '$this->id'";
      $result = $this->conn->query($sql);
      $row = $result->fetch_assoc();
      return $row['username'];
    }
    public function setSession(){
      $sql = "SELECT username FROM $this->table WHERE username = '$this->username'";
      $result = $this->conn->query($sql);
      $row = $result->fetch_assoc();
      $value = $this->rndSession();
      if ($row) {
        $sql = "UPDATE $this->table SET id = '$value' WHERE username = '$this->username'";
      } else {
        $sql = "INSERT INTO $this->table(id, username) VALUES('$value','$this->username')";
      }
      $this->conn->query($sql);
      return $value;
    }
    function rndSession() {
      $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
      $key = "";
      for ($i = 0; $i < 32; $i ++) {
        $key .= $pattern[rand(0,35)];
      }
      return $key;
    }
  }

  // function checkSession($conn, $username) {
  //   $sql = "SELECT * FROM ishin4554_users_certificate WHERE username = '$username'";
  //   $result = $conn->query($sql);
  //   return $result;
  // }
  // function readSession($conn, $id) {
  //   $sql = "SELECT * FROM ishin4554_users_certificate WHERE id = '$id'";
  //   $result = $conn->query($sql);
  //   $row = $result->fetch_assoc();
  //   return $row;
  // }
  // function setSession($conn, $username, $sessionID){
  //   if (checkSession($conn, $username)) {
  //     $sql = "INSERT INTO ishin4554_users_certificate(id, username) VALUES('$sessionID','$username')";
  //   } else {
  //     $sql = "UPDATE ishin4554_users_certificate SET id = '$sessionID' WHERE username = '$username'";
  //   }
  //   $conn->query($sql);
  // }
  // function rndSession() {
  //   $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
  //   $key = "";
  //   for ($i = 0; $i < 32; $i ++) {
  //     $key .= $pattern[rand(0,35)];
  //   }
  //   return $key;
  // }
?>