<!---REMOVEDOR CHAMADO PELO ALTER DATA ADMIN:--->

<link rel="stylesheet" href="../css/css.css" media="all" type="text/css">
<?php
include "./Login/db_connection.php";

$email=$_POST['email'];
$data=$_POST['data'];
echo "<center><fieldset><p>email: ". $email."</p> <p>data:". $data. "</p></fieldset>";
//CHECA SE A OPÇÃO EXCLUIR CONTA ESTÁ MARCADA.
/*if(isset($_POST['excluir']) && !isset($_POST['cancelar'])){
	//DELETA O USUARIO.
	echo '<fieldset style="animation: fadeIn 1s;"><h3>Tem certeza que deseja deletar o usario:</h3> <p> Email: </p>'. $_POST['emailanterior'] .'<p> Nome: </p>'.$nome.'<p> Telefone: </p>'.$_POST['telefone'].'<p> Endereço: </p>'.$_POST['endereco'].' </fieldset>';
	echo'<form method="post"><input type="submit" name="remove" value="excluir">';
	echo'<input type="submit" name="cancelar" value="cancelar"/>';
	echo'<input type="hidden" name="emailatual" value='.$_POST['emailanterior'].'><br/><br/></form><hr/>';
}
if(isset($_POST['remove'])){
	*/
    mysqli_query($db_connection, "DELETE FROM `anotacoes` WHERE `email` LIKE '".$email."' AND `data` LIKE '".$data."'");
    if (mysqli_affected_rows($db_connection)){
    	echo "<br/><fieldset> <summary>Console:</summary><hr id='cut'><p>Excluido com sucesso</p>";
    }
	else{
	    echo "<br/><fieldset> <summary>Console:</summary><hr id='cut'><p>Ops, Não foi possivel efetuar a consulta.</p>";}
//}
//SE NÃO ALTERA OS DADOS DO USUARIO PREENCHIDO.
/*else{
	if(isset($_POST['aplicar']) && !isset($_POST['excluir'])){
		mysqli_query($db_connection, "UPDATE usuario SET username = '".$_POST['nomeusuario']."' WHERE user_email='".$email."'");
		mysqli_query($db_connection, "UPDATE usuario SET user_email = '".$_POST['emailusuario']."' WHERE user_email='".$email."'");
		mysqli_query($db_connection, "UPDATE usuario SET telefone = '".$_POST['telefone']."' WHERE user_email='".$email."'");
		mysqli_query($db_connection, "UPDATE usuario SET endereco = '".$_POST['endereco']."' WHERE user_email='".$email."'");
		$message = "Efetuado com sucesso.";}
	}/*/

	?>

<script>
  window.location.href = "./index.php";
</script>