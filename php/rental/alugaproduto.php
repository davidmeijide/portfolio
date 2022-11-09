<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aluga</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<form action="xestionaProduto.php">
<label for="nomeCliente">Nome</label>

    <select name="nomeProduto" id="nomeProduto">
        <?php
            require_once('config/config.php');
            try{
                $pdoStatement= $pdo->prepare("SELECT id_Produto, nome FROM produto");
                $pdoStatement->execute();
                while($fila = $pdoStatement->fetch(PDO::FETCH_ASSOC)){
                    $id = $fila['id_Produto'];
                    $nome = $fila['nome'];

                    echo "<option value='$id'>$nome</option>";
                }
            }
            catch(PDOException $e){
                echo 'Error ao procurar a lista de produtos', $e;
            }
        ?>
    </select><br>

    <label for="nomeCliente">Cliente</label>
    <select name="nomeCliente" id="nomeCliente">
        <?php
            require_once('config/config.php');
            try{
                $pdoStatement= $pdo->prepare("SELECT codCliente, nome, apelidos FROM cliente");
                $pdoStatement->execute();
                while($fila = $pdoStatement->fetch(PDO::FETCH_ASSOC)){
                    $id = $fila['codCliente'];
                    $nome = $fila['nome']." ".$fila['apelidos'];

                    echo "<option value='$id'>$nome</option>";
                }
            }
            catch(PDOException $e){
                echo 'Error ao procurar a lista de produtos', $e;
            }
        ?>
    </select><br>
    <label for="dataAluguer">Data</label>
    <input type="date" name="dataAluguer" id="dataAluguer" required><br>

    <label for="ndias">Número de días</label>
    <input type="number" name="ndias" id="ndias" required><br>

    <label for="prezo">Prezo</label>
    <input type="number" name="prezo" id="prezo" required><br>

    <button name="alugar" value="alugar">Alugar</button>
</form>


</body>
</html>


