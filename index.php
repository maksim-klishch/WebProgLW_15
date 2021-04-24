<?php

class Database
{
    private static $instance = null;

    private $pdo;
    private $dtable;
    private $fields;

    private function __construct()
    {
        $host = "localhost";
        $user_db = "winter";
        $pass_db = "winter";
        $dbase = "php_db";
        $this->dtable = "monitors";
        $charset="utf8";
        $dsn = "mysql:host=$host; dbname=$dbase; charset=$charset";
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        $this->pdo = new PDO($dsn, $user_db, $pass_db, $opt);
        $fields = array("type", "resolution", "colors");
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function pdoSet($fields, $values)
    {
        $set = "(";

        foreach($fields as $key => $field)
        {
            $set = $set . "`$field` = $values[key], ";
        }
        return substr($set, 0, -2) . ")";
    }

    //insert Data into table
    public function setData($type, $resolution, $colors)
    {
        $sql = "INSERT INTO $this->dtable SET " . pdoSet($this->fields, array($type, $resolution, $colors));
        $this->pdo->query($sql);
    }

    //updating data
    public function updateData($id, $type, $resolution, $colors)
    {
        $sql = "UPDATE $this->dtable SET " . pdoSet($this->fields, array($type, $resolution, $colors)) . "WHERE `id` = $id";
        $this->pdo->query($sql);
    }

    //delete data
    public function removeData($id)
    {
        $this->pdo->query("DELETE FROM $this->$dtable WHERE `id` = $id");
    }

    //show table
    public function viewMonitors()
    {
        $stmt = $this->pdo->query("SELECT * FROM $dtable");

        echo "<table class=\"table\">
            <tr>
                <td>id</td>
                <td>Тип монітора</td>
                <td>Роздільна здатність</td>
                <td>К-сть кольорів</td>
            </tr>";  

        while ($row = $stmt->fetch())
        {
        echo "<tr>
                <td>" .
                    $row['id'] . "
                </td>
                <td>" .
                    $row['type'] . "
                </td>
                <td>" .
                    $row['resolution'] . "
                </td>
                <td>" .
                    $row['colors'] . "
                </td>        
            </tr>";
        }

        echo "</table>";
    }
}

try {
    $db = Database::getInstance();
    $db->viewMonitors();
} catch(PDOException $e) {
    print "ERROR:: " . $e->getMessage() . "<br/>";
    die();
}

?>