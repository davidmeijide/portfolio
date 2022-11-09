<?php
@session_start();
if($_SESSION['rol']!="admin"){
    header('Location: mostra.php');
}
require_once('config/config.php');
try{    
    //APLICAR CAMBIOS (SOLO SELECT)
    if(isset($_GET['aplicarCambios'])){
        $error="";
        foreach ($_GET as $user => $rol){
            if($user=="aplicarCambios"){
                continue;
            }
            if($user=="admin" && $rol!="admin"){
                $error = "<p class='vermello'>O usuario $user non pode ser modificado.</p>";
                continue;
            }
            $set_cambios = $pdo->prepare("UPDATE usuarios SET rol = ?
                                            WHERE nome LIKE '$user'");
            $set_cambios ->bindParam(1,$rol);    
            $set_cambios->execute();
        }
        $_SESSION['msg']['error_modificar']=$error;
        $_SESSION['msg']['cambios_aplicados']='<p class="verde">Cambios aplicados</p>';
    }
    if(isset($_GET['eliminarUsuario'])){
        $usuario_borrar = $_GET['eliminarUsuario'];
        try{
            if($usuario_borrar == "admin"){
                $_SESSION['msg']['error_borrar'] = "<p class='vermello'>O usuario $usuario_borrar non pode ser eliminado.</p>";
            }
            else{
                $sentenza_borrado = $pdo->prepare("DELETE FROM usuarios WHERE nome LIKE ?");
                $_SESSION['msg']['cambios_aplicados']='<p class="verde">Cambios aplicados</p>';
                $sentenza_borrado->bindParam(1,$usuario_borrar);
                $sentenza_borrado->execute();
            
            }
        }
        catch(Exception $e){
            if($e->getCode()=='23000'){
                $_SESSION['msg']['error_borrar'] = "<p>O usuario que intenta borrar ten alugueres en curso.</p>";
            }
            else{
                echo $e->getMessage();
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/mostra.css">
        <title>Xestiona</title>
    </head>
    <body>
    <nav>
        <a id="btn-xestionaProdutos" href="xestionaProdutos.php"><button>Xestionar produtos</button></a>
        <a id="pechaSesion" href="pechaSesion.php">Pechar sesión</a>
    </nav>

    <?php
    //MOSTRAR TABLA
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $consulta_usuarios = $pdo->query("SELECT * FROM usuarios");
    echo '<h3>Xestión de usuarios</h3>';
    echo '<form action="xestiona.php">';
    echo '<table id="tabla-usuarios">';
    echo '<tr><th>Nome de usuario</th><th>Nome completo</th><th>e-mail</th><th>Último inicio de sesión</th><th>Rol</th><th>Eliminar</th></tr>';
    while($fila = $consulta_usuarios->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>';
        echo "<td>".$fila['nome']."</td>";
        echo "<td>".$fila['nomeCompleto']."</td>";
        echo "<td>".$fila['email']."</td>";
        echo "<td>".$fila['data']."</td>";
        
        echo "<td><select name=".$fila['nome'].">";
        if($fila['rol']=="admin"){
            echo "<option selected='selected' value='admin'>Admin</option>";
            echo "<option value='user'>Usuario</option>";
        }
        else{
            echo "<option value='admin'>Admin</option>";
            echo "<option selected='selected' value='user'>Usuario</option>";
        }
        echo "</select></td>";
        echo "<td class='btn-eliminar'><button class='eliminar' value=".$fila['nome']." name='eliminarUsuario'>X</button></td>";
        echo '</tr>';
    }
    echo '</table>';
    echo '<input type="submit" name="aplicarCambios" value="Aplicar cambios">';
    echo '</form>';

    //IMPRIMO MENSAXES DE APLICAR CAMBIOS
    if(isset($_SESSION['msg'])){
        foreach($_SESSION['msg'] as $key => $msg){
            echo $msg;
            //unset($_SESSION['msg'][$key]);
        }
        $_SESSION['msg']['cambios_aplicados']="";
        $_SESSION['msg']['error_borrar']="";
        $_SESSION['msg']['error_modifcar']="";

    }



}
catch(PDOException $e){
    echo $e->getMessage();
}

?>
</body>
</html>