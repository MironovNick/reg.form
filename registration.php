<?php
	session_start();		// Старт сессии
	
	$req = array("retcode" => 0, "msg"=> "");		// возвращаемый форме об'ект
	
	// получитьь данные с формы и "распарсить" их в массив $data
	$postData = file_get_contents('php://input');
	$data = json_decode($postData, true);

	// Если получены не все данные с формы - возврат с сообщением (код возврата 0)
	if(  !isset($data['login']) || !isset($data['password']) || !isset($data['confirm_password']) || !isset($data['email']) || !isset($data['username'])){	 
		$req['msg'] =  "One or more poles are not correct";
		echo json_encode($req);
		exit;
	}
	
	// Проверка логин по формату
	$login = $data['login'];
	if(!preg_match("/[a-zA-Z0-9]/", $login)){
		$req['msg'] = "Use only a-z & 0-9";
		echo json_encode($req);
		exit;
	}
	
	// пароль введен одинаково оба раза?
	$password = $data['password'];
	$confirm_password = $data['confirm_password'];
	if($password === "" || $password !== $confirm_password){
		$req['msg'] = "Invalid password!";
		echo json_encode($req);
		exit;
	}

	// Проверка логин по формату
	$email = $data['email'];
	if(!preg_match("/[a-zA-Z0-9_]{3,33}@[a-zA-Z0-9]{2,33}\.[a-zA-Z\.]{2,33}/", $email)){
		$req['msg'] = "Invalid e-mail";
		echo json_encode($req);
		exit;
	}
	
// Проверка имени пользователя по формату	
	$username = $data['username'];
	if(!preg_match("/[a-zA-Z]/", $username)){
		$req['msg'] = "Use only a-z";
		echo json_encode($req);
		exit;		
	}	
	
	// Если с формы получено хоть одно пустое поле - возврат с сообщением (код возврата 0)
	if ($login === "" || $password === "" || $confirm_password === "" || $email === "" || $username === ""){
		$req['msg'] = "One or more poles are empty";
		echo json_encode($req);
		exit;
	}
	
	// Файл БД существует?
	if (!file_exists ('manaodb.xml')){
		$req['msg'] = "XML file error";
		echo json_encode($req);
		exit;
	}
	
	// Прочитать файл БД 
	$dbxml = simplexml_load_file ('manaodb.xml'); 
	
	// Проверить на уникальность логин и email в БД
	foreach($dbxml->users->row as $row){
		if((string)$row->login == $login || (string)$row->e_mail == $email) {
			$req['msg'] =  "Login or e-mail is not unique";
			echo json_encode($req);
			exit;
		}
	}
	
	// Добавить нового пользователя
	$row = $dbxml->users->addChild('row');
	$row->addChild('login', $login);
	$row->addChild('password', md5($password));
	$row->addChild('e_mail', $email);
	$row->addChild('user_name', $username);
	
	// Сохранить данные о новом пользователе в файл
	if ($dbxml->asXML('manaodb.xml')){
	// Сохранить данные авторизации в cookie, для возможности автоматической авторизации
		setcookie("UserLastLogin",$login.":".$username.":".md5($password),time()+60*60*24);
	//  Сохранить данные пользователя в Сессии, для возможности использования из других страниц
		$_SESSION['userlogin'] = $login;
		$_SESSION['username'] = $username;
		$req['retcode'] = 1;	// Успешный код возврата и сообщение
		$req['msg'] = "Hello, ".$username."!";
		echo json_encode($req);
	} else {
		$req['msg'] = "XML file write error";	// Не удалось записать данные о новом пользователе в файл
		echo json_encode($req);
	}
	
?>









