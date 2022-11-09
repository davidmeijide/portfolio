<?php
@session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
}

require_once('config/config.php');
try{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reserva.css">
        <title>Reserva</title>
    </head>
    <body>
    <a id="pechaSesion" href="pechaSesion.php">Pechar sesión</a>
    <?php
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(isset($_GET['alugar'])){
        $id_produto = $_GET['alugar'];
        $_SESSION['id_produto'] = $id_produto;
        $consulta_produtos = $pdo->query("SELECT * FROM produto WHERE id_produto LIKE '$id_produto'");
        $fila=$consulta_produtos->fetch();
        $id_produto = $fila['id_produto'];
        $nome = $fila['nome'];
        $ruta_imaxe = $fila['imaxe'];
        $descricion = $fila['descricion'];
        $prezo = $fila['prezo'];
        
        echo '<div class="card">';
        echo "<h4>$nome</h4>";
        echo "<img src='imaxes/$ruta_imaxe'>";
        echo "<p>$descricion</p>";
        echo "<h5>$prezo €/día</h5>";
        echo '</div>';
        ?>
        <form action="reserva.php">
        <label for="dataInicio">Data de inicio</label><br>
        <input type="date" name="dataInicio" id="dataInicio"><br><br>

        <label for="dataFin">Data de fin</label><br>
        <input type="date" name="dataFin" id="dataFin"><br>
        <?php
        echo "<button id='reservar' name='reservar' value='$id_produto'>Reservar</button>"
        ?>
        </form>
        <?php
    }

    if(isset($_GET['reservar'])){
        $_SESSION['dataInicio'] = $_GET['dataInicio'];
        $_SESSION['dataFin'] = $_GET['dataFin'];
        $id_produto = $_SESSION['id_produto'];
        $consulta_produtos = $pdo->query("SELECT prezo FROM produto WHERE id_produto LIKE '$id_produto'");
        $prezo=$consulta_produtos->fetch()['prezo'];
        $dias_totales = (strtotime($_GET['dataFin']) - strtotime($_GET['dataInicio']))/86400;

        $prezo_total = $prezo * $dias_totales;
        echo '<p>Data de inicio do aluguer: '.$_GET["dataInicio"].'</p>'; 
        echo '<p>Data de fin do aluguer: '.$_GET["dataFin"].'</p>'; 
        echo "<p id='desglose'>Prezo total : ($prezo € / día x $dias_totales días) = <b>$prezo_total €</b></p>";
        echo '<p>¿Desexa confirmar a reserva?</p>';
        echo '<form action="confirmaReserva.php">';
        echo '<a href="mostra.php"><button>Cancelar</button></a>';
        echo '<button name="confirmaReserva" value="confirmaReserva">Confirmar</button>';
        echo '</form>';
        }


}
catch(PDOException $e){
    echo $e->getMessage();
}
?>

</body>
    </html>
