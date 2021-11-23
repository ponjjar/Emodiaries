<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="../css/css.css"/>
  <script src="https://kit.fontawesome.com/df1511442e.js" crossorigin="anonymous"></script>
</head>
<body>

  <center>
    <header>
      <a href="./index.php"><h1><img src="http://emodiaries.ga/Imagens/emodiaries.png" width="25%"></img></h1></a>
      <!---Criando para falar mais sobre nós.-->
    </header>

    <nav>
      <div class="popup">
        <span class="popuptext" id="myPopup">Feito com carinho por<br/> Caique Ponjjar<br/> Breno Stevanatto<br/> Rhyan S. Oliveira</span>
      </div>
      <p id="sobrenos"onclick="popup();">Sobre nós</p>
    </nav>

  </center>
  <script>
  function popup() {
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
  }
  </script>
</body>
