<?php
	session_start();	// старт сессии
?>


<?php
 echo "<html>
 <head>
      <title>Main page</title>
      <link rel='stylesheet' type='text/css' href='formstyle.css' />
 </head>
 <body>"
?>
<?php	// Если произошла авторизация - вывести сообщение с именем  пользователя.
		echo "<div id='status'>";
		if (isset ($_SESSION['username']) && $_SESSION['username'] != ""){
		echo "Hello, ".$_SESSION['username']."!";
		}
		echo "</div>";
?>
<?php	// переход на форму авторизации
 echo "<a href='autorisation.html'><button id='status_button' type='button' name='signup' class='status_button'>Autorisation</button>
	</a>
 </body>
</html>"
?>

