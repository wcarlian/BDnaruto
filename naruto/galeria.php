<?php

$servername = "localhost";
$username = "root";        
$password = "";            
$dbname = "meus_posts";    

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    
   
    $imagem = $_FILES['imagem']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($imagem);
    
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
     
        $sql = "INSERT INTO POST (titulo, path_imagem, descricao) VALUES ('$titulo', '$target_file', '$descricao')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Novo post adicionado com sucesso!";
        } else {
            echo "Erro ao inserir o post: " . $conn->error;
        }
    } else {
        echo "Erro ao fazer upload da imagem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hokages</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f3ea;
    background-image: url('https://www.transparenttextures.com/patterns/white-diamond.png'); /* textura estilo pergaminho */
    color: #333;
    padding: 20px;
}

form {
    background-color: #ffcc00;
    padding: 20px;
    border: 2px solid #333;
    border-radius: 10px;
    margin-bottom: 20px;
    max-width: 600px;
}

form label {
    font-weight: bold;
    color: #333;
}

form input[type="text"],
form textarea,
form input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0 16px 0;
    border: 1px solid #333;
    border-radius: 5px;
}

form input[type="submit"] {
    background-color: #ff6600;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

form input[type="submit"]:hover {
    background-color: #ff3300;
}

.galery_row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.galery_column {
    background-color: #333;
    color: #f4f3ea;
    width: 30%;
    margin: 10px;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
}

.galery_column:hover {
    transform: scale(1.05);
}


.titulo {
    font-size: 1.5em;
    font-weight: bold;
    color: #ffcc00;
    margin-bottom: 10px;
    text-align: center;
}


.galery_column img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
    border: 3px solid #ff6600;
    transition: border 0.3s;
}

.galery_column img:hover {
    border: 3px solid #ffcc00;
}


.galery_column p {
    font-size: 1em;
    line-height: 1.6;
    color: #f4f3ea;
    text-align: justify;
}

    </style>
</head>
<body>

    <form action="galeria.php" method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required><br>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" required></textarea><br>

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" required><br><br>

        <input type="submit" value="Adicionar Post">
    </form>


    <?php
    $sql = "SELECT * FROM POST";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='galery_row'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='galery_column'>";
            echo "<h3 class='titulo'>" . htmlspecialchars($row['titulo']) . "</h3>";
            echo "<img src='" . htmlspecialchars($row['path_imagem']) . "' alt='Imagem do Post' style='width:100%'>";
            echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "Nenhum post encontrado.";
    }

    $conn->close();
    ?>
</body>
</html>
