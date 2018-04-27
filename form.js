
// Функция работы с сервером PHP формы авторизации (AJAX)
function check_log(){
	var req = new XMLHttpRequest();
	// Передаваемый на PHP об'ект
	var snd_obj = 	{	login: document.getElementById("login").value,
						password: document.getElementById("password").value
					};
	req.open("POST", "autorisation.php", true); // метод POST
	req.onreadystatechange = function(){	// обработчик принимаемых с PHP данных
        if (req.readyState == 4 && req.status == 200){
				var resp = JSON.parse(req.responseText); // получаемые данные в формате JSON, преобразуем в об'ект
				if(resp){
					if(resp.retcode == 1){	// приняли успешный код завершения
						document.location.href = "index.php";		// переход на главную страницу
					} else
					document.getElementById("alert").innerHTML=resp.msg;	// сообщение об ошибке
				} else
					document.getElementById("alert").innerHTML= "Mesage not responced by JSON"; 	// сообщение об ошибке
			}
        }
	req.setRequestHeader("Content-Type", "application/json");
	req.send(JSON.stringify(snd_obj));	// отправить данные на PHP в формате JSON
}

function reg_func(){
	var req = new XMLHttpRequest();
	// Передаваемый на PHP об'ект
	var snd_obj = 	{	login: document.getElementById("login").value,
						password: document.getElementById("password").value,
						confirm_password: document.getElementById("confirm_password").value,
						email: document.getElementById("email").value,
						username: document.getElementById("username").value
					};
	req.open("POST", "registration.php", true);	 // метод POST
	req.onreadystatechange = function(){		// обработчик принимаемых с PHP данных
		if (req.readyState == 4 && req.status == 200){
				var resp = JSON.parse(req.responseText);	 // получаемые данные в формате JSON, преобразуем в об'ект
				if(resp){
					if(resp.retcode == 1){	// приняли успешный код завершения
						document.location.href = "index.php";			// переход на главную страницу
					} else
					document.getElementById("alert").innerHTML=resp.msg;		// сообщение об ошибке
				} else
					document.getElementById("alert").innerHTML= "Mesage not responced by JSON";		// сообщение об ошибке
			}
        }
		req.setRequestHeader("Content-Type", "application/json");
		req.send(JSON.stringify(snd_obj));		// отправить данные на PHP в формате JSON
}








