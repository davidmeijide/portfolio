<?php
@session_start();
require_once('config/config.php');
if($_SESSION['rol']=="usuario"){
    try{
        //Engadir comentario
        if(isset($_GET['engadir_comentario'])){
            $data = date('Y-m-d');
            $sentenza_insercion_comentario = $pdo->prepare("INSERT INTO comentarios(fk_nome_usuario,comentario,data) VALUES(?,?,?)");
            $sentenza_insercion_comentario->bindParam(1, $_SESSION['user']);
            $sentenza_insercion_comentario->bindParam(2, $_GET['texto_comentario']);
            $sentenza_insercion_comentario->bindParam(3, $data);

            $sentenza_insercion_comentario->execute();
            header('Location: mostra.php');
        }
        $user = $_SESSION['user'];
        //Saúdo
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/mostra.css">
            <title>Mostra</title>
        </head>
        <body>
        <a id="pechaSesion" href="pechaSesion.php">Pechar sesión</a>
            
        <?php
        switch($_COOKIE['lingua']){
            case 'gl':
                echo 'Benvid@, '.$user;
                break;
            case 'es':
                echo 'Bienvenid@, '.$user;
                break;
            case 'en':
                echo 'Welcome, '.$user;
                break;
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $consulta_produtos = $pdo->query("SELECT * FROM produto");
        echo '<h2>Produtos dispoñibles</h2>';
        echo '<div id="card_container">';
        while($fila=$consulta_produtos->fetch()){
            echo '<div class="card">';
            $id_produto = $fila['id_produto'];
            $nome = $fila['nome'];
            $familia = $fila['familia'];
            $ruta_imaxe = $fila['imaxe'];
            $descricion = $fila['descricion'];
            $prezo = $fila['prezo'];
            echo "<h4>$nome</h4>";
            echo "<p>$familia</p>";
            echo "<img src='imaxes/$ruta_imaxe'>";
            echo "<p class='descricion'>$descricion</p>";
            echo "<h5>$prezo € por día</h5>";
            echo "<form action='reserva.php'>";
            echo "<button name='alugar' value='$id_produto'>Alugar</button>";
            echo "</form>";
            echo '</div>';
        }
        echo '</div>';

        //Reservas
        $consulta_reservas = $pdo->query("SELECT * FROM aluga WHERE cod_cliente LIKE '$user'");
        echo '<div class="comentarios_container">';
        echo '<h2>Reservas</h2>';
        if($consulta_reservas->rowCount()!=0){
            echo '<table><tr><th>Produto</th><th>Data de incio</th><th>Data de fin</th><th>Prezo total</th><th></th></tr>';
            while($fila=$consulta_reservas->fetch()){
                $id_produto = $fila['fk_id_produto'];
                $consulta_nome_produto = $pdo->query("SELECT nome FROM produto WHERE id_produto LIKE '$id_produto'");
                $consulta_nome_produto = $consulta_nome_produto->fetch();

                $data_inicio = new DateTime($fila['data_inicio']);
                $data_fin = new DateTime($fila['data_fin']);
                $data_inicio = $data_inicio->format('d-m-Y');
                $data_fin = $data_fin->format('d-m-Y');
                $id_aluguer = $fila['id_aluguer'];
                echo "<tr><td>$consulta_nome_produto[0]</td><td>$data_inicio</td><td>$data_fin</td><td>".$fila['prezo_total']."</td><td><form action='mostra.php'><button name='cancelar' value='$id_aluguer'>Cancelar</button></form></td></tr>";
            }
            echo '</table>';
        }
        elseif($consulta_reservas->rowCount()==0){
            echo 'Non hai reservas en curso.';
        }

        //Cancelar reserva
        elseif(isset($_GET['cancelar'])){
            $id_aluguer = $_GET['cancelar'];
            $cancelar_reserva = $pdo->prepare("DELETE FROM aluga WHERE id_aluguer LIKE ?") ;
            $cancelar_reserva->bindParam(1,$id_aluguer);
            $cancelar_reserva->execute();
            echo 'Reserva cancelada. <a href="mostra.php">Recargue a páxina.</a>';
        }

        //Comentarios
        $consulta_comentarios=$pdo->query("SELECT * FROM comentarios LIMIT 5");
        echo '<div class="comentarios_container">';
        echo '<h2>Comentarios</h2>';
        while($fila=$consulta_comentarios->fetch()){
            echo '<article class="comentario">';
            echo "<p><q>".htmlspecialchars($fila['comentario'])."</q>";
            echo " - ".$fila['fk_nome_usuario']."</p>";
            echo '</article>';
        }
        echo '</div>';
        echo '<form action="mostra.php">';
        echo '<textarea name="texto_comentario" rows="5" cols="70" placeholder="Escribe o teu comentario"></textarea><br>';

        echo '<button name="engadir_comentario">Engadir comentario</button></form>';


 



    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
}
elseif($_SESSION['rol']=="admin"){
    header('Location: xestiona.php');
}
else{
    header('Location: login.php');
}


?>
</body>
</html>
