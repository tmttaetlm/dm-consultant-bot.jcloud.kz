<h1>
    <?php
        include_once 'db.php';
        use Db;
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $query = "SELECT * FROM users;";
        $db = Db::getDb();
        print_r($db->selectQuery($query,[]));
    ?>
</h1>