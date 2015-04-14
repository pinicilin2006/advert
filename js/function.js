function onlyDigits(input) {//разрешаем воода только цифр и точки
	var value = input.value; 
    var rep = /[-\,;":'a-zA-Zа-яА-Я]/; 
    if (rep.test(value)) { 
        value = value.replace(rep, ''); 
        input.value = value; 
    } 
}

function validateDateBirth_1(){
		var date_birth = $("#date_birth").val();
		var date = new Date(date_birth.replace(/(\d+).(\d+).(\d+)/, '$2/$1/$3'));
		//alert(date);	
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		var t = new Date();
		var a = ( t.getFullYear() - y - ((t.getMonth() - --m||t.getDate() - d)<0) );
		if(a < 18){
			//alert("Минимально допустимый возраст 18 лет");
			$("#date_birth_message_1").html("Минимально допустимый возраст 18 лет!");
			$("#date_birth").val('');
			$("#date_birth_message_1").focus();
		}else {
			$("#date_birth_message_1").html(" ");
		}
}

function add_user(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/user_add.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}
function user_data(a){
	$.ajax({
		type: "GET",
		url: '/ajax/user_data.php',
		data: 'user_id='+a,
		dataType: 'json',
		success: function(data) {		  
		    $('#first_name').val(data.first_name);
		    $('#second_name').val(data.second_name);
		    $('#third_name').val(data.third_name);
		    $('#login').val(data.login);
		    jQuery.each(data.rights, function(i, val) {
		      	 alert(i);
		    });
		}

	});
}
function button_return(){
	$('#user_data').slideDown();
	$('#message_result').html('');
}
