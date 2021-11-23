<?php
session_start();
require './Login/db_connection.php';
// CHECK USER IF LOGGED IN
if(isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])){

$user_email = $_SESSION['user_email'];
$get_user_data = mysqli_query($db_connection, "SELECT * FROM `usuario` WHERE user_email = '$user_email'");
$userData =  mysqli_fetch_assoc($get_user_data);

}else{
header('Location: logout.php');
exit;
}

$favoritos = $_POST["favoritos"] ;
$areatexto = $_POST["texto"] ;
$titulo = $_POST["titulo"] ;
$data = date('d/m/Y \à\s H:i:s');
//echo 'Nome: '. $userData['username'].'\ email: '.  $_SESSION['user_email'].'\ Favoritos: '. $favoritos. '| Texto: '. $areatexto;
//Inserindo os dados favoritos que a pessoa selecionou.
//mysqli_query($db_connection,"UPDATE users SET emojis = '$favoritos' WHERE user_email='$user_email'");
//mysql_query("UPDATE cadastro3 SET nome ='$Nome',experiencia='$experiencia',email='$email' WHERE id=$id");
$areatexto = str_replace(array('\'', '"','`'), '"', $areatexto);
 mysqli_query($db_connection, "INSERT INTO `anotacoes` (`texto`, `emojis`, `nome`, `email`, `titulo`, `data`) VALUES ('".$areatexto."', '".$favoritos."', '".$userData["username"]."', '".$_SESSION["user_email"]."', '".$titulo."', '".$data."')");
//$insert_user = mysqli_query($db_connection, "INSERT INTO `fav` FROM `usuario` where user_email ='$user_email' (fav) VALUES ('$favoritos')";
//"SELECT `fav` FROM `users` WHERE user_email = '$user_email'")
//echo '<input id="recomendados" type="text" value="'. $favoritos.'/">';
?>
<script>
  window.location.href = "./index.php";
</script>
