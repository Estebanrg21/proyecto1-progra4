<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto 1</title>
</head>

<body>

    <?php
    include "./database/database.php";

    $db = new Database();
    $conn = $db->connect();
    
    if ($conn->connect_errno) {
        
        die("Hubo un error en la conexión a la base de datos");
    }

    $result = $conn->query("SELECT * FROM dummy");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            var_dump($row);
        }
    }
?>
    



</body>

</html>