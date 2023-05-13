<?php
class Doctor{
 
    // database connection and table name
    private $conn;
    private $table_name = "doctors";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $gender;
    public $specialist;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function emailExists(){
        $query = "SELECT id, name, email, password, phone, gender, specialist
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->gender = $row['gender'];
            $this->specialist = $row['specialist'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }

    // check if the given password is correct
    function checkPassword(){
        // fetch the hashed password from the database
        $query = "SELECT id, name, email, password, phone, gender, specialist
                FROM " . $this->table_name . "
                WHERE password = ?
                LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $this->password=htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(1, $this->password);
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num>0){
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->gender = $row['gender'];
            $this->specialist = $row['specialist'];
            return $row;
        }

        // return false if email does not exist in the database
        return false;
    }

    // read all doctors
    function read(){
    
        // select all query
        $query = "SELECT
                    `id`, `name`, `email`, `password`, `phone`, `gender`, `specialist`, `created`
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // get single doctor data
    function read_single(){
    
        // select all query
        $query = "SELECT
                    `id`, `name`, `email`, `password`, `phone`, `gender`, `specialist`, `created`
                FROM
                    " . $this->table_name . " 
                WHERE
                    id= '".$this->id."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create doctor
    function create(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`name`, `email`, `password`, `phone`, `gender`, `specialist`, `created`)
                  VALUES
                        ('".$this->name."', '".$this->email."', '".$this->password."', '".$this->phone."', '".$this->gender."', '".$this->specialist."', '".$this->created."')";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // update doctor 
    function update(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name='".$this->name."', email='".$this->email."', password='".$this->password."', phone='".$this->phone."', gender='".$this->gender."', specialist='".$this->specialist."'
                WHERE
                    id='".$this->id."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // delete doctor
    function delete(){
        
        // query to insert record
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id= '".$this->id."'";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                email='".$this->email."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}