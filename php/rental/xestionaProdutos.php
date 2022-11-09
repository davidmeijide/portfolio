<?php
@session_start();
if($_SESSION['rol']!="admin"){
    header('Location: mostra.php');
}

require_once('config/config.php');
try{   
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Insercion na BD
    if(isset($_POST['inserirProduto'])){
        try{
        //echo '<h3>Xestión de produtos</h3>';

            $tmp_name= $_FILES['imaxe']['tmp_name'];
            if(is_uploaded_file($tmp_name)){
                $img_filename = $_FILES['imaxe']['name'];
                $img_type = $_FILES['imaxe']['name'];
                if((strpos($img_type,"gif") || strpos($img_type, "jpeg") ||
                strpos($img_type,"jpg") || strpos($img_type,"png"))){
                    if(move_uploaded_file($tmp_name,"imaxes/".$img_filename)){
                    }
                }
            }
            $pdoStatement = $pdo->prepare("INSERT INTO produto(id_produto,nome, descricion,familia,imaxe,prezo) 
            VALUES(?,?,?,?,?,?)");
            $pdoStatement->bindParam(1, $_POST['id_produto']);

            $pdoStatement->bindParam(2, $_POST['nome_produto']);
            $pdoStatement->bindParam(3, $_POST['descricion']);
            $pdoStatement->bindParam(4, $_POST['familia']);
            $pdoStatement->bindParam(5, $img_filename);
            $pdoStatement->bindParam(6, $_POST['prezo']);

    
            $pdoStatement->execute();
            header('Location: xestionaProdutos.php');
        }
        catch(Exception $e){
            echo 'Erro na inserción. ', $e->getMessage();
        }
    }

    //Formulario de insercion de produto
    elseif(isset($_GET['altaProduto'])){
        echo '<h3>Xestión de produtos</h3>';

        echo '<div id="altaProduto">';
        echo '<h4>Alta produto</h4>';
        echo '<form action="xestionaProdutos.php" method="POST" enctype="multipart/form-data">';
        //Para sacar un novo id unico
        $consulta_max_id = $pdo->query("SELECT MAX(id_produto) FROM produto");
        $consulta_max_id = $consulta_max_id->fetch()[0]+1;
        echo '<label for="id_produto">ID</label>';
        echo '<input readonly type="number" name="id_produto" value="'.$consulta_max_id.'"><br>';
        ?>
        <label for="nome_produto">Nome</label>
        <input type="text" name="nome_produto"><br>

        <label for="descricion">Descrición</label><br>
        <textarea name="descricion"></textarea><br>

        <label for="familia">Familia</label>
        <select name="familia">
        <option value="mirrorless">Mirrorless</option>
        <option value="reflex">Réflex</option>
        <option value="pointandshoot">Point & Shoot</option>
        <option value="compacta">Compacta</option>
        </select><br>

        <label for="imaxe">Engade unha imaxe</label><br>
        <input type="file" name="imaxe"><br>

        <label for="prezo">Prezo</label>
        <input type="number" name="prezo"/>€<br>

        <a href="xestionaProdutos.php"><button>Cancelar</button></a>
        <input type="submit" name="inserirProduto" value="Confirmar">

        </form>
        </div>
        <?php
    }

    //Editar un produto
    elseif(isset($_GET['editarProduto'])){
        echo '<h3>Xestión de produtos</h3>';
        echo '<div id="editarProduto">';
        echo '<h4>Editar produto</h4>';
        echo '<form action="xestionaProdutos.php" method="POST" enctype="multipart/form-data">';
        $id_produto = $_GET['editarProduto'];
        $select_produto = $pdo->query("SELECT * FROM produto WHERE id_produto LIKE '$id_produto'");
        $select_produto = $select_produto->fetch();
        echo '<label for="id_produto">ID</label>';
        echo '<input readonly type="number" name="id_produto" value="'.$id_produto.'"><br>';
        
        echo '<label for="nome_produto">Nome</label>';
        echo '<input type="text" name="nome_produto" value="'.$select_produto['nome'].'"><br>';

        echo '<label for="descricion">Descrición</label><br>';
        echo '<textarea name="descricion">'.$select_produto['descricion'].'</textarea><br>';

        echo '<label for="familia">Familia</label>';
        echo '<select name="familia">';
        echo '<option value="mirrorless">Mirrorless</option>';
        echo '<option value="reflex">Réflex</option>';
        echo '<option value="pointandshoot">Point & Shoot</option>';
        echo '<option value="compacta">Compacta</option>';
        echo '<option value="de accion">De acción</option>';

        echo '</select><br>';

        echo '<label for="imaxe">Engade unha imaxe</label><br>';
        echo '<input type="file" name="imaxe"><br>';

        echo '<label for="prezo">Prezo</label>';
        echo '<input type="number" name="prezo" value ="'.$select_produto['prezo'].'"/>€<br>';

        echo '<input type="submit" name="inserirProduto" value="Confirmar">';
        echo '</form>';
        echo '</div>';
        
        
    }

    //Actualizar produto editado (update)
    elseif(isset($_POST['editarProduto'])){
        try{
            $tmp_name= $_FILES['imaxe']['tmp_name'];
            if(is_uploaded_file($tmp_name)){
                $img_filename = $_FILES['imaxe']['name'];
                $img_type = $_FILES['imaxe']['name'];
                if((strpos($img_type,"gif") || strpos($img_type, "jpeg") ||
                strpos($img_type,"jpg") || strpos($img_type,"png"))){
                    if(move_uploaded_file($tmp_name,"imaxes/".$img_filename)){
                    }
                }
            }
            $id_produto = $_POST['id_produto'];

            $pdoStatement = $pdo->prepare("UPDATE produto
            SET nome=? ,descricion=?,familia=?,imaxe=?,prezo=? WHERE id_produto LIKE ?");

            $nome = $_POST['nome_produto'];
            $descricion = $_POST['descricion'];
            $familia = $_POST['familia'];
            $imaxe = $img_filename;
            $prezo = $_POST['prezo'];

            $pdoStatement->execute(array($nome,$descricion,$familia,$imaxe,$prezo,$id_produto));
            header('Location: xestionaProdutos.php');
        }
        catch(Exception $e){
            if($e)
            echo 'Erro na inserción. ', $e->getMessage();
        }
    }

    elseif(isset($_GET['eliminarProduto'])){
        $id_produto = $_GET['eliminarProduto'];
        $delete_query = $pdo->prepare("DELETE FROM produto WHERE id_produto LIKE ?");
        $delete_query->bindParam(1,$id_produto);
        $delete_query->execute();
        header('Location: xestionaProdutos.php');
    }

    //mostrar todos os produtos reservados
    elseif(isset($_GET['mostraReservados'])){
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/mostra.css">
            <title>Xestionar</title>
        </head>
        <body>
        <a id="pechaSesion" href="pechaSesion.php">Pechar sesión</a>
        <a id="btn-xestionaProdutos" href="xestiona.php"><button>Xestionar usuarios</button></a>
        <form action="xestionaProdutos.php">
            <button name="mostraReservados">Mostrar só reservados</button>
            <button name="mostraNonReservados">Mostrar os non reservados</button>
        </form>

        <?php
        $consulta_produtos = $pdo->query("SELECT * FROM produto WHERE id_produto IN(SELECT fk_id_produto FROM aluga WHERE devolto LIKE 0)");
        if($consulta_produtos->rowCount()!=0){
            echo '<form action="xestionaProdutos.php">';
            echo '<table id="tabla-produtos">';
            echo '<tr><th>id</th><th>Nome</th><th>Descrición</th><th>Familia</th><th>Imaxe</th><th>Prezo</th></tr>';

            while($fila = $consulta_produtos->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                echo "<td>".$fila['id_produto']."</td>";
                echo "<td>".$fila['nome']."</td>";
                echo "<td>".$fila['descricion']."</td>";
                echo "<td>".ucfirst($fila['familia'])."</td>";
                echo "<td><img src="."imaxes/".$fila['imaxe']."></td>";
                echo "<td>".$fila['prezo']."€</td>";
                echo "<td class='btn-editar'><button class='editar' value=".$fila['id_produto']." name='editarProduto'>Editar</button></td>";
                echo "<td class='btn-eliminar'><button class='eliminar' value=".$fila['id_produto']." name='eliminarProduto'>X</button></td>";
                echo '</tr>';
            }
            echo '</table>';
            echo '<button name="altaProduto" value="altaProduto">Alta produto</button>';
            echo '</form>';
        }
        else{
            echo '<p>Non hai produtos reservados actualmente.</p>';
        }
    }
    //mostrar todos os produtos NON reservados
    elseif(isset($_GET['mostraNonReservados'])){
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/mostra.css">
            <title>Xestionar</title>
        </head>
        <body>
        <a id="pechaSesion" href="pechaSesion.php">Pechar sesión</a>
        <a id="btn-xestionaProdutos" href="xestiona.php"><button>Xestionar usuarios</button></a>
        <form action="xestionaProdutos.php">
            <button name="mostraReservados">Mostrar só reservados</button>
            <button name="mostraNonReservados">Mostrar os non reservados</button>
        </form>

        <?php


        $consulta_produtos = $pdo->query("SELECT * FROM produto WHERE id_produto NOT IN(SELECT fk_id_produto FROM aluga WHERE devolto LIKE 0)");

        if($consulta_produtos->rowCount()!=0){
            echo '<form action="xestionaProdutos.php">';
            echo '<table id="tabla-produtos">';
            echo '<tr><th>id</th><th>Nome</th><th>Descrición</th><th>Familia</th><th>Imaxe</th><th>Prezo</th></tr>';

            while($fila = $consulta_produtos->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                echo "<td>".$fila['id_produto']."</td>";
                echo "<td>".$fila['nome']."</td>";
                echo "<td>".$fila['descricion']."</td>";
                echo "<td>".ucfirst($fila['familia'])."</td>";
                echo "<td><img src="."imaxes/".$fila['imaxe']."></td>";
                echo "<td>".$fila['prezo']."€</td>";
                echo "<td class='btn-editar'><button class='editar' value=".$fila['id_produto']." name='editarProduto'>Editar</button></td>";
                echo "<td class='btn-eliminar'><button class='eliminar' value=".$fila['id_produto']." name='eliminarProduto'>X</button></td>";
                echo '</tr>';
            }
            echo '</table>';
            echo '<button name="altaProduto" value="altaProduto">Alta produto</button>';
            echo '</form>';
        }
        else{
            echo '<p>Non produtos reservados.</p>';
        }

    }
    else{
        //tabla mostrando todos os produtos
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/mostra.css">
            <title>Xestionar</title>
        </head>
        <body>
        <a id="pechaSesion" href="pechaSesion.php">Pechar sesión</a>
        <a id="btn-xestionaProdutos" href="xestiona.php"><button>Xestionar usuarios</button></a>
        <form action="xestionaProdutos.php">
            <button name="mostraReservados">Mostrar só reservados</button>
            <button name="mostraNonReservados">Mostrar os non reservados</button>
        </form>

        <?php
        echo '<form action="xestionaProdutos.php">';
        echo '<table id="tabla-produtos">';
        echo '<tr><th>id</th><th>Nome</th><th>Descrición</th><th>Familia</th><th>Imaxe</th><th>Prezo</th></tr>';

        $consulta_produtos = $pdo->query("SELECT * FROM produto");
        while($fila = $consulta_produtos->fetch(PDO::FETCH_ASSOC)){
            echo '<tr>';
            echo "<td>".$fila['id_produto']."</td>";
            echo "<td>".$fila['nome']."</td>";
            echo "<td>".$fila['descricion']."</td>";
            echo "<td>".ucfirst($fila['familia'])."</td>";
            echo "<td><img src="."imaxes/".$fila['imaxe']."></td>";
            echo "<td>".$fila['prezo']."€</td>";
            echo "<td class='btn-editar'><button class='editar' value=".$fila['id_produto']." name='editarProduto'>Editar</button></td>";
            echo "<td class='btn-eliminar'><button class='eliminar' value=".$fila['id_produto']." name='eliminarProduto'>X</button></td>";
            echo '</tr>';
        }
        echo '</table>';
        echo '<button name="altaProduto" value="altaProduto">Alta produto</button>';
        echo '</form>';

        ?> 

        </body>
        </html>
        <?php
    }




}
catch(PDOException $e){
    echo $e->getMessage();
}

?>