function onlyDigits(input) {//разрешаем воода только цифр и точки
	var value = input.value; 
    var rep = /[-\,;":'a-zA-Zа-яА-Я]/; 
    if (rep.test(value)) { 
        value = value.replace(rep, ''); 
        input.value = value; 
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

function edit_user(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/user_edit.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function add_item(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/item_add.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function edit_item(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/item_edit.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function add_channel(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/channel_add.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function edit_channel(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/channel_edit.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function price(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/price_word.php',
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
		    $('#date_birth').val(data.date_birth);
		    $(".rights").prop("checked", false);
		    jQuery.each(data.rights, function(i, val) {
		      	$("#right_"+i).prop("checked", true);
		    });
		    if(data.active == 1){
		    	$("#active").prop("checked", true);
		    }else{
		    	$("#active").prop("checked", false);
		    }
		}

	});
}

function item_data(){
	var a = $('#item_id').val();
	$('#item').val($( "#item_id option:selected" ).text());
	$.ajax({
		type: "GET",
		url: '/ajax/item_data.php',
		data: 'item_id='+a,
		success: function(data) {	  
		    if(data == '1'){
		    	$("#active").prop("checked", true);
		    }else{
		    	$("#active").prop("checked", false);
		    }
		}
	});
}

function channel_data(){
	var a = $('#channel_id').val();
	$('#channel').val($( "#channel_id option:selected" ).text());
	$.ajax({
		type: "GET",
		url: '/ajax/channel_data.php',
		data: 'channel_id='+a,
		success: function(data) {	  
		    if(data == '1'){
		    	$("#active").prop("checked", true);
		    }else{
		    	$("#active").prop("checked", false);
		    }
		}
	});
}

function button_return(){
	$('#user_data').slideDown();
	$('#message_result').html('');
}
