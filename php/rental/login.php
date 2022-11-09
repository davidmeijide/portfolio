<?php
@session_start();
require_once('config/config.php');
    try{
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Gardar lingua en cookie
        if(isset($_GET['lingua'])){
            setcookie('lingua',$_GET['lingua'],time()+300);
            header('Location: login.php');
        }
        //por defecto, en galego
        if(!isset($_COOKIE['lingua'])){
            setcookie('lingua','gl',time()+300);
            header('Location: login.php');

        }
        // COMPROBA LOGIN CORRECTO
        if(isset($_GET['login'])){
            try{
                $username = $_GET['username'];
                $pass = $_GET['pass'];
                //Comprobamos se existe o usuario
                $consulta =  $pdo->query("SELECT * FROM usuarios 
                                                WHERE nome LIKE '$username'");
                if($consulta->rowCount()==1){
                    $consulta = $consulta->fetch(PDO::FETCH_ASSOC);
                    $rol = $consulta['rol'];
                    $hashed_pass = $consulta['contrasinal'];
                    if (password_verify($pass,$hashed_pass)){
                        $_SESSION['user'] = $username;
                        $_SESSION['rol'] = $rol;
                        session_regenerate_id(true); //Protección contra session fixation
                        header('Location: mostra.php');
                    }
                    else{
                        echo 'As credenciais son incorrectas.';
                    }
                }
                else{
                    echo 'As credenciais son incorrectas.';
                }
            }
            catch(Exception $e){
                echo 'Error no inicio de sesión.'.$e->getMessage();
            }
        }    


        // COMPROBA REXISTRO CORRECTO 
        if(isset($_COOKIE['lingua'])){
            //[galego]
            if($_COOKIE['lingua']=="gl"){
                ?>
                <form action="login.php">
                    <select name="lingua" id="lingua" onchange="this.form.submit()">
                        <option value="default" selected disabled>Lingua</option>
                        <option value="gl">Galego</option>
                        <option value="es">Español</option>
                        <option value="en">Inglés</option>
                    </select>
                    <input type="submit" name="cambiarLingua" id="cambiarLingua" hidden>
                </form>
                    <h3>Iniciar sesión</h3>
                    <form action="login.php">
                        <label for="username">Nome de usuario</label>
                        <input type="text" name="username" id="username" required><br>
    
                        <label for="pass">Contrasinal</label>
                        <input type="password" name="pass" id="pass" required><br>
                        
                        <button name="login" value="login">Iniciar sesión</button>
                    </form>   
                    <p>¿Non estás rexistrado? <a href="rexistro.html">Rexistrarse</a></p>
    
                <?php
            }
            //Español
            if($_COOKIE['lingua']=="es"){
                ?>
                <form action="login.php">
                    <select name="lingua" id="lingua" onchange="this.form.submit()">
                        <option value="default" selected disabled>Idioma</option>
                        <option value="gl">Galego</option>
                        <option value="es">Español</option>
                        <option value="en">Inglés</option>
                    </select>
                    <input type="submit" name="cambiarLingua" id="cambiarLingua" hidden>
                </form>
                    <h3>Iniciar sesión</h3>
                    <form action="login.php">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" name="username" id="username" required><br>
    
                        <label for="pass">Contraseña</label>
                        <input type="password" name="pass" id="pass" required><br>
                        
                        <button name="login" value="login">Iniciar sesión</button>
                    </form>   
                    <p>¿No estás registrado? <a href="rexistro.html">Registrarse</a></p>
    
                <?php
            }
            //Inglés
            if($_COOKIE['lingua']=="en"){
                ?>
                <form action="login.php">
                    <select name="lingua" id="lingua" onchange="this.form.submit()">
                        <option class="gris" value="default" selected disabled>Language</option>
                        <option value="gl">Galego</option>
                        <option value="es">Español</option>
                        <option value="en">Inglés</option>
                    </select>
                    <input type="submit" name="cambiarLingua" id="cambiarLingua" hidden>
                </form>
                    <h3>Login</h3>
                    <form action="login.php">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required><br>
    
                        <label for="pass">Password</label>
                        <input type="password" name="pass" id="pass" required><br>
                        
                        <button name="login" value="login">Login</button>
                    </form>   
                    <p>Not registed?<a href="rexistro.html">Register</a></p>
    
                <?php
            }
            




            if(isset($_GET['rexistrar'])){
                try{
                    $pass_hasheado = password_hash($_GET['pass'],PASSWORD_DEFAULT);
    
                    $pdoStatement = $pdo->prepare("INSERT INTO usuarios (nome,contrasinal,nomeCompleto,email,data,rol)
                            VALUES(?,?,?,?,?,'usuario')");
                    $pdoStatement->bindParam(1,$_GET['username']);
                    $pdoStatement->bindParam(2,$pass_hasheado);
                    $pdoStatement->bindParam(3,$_GET['nomeCompleto']);
                    $pdoStatement->bindParam(4,$_GET['email']);
                    $data = date('Y-m-d H:i:s');
                    $pdoStatement->bindParam(5,$data);
    
                    $pdoStatement->execute();
                    echo 'Rexistro completado. Inicie sesión.';
                }
    
                catch(Exception $e){
                    echo $e->getMessage();
                }
            }
        }
    
    }
    catch(PDOException $e){
        echo 'Erro de conexión coa BD. '.$e->getMessage();
    }
?>