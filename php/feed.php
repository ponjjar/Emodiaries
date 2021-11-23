
<!---PAGINA DE USUARIOS (ADMIN, USUARIO COMUM, USUARIO NÃO LOGADO):--->
<?php
include './Incluidos/cabecalho.php';
require './Login/alteruser.php';
ini_set('display_errors','Off');
session_start();
require './Login/db_connection.php';
//ALTERA O HORARIO PARA O FUSO DE SÃO PAULO
date_default_timezone_set('America/Sao_Paulo');
// CHECA SE O USUARIO ESTÁ LOGADO
if(isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])){
  $user_email = $_SESSION['user_email'];
  $get_user_data = mysqli_query($db_connection, "SELECT * FROM `usuario` WHERE user_email = '$user_email'");
  $userData =  mysqli_fetch_assoc($get_user_data);
  $hourMin = date('H:i');
  echo "<center><form action='feed.php' method='get'><input type='text' style='width:40%'name='pesquisa' id='buscaricon'placeholder='Buscar' maxlength='25' required></input>
  <button  class='fas fa-search'  type='submit' style='width:35px'></button>
  </form><fieldset><h3>OLA, ". strtoupper($userData['username']) . " </h3> <a href='#' onclick='window.location.href=`./Login/logout.php`'><i>SAIR</i></a><br/>";

  echo "<br/><button onclick='window.location.href=`./index.php`'>Meu Diário</button>";
  //CHECA SE O USUARIO QUE ESTA LOGADO NÃO É UM ADMIN:
  if(strcmp($userData['user_email'], "admin@ifsp.edu.br")){
    //FORMULARIO PARA ALTERAR SEUS DADOS DE USARIO
    
  }
  // CASO O USUARIO LOGADO SEJA UM ADMIN:
  else{
    echo"<fieldset id='fielddata'><legend>Alterar dados de usuarios</legend>";
    echo "<table>";
    require './Login/remove.php';
    $resultado = mysqli_query($db_connection, "select * from usuario");
    //IMPRIME MENSAGEM SE HOUVER ALGO ESCRITO.
    if(!empty($message)){
      echo '<center><div id="sumir">'. $message . "</div>";
    }
    
    echo '</center> <div id="hora">Consulta realizada as '.$hourMin.' horas.</div>';
    echo"<th>Nome</th><th>Email</th><th>Endereço</th><th>Telefone</th><th colspan='2'>Opções</th>";
    //FORMULARIO OU TABELA PARA ALTERAR OS DADOS DE OUTROS USUARIOS:
    while($usuario = mysqli_fetch_assoc($resultado)) {
      //VERIFICA SE O NOME É IGUAL AO DO ADMIN E REMOVE DA LISTA.
      if(strcmp($usuario['user_email'], "admin@ifsp.edu.br")){
        echo "<form action='#' method='post'><tr><td>
        <input type='text' name='nomeusuario' value='".$usuario['username']."' id='dado'></td> <td>
        <input type='email' name='emailusuario' value='".$usuario['user_email']."' id='dado'>
        <input type='hidden' name='emailanterior' value='".$usuario['user_email']."' id='dado' readonly></td><td>
        <input type='text' name='endereco' value='".$usuario['endereco']."' id='dado'> </td><td>
        <input type='text' name='telefone' minlength=15 class='form-control' onkeypress='$(this).mask(`(00) 00000-0009`)' value='".$usuario['telefone']."' id='dado'> </td><td>
        <input type = 'checkbox' name='excluir'></input><a><i>excluir</i></a> </td>
        ";
        
        echo "<td><input type=submit name='aplicar' value='aplicar' id='dado'></td></tr></form></details></fieldset>";    }
      }
    }
  //IMPRIME ANOTAÇÕES DO USUÁRIO
    //Verifica se tem alguma variavel de pesquisa
    if(isset($_GET['pesquisa'])) {
      $pesquisar = $_GET['pesquisa'];
      $resultado = mysqli_query($db_connection, "SELECT * FROM `anotacoes` WHERE `texto` LIKE '%".$pesquisar."%' OR `titulo` LIKE '%".$pesquisar."%' OR  `nome` LIKE '%".$pesquisar."%' OR  `data` LIKE '%".$pesquisar."%'");
      echo "</center><br/><fieldset><center><hr/><h3> PESQUISA</h3> </center><div id='hora'>Ultima consulta: ".$hourMin." horas.</div></fieldset>";

      }
    else{ 
      $resultado = mysqli_query($db_connection, "SELECT * FROM `anotacoes` ORDER BY `data` desc"); 
      echo "</center><br/><fieldset><center><hr/><h3> FEED DE ANOTAÇÕES</h3> </center><div id='hora'>Ultima consulta: ".$hourMin." horas.</div></fieldset>";

    }
  $emoji = "";
  $emojidef = "";
  $loops = 0;
   //FORMULARIO OU TABELA PARA ALTERAR OS DADOS DE OUTROS USUARIOS:
  while($usuario = mysqli_fetch_assoc($resultado)) {
      $loops += 1;
    //verifica se o titulo é vazio
      if($usuario['titulo'] == ""){
        if($usuario['texto'] != ""){
        $usuario['titulo'] = ((ucfirst(substr($usuario['texto'], 0, 40)). "..."));  
       }
       else{
        $usuario['titulo'] = "Texto em branco";
       }
      }
      $emoji = $usuario['emojis'];
      $emojidef = $usuario['emojis'];
      if($emoji == "01"){
        $emoji = "<i class='fas fa-grin-stars'></i>";
        $emojidef = "<i class='fas fa-grin-stars'> Ótimo</i>";
      }
      if($emoji == "02"){
        $emoji = "<i class='fas fa-laugh'></i>";
        $emojidef = "<i class='fas fa-laugh'> Bom</i>";
      }
      if($emoji == "03"){
        $emoji = "<i class='fas fa-meh'></i>";
        $emojidef = "<i class='fas fa-meh'> Normal</i>";
      }
      if($emoji == "04"){
        $emoji = "<i class='fas fa-sad-tear'></i>";
        $emojidef = "<i class='fas fa-sad-tear'> Ruim</i>";
      }
      if($emoji == "05"){
        $emoji = "<i class='fas fa-tired'></i>";
        $emojidef = "<i class='fas fa-tired'> Péssimo</i>";
      }
      
      //IMPRIME ALAS DE NOTAS
      echo "<hr id='cut'/><fieldset>
      <summary type='text' name='titulonota' style='text-align:center;' id='dado'>".$usuario['titulo']." <font size=05>". $emoji."</font></summary>
      </center><div id='hora'>Publicado por ".$usuario['nome']."</div>
      <p style='color:white; text-align: justify;' type='text' name='endereco' id='dado'>".$usuario['texto']."</p> <td><center><h5>Sentindo-se ".$emojidef."</h3></center>
     <input value='".$usuario['data']."' name='data' style='display:none'/><input value='".$usuario['email']."' name='email' style='display:none'/>
      <div id='hora' style='float:left; margin-left:0%; position:absolute'> publicado ".$usuario['data']."</div></td>
     <br/>
      </fieldset><br/>";
    }
    
  if( $loops == 0){
        echo "<fieldset><center>
        <p><font color='white'>Não há resultados para ".'</font>"'.$pesquisar.'"'."</p>
        <img src='../Imagens/bemo.gif' width='175px'></img><br/>
        <button onclick='window.location.href=`./feed.php`'>Voltar ao Feed</button>
        </center></fieldset>";
    }
    echo "</fieldset>";
  }else{
    //MENSAGEM DE USUARIO NÃO AUTENTICADO:
    echo "<center><fieldset><p>Você não está autenticado</p> </fieldset> <br/> <button onclick='window.location.href=`./Login`'>Fazer login</button></center>";
  }

  ?>

  <html lang="pt-br">
  <head>
    <!---IMPORTA OS SCRIPTS DE MASK PARA O NUMERO DE TELEFONE--->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="../css/css.css" media="all" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Autenticação</title>
  </head>
  <?php
  //IMPRIME RODA PÉ:
  if(isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])){
    if(strcmp($userData['user_email'], "admin@ifsp.edu.br")){
      $footermessage="</fieldset><br/><hr/><footer><fieldset id='fielddata'><legend>Dicas</legend><p> Altere seus dados preenchendo os espaços acima e clicando em <input type='button'readonly value='alterar'/></p>
      <p> Não é necessário preencher todos</p>
      <p>Para sair da conta e retornar a tela de login, clique em <u><i style='color:white'>sair</i></u></p>
      <p>Para saber mais, clique em <span id='sobrenos' style='display:inline;'> Sobre nós </span></footer>";}
      else{
        $footermessage="<br/><hr/><footer><fieldset id='fielddata'><legend>Dicas</legend><p> É possivel alterar os dados de um usuario, modificando uma linha por vez e clicando em <input type='button'readonly value='aplicar'/></p>
        <p> Para excluir um usuario, marque a caixa <input type='checkbox'><i style='color:white'>excluir</i> e clique em <input type='button'readonly value='aplicar'/></p>
        <p>Para sair da conta e retornar a tela de login, clique em <u><i style='color:white'>sair</i></u></p>
        <p>Para saber mais, clique em <span id='sobrenos' style='display:inline;'> Sobre nós </span></footer>";
      }}
      
      else{

$footermessage="<br/><hr/><footer><fieldset id='fielddata'><legend>Dicas</legend>Rodar o script sql/usuario.sql para criar o banco de dados no phpmyADMIN.</footer>";
      }
      require 'Incluidos/rodape.php';
      ?>
      
<!--<form action="favregister.php" method="post">--->

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
var x;
var y= document.getElementById("hide6a");
var Stringval = "03";
var times = 0;
var maxtimesearch = 4;
var Favoritos="03"; //Aqui ele vai guardar onde o usuario curtiu. (ele guarda 5 letras por tag.)
function hide01(){
    x = document.getElementById("hide1");
    Stringval = "01";
    Favoritos = "01";
    Toggle();
    x = document.getElementById("hide1a");
}
function hide02(){
    x = document.getElementById("hide2");
    Stringval = "02";
     
    Favoritos = "02";
    Toggle();
    x = document.getElementById("hide2a");
}
function hide03(){
    x = document.getElementById("hide3");
    Stringval = "03";
     
    Favoritos = "03";
    Toggle();
    x = document.getElementById("hide3a");
}
function hide04(){
    x = document.getElementById("hide4");
    Stringval = "04";
     
    Favoritos = "04";
    Toggle();
    x = document.getElementById("hide4a");
}
function hide05(){
    x = document.getElementById("hide5");
    Stringval = "05";
     
    Favoritos = "05";
    Toggle();
    x = document.getElementById("hide5a");
}
function hide06(){
    x = document.getElementById("hide6");
    Stringval = "06";
     
    Favoritos = "06";
    Toggle();
    x = document.getElementById("hide6a");
}
function Toggle() {
    document.getElementById("favs").value = Favoritos;
    document.getElementById("hide5").style.color = "rgb(207, 191, 99)";
    document.getElementById("hide4").style.color = "rgb(207, 191, 99)";
    document.getElementById("hide3").style.color = "rgb(207, 191, 99)";
    document.getElementById("hide2").style.color = "rgb(207, 191, 99)";
    document.getElementById("hide1").style.color = "rgb(207, 191, 99)";
    x.style.color = "lightblue  ";
    console.log(x.style.color);
  }
  

$(document).ready(function () {
    $("#reset").click(function (e) {
        location.reload();
    });

    $("#submit").click(function (e) {
        $("#outputDiv").html("");
    });
});
</script>
