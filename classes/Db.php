<?php
include_once "classes/Filter.php";
//require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
class Db
{
    public $pdo; //Database variable
    private $select_result; //result

    public function __construct($host, $db_name, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection to server failed: " . $e->getMessage();
            exit();
        }
    }

    function __destruct()
    {
        $this->pdo = null;
    }

    public function select($sql)
    {
        //parameter $sql – select string
        //variable $results – association table with query results
        $results = array();
        try {
            $stmt = $this->pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }
        $this->select_result = $results;
        return $results;
    }

    public function addMessage($name, $type, $content, $login)
    {
        $insert_query =
            "INSERT INTO message
                (`name`,`type`, `message`,`deleted`, `user_id`)
            VALUES
                (:name, :type, :content, 0, (
                    SELECT id FROM user WHERE login = :login LIMIT 1
                ))";
        try {
            $stmt = $this->pdo->prepare($insert_query);
            $stmt->execute(
                array(
                    ':name' => Filter::sanitizeData($name, 'str'),
                    ':type' => Filter::sanitizeData($type, 'str'),
                    ':content' => Filter::sanitizeData($content, 'str'),
                    ':login' => Filter::sanitizeData($login, 'str'),
                )
            );
            return true;
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    public function getSingleMessage($message_id)
    {
        $query =
            "SELECT m.`id`, `name`, `type`, `message`, `login`
            FROM message m 
            LEFT JOIN user u ON u.id = m.user_id
            WHERE m.id = :message_id  ";

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(
                array(
                    ':message_id' => Filter::sanitizeData($message_id, 'num')
                )
            );
            return $stmt;
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return false;
        }
    }

    public function updateMessage($id, $title, $type, $message)
    {
        printf("$id, $title, $type, $message");

        $update_query =
            "UPDATE message
            SET `name` = :title, `type` = :type, `message` = :message
            WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($update_query);
            $stmt->execute(
                array(
                    ':title' => Filter::sanitizeData($title, 'str'),
                    ':type' => Filter::sanitizeData($type, 'str'),
                    ':message' => Filter::sanitizeData($message, 'str'),
                    ':id' => Filter::sanitizeData($id, 'num')
                )
            );
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }

    public function getMessage($message_id)
    {
        foreach ($this->select_result as $message):
            if ($message->id == $message_id)
                return $message->message;
        endforeach;
    }
}
?>