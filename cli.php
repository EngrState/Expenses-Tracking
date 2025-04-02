<?php

require __DIR__ . "/vendor/autoload.php";

use Framework\Database;
use App\Config\Paths;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$db = new Database($_ENV['DB_DRIVER'],
[
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'dbname' => $_ENV['DB_NAME'],
],
$_ENV['DB_USER'], $_ENV['DB_PASS']);

$sqlFile = file_get_contents("./database.sql");

$db->connection->query($sqlFile);


//below is a well written transaction
// try {
//     // Check if the connection is established before executing queries
//     if (!$db->isConnected()) {
//         die("❌ Database connection failed.");
//     }
//     $db->connection->beginTransaction();

//     $db->connection->query("INSERT INTO products VALUES(40, 'Glove')");


//     $search = "Glove";
//     $query = "SELECT * FROM products WHERE name = :name";




//     $stmt = $db->connection->prepare($query);
//     $stmt->bindValue('name', $search, PDO::PARAM_STR);
//     $stmt->execute([
//         'name' => $search
//     ]);
//     $result = $stmt->fetchAll(PDO::FETCH_OBJ);
//     // Output query results
//     if ($result) {
//         echo "✅ Query executed successfully!\n";
//         var_dump($result);
//     } else {
//         echo "⚠️ No records found.\n";
//     }

//     $db->connection->commit();
// } catch (Exception $e) {
//     if ($db->connection->inTransaction()) {
//         $db->connection->rollBack();
//     }
//     echo  "transaction failed";
// }
