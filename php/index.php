
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
  $openNewUser = '';
  $hourMin = date('H:i');
  echo "<center><fieldset><h3>OLA, ". strtoupper($userData['username']) . "</h3> <a href='#' onclick='window.location.href=`./Login/logout.php`'><i>SAIR</i></a><br/><hr/>";
  //CHECA SE O USUARIO QUE ESTA LOGADO NÃO É UM ADMIN:
  if(strcmp($userData['user_email'], "admin@ifsp.edu.br")){
    //FORMULARIO PARA ALTERAR SEUS DADOS DE USARIO
    echo "<details><summary><u>Alterar seus dados</u></summary><fieldset id='Emotefieldset'>
     <p>Nome</p>
    <form id='alterardados' action='' method='post' autocomplete='off'>
    <input type='text' name='nome' autocomplete='off' placeholder='".$userData['username'].   "'/>
    <br/> <p>Email</p>
    <input type='email' name='email' placeholder='".$userData['user_email']."'/>
<p>Senha</p>
    <input type='password' name='senha' minlength=8 placeholder='********'/><br/></br>
    <input type='submit' name='alterar' value='alterar'/></form></details>";
    //Anotações
    echo "<hr id='middle'><details><summary><labbel><u>Escrever nota</u></labbel></summary><fieldset>
    <p> Cole um Emoticon</p>
    <form autocomplete='off' action='./postar.php' method='post'>
    <div id='Emoticons'>
    <!--Selecionar os favoritos, logo começaremos a estudar a entidade-->
    <!--------->
    <i id='hide1' class='fas fa-grin-stars' onclick='hide01();this.disabled=true'>Ótimo</i>
    <!--------->
    <i id='hide2' class='fas fa-laugh'onclick='hide02();this.disabled=true'>Bom</i>
    <!--------->
    <i id='hide3' class='fas fa-meh' onclick='hide03();this.disabled=true'>Normal</i>
    <!--------->
    <i id='hide4' class='fas fa-sad-tear' onclick='hide04();this.disabled=true'>Ruim</i>
   <!--------->
    <i class='fas fa-tired' id='hide5' onclick='hide05();this.disabled=true'>Péssimo</i></div>
    <!------>
    
    <input type='text' id='favs' name='favoritos' value='03'style='display:none;'></input>
    <p>Anotação</p>
    <input type='text' placeholder='Titulo' maxlength='75' style='text-align:center; font-size:20px; width: 96%;' name='titulo'/><br/>
    <textarea name='texto' maxlength='2500'style='margin: 0px;font-size:16px; width: 96%; height: 180px;' required></textarea>
    <br/><br/> <input type='submit' name='Nova' value='Anotar ✔'/></form></fieldset>
    </details>
    ";
    $verify = mysqli_query($db_connection, "SELECT * FROM `anotacoes` WHERE email = '$user_email'");
    if(mysqli_num_rows($verify)!=0){ 
        echo "<hr id='middle'>
    <!--Dados do gráfico--><details><summary><labbel><u>Dados gráficos</u></labbel></summary>
    <p> Baseados no uso do site</p>
    <fieldset  style='height: 370px;margin: 0px; width: 350px;'>
    <div id='chartContainerPie'></div></fieldset><br/><br/>
    <fieldset  style='height: 370px;margin: 0px; width: 350px;'>
    <div id='chartContainer' style='height: 370px; width: 97%;'></div></fieldset></details>";
    }
    echo "  <br/>
    <button onclick='window.location.href=`./feed.php`'>Mural de Anotações</button>";
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
    echo"<th>Nome</th><th>Email</th><th colspan='2'>Opções</th>";
    //FORMULARIO OU TABELA PARA ALTERAR OS DADOS DE OUTROS USUARIOS:
    while($usuario = mysqli_fetch_assoc($resultado)) {
      //VERIFICA SE O NOME É IGUAL AO DO ADMIN E REMOVE DA LISTA.
      if(strcmp($usuario['user_email'], "admin@ifsp.edu.br")){
        echo "<form action='#' method='post'><tr><td>
        <input type='text'style='width:100%' name='nomeusuario' value='".$usuario['username']."' id='dado'></td> <td>
        <input type='email' name='emailusuario' style='width:100%' value='".$usuario['user_email']."' id='dado'>
        <input type='hidden' name='emailanterior' value='".$usuario['user_email']."' id='dado' readonly></td><td>
        <input type = 'checkbox' name='excluir'></input><a><i>excluir</i></a> </td>
        ";
        //     <input type='text' name='endereco' value='".$usuario['endereco']."' id='dado'> </td><td>
       // <input type='text' name='telefone' minlength=15 class='form-control' onkeypress='$(this).mask(`(00) 00000-0009`)' value='".$usuario['telefone']."' id='dado'>
        
        echo "<td><input type=submit name='aplicar' style='width:100%' value='aplicar' id='dado'></td></tr></form></details></fieldset>";    }
      }
    }
  //IMPRIME ANOTAÇÕES DO USUÁRIO
  $resultado = mysqli_query($db_connection, "SELECT * FROM `anotacoes` WHERE email = '$user_email' ORDER BY `data` desc");

  $emoji = "";
  $emojidef = "";
  
  echo "</center><br/><fieldset><center><summary>Diário</summary> 
  
  </center><div id='hora'>Ultima consulta: ".$hourMin." horas.</div></fieldset>";
  
  //FORMULARIO OU TABELA PARA ALTERAR OS DADOS DE OUTROS USUARIOS:
  $piechartY = "0";
  $piechartY = 1;
  $emojigeral = 0;
  $loops = 0;
  $emojicounts = array(0, 0, 0,0,0);
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
      
      $emojigeral += 1;
      $emoji = $usuario['emojis'];
      $emojidef = $usuario['emojis'];
      if($emoji == "01"){
        $emojicounts[0] += 1;
        $emoji = "<i class='fas fa-grin-stars'></i>";
        $emojidef = "<i class='fas fa-grin-stars'> Ótimo</i>";
      }
      if($emoji == "02"){
        $emojicounts[1] += 1;
        $emoji = "<i class='fas fa-laugh'></i>";
        $emojidef = "<i class='fas fa-laugh'> Bom</i>";
      }
      if($emoji == "03"){
        $emojicounts[2] += 1;
        $emoji = "<i class='fas fa-meh'></i>";
        $emojidef = "<i class='fas fa-meh'> Normal</i>";
      }
      if($emoji == "04"){
        $emojicounts[3] += 1;
        $emoji = "<i class='fas fa-sad-tear'></i>";
        $emojidef = "<i class='fas fa-sad-tear'> Ruim</i>";
      }
      if($emoji == "05"){
        $emojicounts[4] += 1;
        $emoji = "<i class='fas fa-tired'></i>";
        $emojidef = "<i class='fas fa-tired'> Péssimo</i>";
      }
      
      //IMPRIME ALAS DE NOTAS
      echo "<fieldset>
      <details>
      <summary type='text' name='titulonota' style='text-align:center;' id='dado'>".$usuario['titulo']." <font size=05>". $emoji."</font></summary>
      </center><div id='hora'>Feito por ".$usuario['nome']."</div>
      <p style='color:white; text-align: justify;' type='text' name='endereco' id='dado'>".$usuario['texto']."</p> <table> <td><center><h3>Sentindo-se ".$emojidef."</h3></center>
      <form method='post' action='excluir.php'><input value='".$usuario['data']."' name='data' style='display:none'/><input value='".$usuario['email']."' name='email' style='display:none'/>
      <div id='hora' style='float:left; margin-left:0%; position:absolute'> publicado ".$usuario['data']."</div><input style='float:right;' type='submit' name='remove' value='Excluir'/></td></table></form>
      </details><br/><hr id='cut'/>
      </fieldset>";
       $dataPointsPieChart = array(
      array('label' => "Péssimo", 'y'=> 100 * $emojicounts[4]/$emojigeral),
      
      array('label' => "Ruim", 'y'=> 100 * $emojicounts[3]/$emojigeral),
      
      array('label' => "Normal", 'y'=> 100 * $emojicounts[2]/$emojigeral),
      
      array('label' => "Bom", 'y'=> 100 * $emojicounts[1]/$emojigeral),
    
      array('label' => "Ótimo", 'y'=> 100 * $emojicounts[0]/$emojigeral)
  );
  
    $dataPoints[] = array('label' => $usuario["data"], 'y'=> $usuario["emojis"]);

    }
 if($loops == 0){
      echo'<fieldset><center>
      <p>'.strtoupper('Ola, seja bem vindo '.$userData['username'].'').'! <img src="../Imagens/bemoHeart.gif" width="5%"></img></p>
      <hr id="cut"/> 
      <font color="white"><p> - O seu diário está vazio. <u>Escreva uma nota</u> no seu novo diário! </p><p> - Dê uma olhada nas outras publicações pelo <u>mural de anotações </u></p></font>
        <img src="../Imagens/bemodancing.gif" width="75px"></img>
       <br/> 
      Ah, não esquece do nosso mascote, <b>Bemo</b>  </center>
      
      </fieldset>
      
      ';
          $dataPoints[] = array(null);
      $dataPointsPieChart[] = array(null);
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

<script>
var x;
var y= document.getElementById("hide6a");
var Stringval = "";
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
  

  window.onload = function () {
 
 //Gráfico de pizza
    var chart2 = new CanvasJS.Chart("chartContainerPie", {
   animationEnabled: true,
   theme: "dark2",
   backgroundColor: "#151620",
legend:{
    cursor: "pointer",
    verticalAlign: "center",
    horizontalAlign: "right"
},
   title:{
     text: "Emojis utilizados"
   },
   subtitles:[
		{
			text: "Contagem dos emojis mais utilizados"
			//Uncomment properties below to see how they behave
			//fontColor: "red",
			//fontSize: 30
		}
		],
    data: [{
		type: "pie",

		yValueFormatString: "#,##0.00\"%\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPointsPieChart, JSON_NUMERIC_CHECK); ?>
	}]
});
 chart2.render();
 //Chart de Linha
 var chart = new CanvasJS.Chart("chartContainer", {
   animationEnabled: true,
   theme: "dark2",
   backgroundColor: "#151620",
legend:{
    cursor: "pointer",
    verticalAlign: "center",
    horizontalAlign: "right"
},
   title:{
     text: "Variação de humor depressivo"
   },
   subtitles:[
		{
			text: "Váriação do humor negativo diário"
			//Uncomment properties below to see how they behave
			//fontColor: "red",
			//fontSize: 30
		}
		],
   axisX:{
   reversed:  true,
     includeZero: false,
     crosshair: {
       enabled: true,
       snapToDataPoint: true
     }
   },
   axisY:{
     title: "Variação do emoji negativo",
     includeZero: false,
     crosshair: {
       enabled: true,
       snapToDataPoint: true
     }
   },
   toolTip:{
     enabled: true
   },
   data: [{
     type: "area",
     dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
   }]
 });
 chart.render();
  
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

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
