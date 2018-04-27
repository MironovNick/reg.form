<?php
    session_start();	// Старт сессии
	
	$req = array("retcode" => 0, "msg"=> "");	// возвращаемый форме об'ект
	
	// получитьь данные с формы и "распарсить" их в массив $data
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	// Если нет логина или пароля - возврат с сообщением (код возврата 0)
	if(  !isset($data['login']) || !isset($data['password']) ){
		$req['msg'] =  "No login or password";
		echo json_encode($req);
		exit;
	}
	
	// логин и пароль в переменные
	$login = $data['login']; 
	$password = $data['password'];
	
	// Если логин или пароль пуст - возврат с сообщением (код возврата 0)
	if ($login === "" || $password === ""){
		$req['msg'] =  "Empty login or password";
		echo json_encode($req);
		exit;
	}
	// Прочитать XML файл БД
	if (file_exists('manaodb.xml'))
		$dbxml = simplexml_load_file('manaodb.xml');
	else {			// Если XML файл БД не найден -  - возврат с сообщением (код возврата 0)
		$req['msg'] =  "XML Data base error";
		echo json_encode($req);
		exit;
	}
	
	// Проверить, есть в БД пользователь с указанным логин и паролем
	$user_name = "";
	foreach ($dbxml->users->row as $row) {
		if((string)$row->login == $login && (string)$row->password == md5($password)) {
			$user_name = (string)$row->user_name;	// пользователь найден
			break;
		}
	}
	
	// Если пользователь в БД найден
	if ($user_name != "") {
		// Сохранить данные авторизации в cookie, для возможности автоматической авторизации
		setcookie("UserLastLogin",$login.":".$user_name.":".md5($password),time()+60*60*24);
		//  Сохранить данные пользователя в Сессии, для возможности использования из других страниц
		$_SESSION['userlogin'] = $login;
		$_SESSION['username'] = $user_name;
		// Успешный код возврата и сообщение
		$req['retcode'] = 1;
		$req['msg'] = "Hello, ".$user_name."!";
		// Передача результата в форму в формате JSON
		echo json_encode($req);
	} else {
		$req['msg'] = "This user is not exist";	// пользователь в БД не найден
		echo json_encode($req);
	}

?>