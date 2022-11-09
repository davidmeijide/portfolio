<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
</body>
</html>

<form action="xestionaProduto.php" method="post" enctype="multipart/form-data">
    <label for="nomeProduto">Nome do produto</label>
    <input type="text" name="nomeProduto" id="nomeProduto" required><br>

    <label for="desc">Descrición</label>
    <textarea name="desc" id="desc"></textarea><br>

    <label for="marca">Marca</label>
    <input type="text" name="marca" id="marca" required><br>

    <label for="imaxe">Sube unha imaxe</label>
    <input type="file" name="imaxe" id="imaxe" required><br>

    <input type="submit" value="Engadir produto" name="enviar" id="enviar">
</form>

