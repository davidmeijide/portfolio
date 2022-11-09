<?php
@session_start();
if(!isset($_SESSION['user'])){
    header('Location: login.php');
}

require_once('config/config.php');
try{
    if(isset($_GET['confirmaReserva'])){
        $id_produto = $_SESSION['id_produto'];
        $dataInicio = $_SESSION['dataInicio'];
        $dataFin = $_SESSION['dataFin'];
        $consulta_produtos = $pdo->query("SELECT prezo FROM produto WHERE id_produto LIKE '$id_produto'");
        $prezo=$consulta_produtos->fetch()['prezo'];
        $prezo_total = strtotime($dataFin) - strtotime($dataInicio);
        $prezo_total = $prezo * ($prezo_total/86400);
        $insercion_aluguer = $pdo->prepare("INSERT INTO aluga(data_inicio,
        data_fin, prezo_total, devolto, fk_id_produto, cod_cliente)
                                         VALUES(?,?,?,0,?,?)");
        $prezo_total = intval($prezo_total);
        $id_usuario = $_SESSION['user'];
        $insercion_aluguer->bindParam(1,$dataInicio);
        $insercion_aluguer->bindParam(2,$dataFin);
        $insercion_aluguer->bindParam(3,$prezo_total);
        $insercion_aluguer->bindParam(4,$id_produto);
        $insercion_aluguer->bindParam(5,$id_usuario);

        $insercion_aluguer->execute();
        echo '<p>Produto reservado</p>';
        echo '<a href="mostra.php"><button>Volver</button></a>';

    }

}
catch(PDOException $e){
    echo $e->getMessage();
}

?>