<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    

<form action="xestionaProduto.php">
    <label for="nomeCliente">Nome</label>
    <input type="text" name="nomeCliente" id="nomeCliente" required><br>

    <label for="apelidosCliente">Apelidos</label>
    <input type="text" name="apelidosCliente" id="apelidosCliente" required><br>

    <label for="email">Email</label>
    <input type="email" name="email" id="email"><br>
    
    <button name="inserirCliente" value="inserirCliente">Crear</button>
</form>



</body>
</html>


