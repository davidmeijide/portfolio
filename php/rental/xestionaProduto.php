<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Alugueres de cámaras de fotos</title>
</head>
<body>
    


<a href="crearCliente.php">
    <button name="crearCliente" value="crearCliente">Crear cliente</button>
</a><br>
<a href="introproduto.php">
    <button name="introproduto" value="introproduto">Introducir produto</button>
</a><br>
<a href="alugaproduto.php">
    <button name="alugaproduto" value="alugaproduto">Aluga un produto</button>
</a><br>
<form id="menu" action="xestionaProduto.php" method="GET">
    <button name="totalProdutos" value="totalProdutos">Mostrar todos os produtos</button>
    <button name="totalAlugados" value="totalAlugados">Mostrar alugados</button>
    <button name="mostrarClientes" value="mostrarClientes">Mostrar clientes</button>
</form>
<?php

//PROBAR CONEXION
require_once('config/config.php');
try{
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // INSERIR PRODUTO
    if(isset($_POST["enviar"])){
        require_once('config/config.php');
        try{
            $tmp_name= $_FILES['imaxe']['tmp_name'];
            if(is_uploaded_file($tmp_name)){
                $img_filename = $_FILES['imaxe']['name'];
                $img_type = $_FILES['imaxe']['name'];
                if((strpos($img_type,"gif") || strpos($img_type, "jpeg") ||
                strpos($img_type,"jpg") || strpos($img_type,"png"))){
                    if(move_uploaded_file($tmp_name,"imaxes/".$img_filename)){
                        echo 'Imaxe subida con éxito.';
                    }
                }
            }
            $pdoStatement = $pdo->prepare("INSERT INTO produto(nome, descricion,marca,imaxe) 
            VALUES(?,?,?,?)");
            $pdoStatement->bindParam(1, $_POST['nomeProduto']);
            $pdoStatement->bindParam(2, $_POST['desc']);
            $pdoStatement->bindParam(3, $_POST['marca']);
            $pdoStatement->bindParam(4, $img_filename);
    
            $pdoStatement->execute();
        }
        catch(Exception $e){
            echo 'Erro na inserción. ', $e->getMessage();
        }
    }

    //VER TODOS OS ALUGADOS
    if(isset($_GET['totalAlugados'])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("SELECT aluga.cod_aluguer,produto.nome, aluga.data, aluga.numDiasAlugados, aluga.devolto  FROM produto
                                            JOIN aluga ON produto.id_Produto = aluga.idProduto WHERE aluga.devolto = 0");
            $pdoStatement->execute();
            echo '<table><tr><th>id</th><th>Nome</th><th>Data</th><th>Dias</th><th>Devolto</th></tr>';
            
            while($fila = $pdoStatement->fetch(PDO::FETCH_ASSOC)){

                echo "<tr><td>".$fila['cod_aluguer']."</td><td>".$fila['nome']."</td><td>".$fila['data']."</td><td>".$fila['numDiasAlugados']."</td>";
                if($fila['devolto'] == 0){
                    $id=$fila['cod_aluguer'];
                    echo "<td>Non</td><td><form action='xestionaProduto.php'><button name='devolver' value='$id'>Devolver</button></td></tr>";
                }
                else{
                    echo "Sí";
                }
                
            }
            echo '</table>';
        }
        catch(Exception $e){
            echo 'Erro na selección.', $e->getMessage();
        }
    }
    //ELIMINAR UN PRODUTO
    if(isset($_GET['eliminarProduto'])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("DELETE FROM produto WHERE id_Produto LIKE ?");
            $id=$_GET['eliminarProduto'];
            $pdoStatement->bindParam(1,$id);
            $pdoStatement->execute();

        }
        catch(Exception $e){
            echo 'Erro na eliminación.', $e->getMessage();
        }
    }
    //VER TODOS OS PRODUTOS
    if(isset($_GET['totalProdutos']) || isset($_GET['eliminarProduto'])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("SELECT * FROM produto");
            $pdoStatement->execute();
            echo '<table><tr><th>id</th><th>Nome</th><th>Descrición</th><th>Marca</th><th>Imaxe</th></tr>';
            
            while($fila = $pdoStatement->fetch(PDO::FETCH_ASSOC)){

                echo "<tr><td>".$fila['id_Produto']."</td><td>".$fila['nome']."</td><td>".$fila['descricion']."</td><td>".$fila['marca']."</td>";
                $id = $fila['id_Produto'];
                $src = "imaxes/".$fila['imaxe'];
                echo "<td><img height='50px' width='80px' src='$src'/></td>";
                echo "<td><form action='xestionaProduto.php'><button name='eliminarProduto' value='$id'>Eliminar</button></form></td></tr>";
            }
            echo '</table>';
        }
        catch(Exception $e){
            echo 'Erro na selección.', $e->getMessage();
        }
    }
    // INSERIR CLIENTE
    if(isset($_GET["inserirCliente"])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("INSERT INTO cliente(nome, apelidos,email) 
            VALUES(?,?,?)");
            $pdoStatement->bindParam(1, $_GET['nomeCliente']);
            $pdoStatement->bindParam(2, $_GET['apelidosCliente']);
            $pdoStatement->bindParam(3, $_GET['email']);

    
            $pdoStatement->execute();
            echo 'Cliente inserido correctamente.';
        }
        catch(Exception $e){
            echo 'Erro na inserción. ', $e->getMessage();
        }
    }   

    //ELIMINAR UN CLIENTE
    if(isset($_GET['eliminarCliente'])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("DELETE FROM cliente WHERE codCliente LIKE ?");
            $id=$_GET['eliminarCliente'];
            $pdoStatement->bindParam(1,$id);
            $pdoStatement->execute();
            
            while($fila = $pdoStatement->fetch(PDO::FETCH_ASSOC)){

                echo "<tr><td>".$fila['codCliente']."</td><td>".$fila['nome']."</td><td>".$fila['apelidos']."</td></tr>";
            }
            echo '</table>';
        }
        catch(Exception $e){
            echo 'Erro na eliminación.', $e->getMessage();
        }
    }
    //MOSTRAR CLIENTES
    if(isset($_GET['mostrarClientes']) || isset($_GET['eliminarCliente'])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("SELECT * FROM cliente");
            $pdoStatement->execute();
            echo '<table><tr><th>id</th><th>Nome</th><th>Apelidos</th><th>Email</th></tr>';
            
            while($fila = $pdoStatement->fetch(PDO::FETCH_ASSOC)){

                echo "<tr><td>".$fila['codCliente']."</td><td>".$fila['nome']."</td><td>".$fila['apelidos']."</td><td>".$fila['email']."</td>";
                $id = $fila['codCliente'];
                echo "<td><form action='xestionaProduto.php'><button name='eliminarCliente' value='$id'>Eliminar</button></form></td></tr>";
            }
            echo '</table>';
        }
        catch(Exception $e){
            echo 'Erro na selección.', $e->getMessage();
        }
    }

    //ALUGAR
    if(isset($_GET["alugar"])){
        require_once('config/config.php');
        try{
            $pdoStatement = $pdo->prepare("INSERT INTO aluga (idProduto, CodCliente, data, numDiasAlugados, prezo,devolto ) 
            VALUES(?,?,?,?,?,0)");
            $pdoStatement->bindParam(1, $_GET['nomeProduto']);
            $pdoStatement->bindParam(2, $_GET['nomeCliente']);
            $pdoStatement->bindParam(3, $_GET['dataAluguer']);
            $pdoStatement->bindParam(4, $_GET['ndias']);
            $pdoStatement->bindParam(5, $_GET['prezo']);


    
            $pdoStatement->execute();
            echo 'Produto alugado correctamente.';
        }
        catch(Exception $e){
            echo 'Erro na inserción. ', $e->getMessage();
        }
    }
    //DEVOLVER
    if(isset($_GET["devolver"])){
        require_once('config/config.php');
        try{
            $id = $_GET['devolver'];
            $pdoStatement = $pdo->prepare("UPDATE aluga SET devolto = 1 WHERE $id LIKE cod_aluguer");
     
            $pdoStatement->execute();
            echo 'Produto devolto correctamente.';
        }
        catch(Exception $e){
            echo 'Erro na inserción. ', $e->getMessage();
        }
    }
    


}
catch(Exception $e){
    echo 'Erro ao conectar co servidor MySQL: '. $e->getMessage();
}
finally{
    $pdo = null;
}
?>

</body>
</html>