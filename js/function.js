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

function add_advert(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/ad_add.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function edit_advert(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/ad_edit.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function edit_advert2(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/ad_edit2.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function delete_advert(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/ad_delete.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function add_discount(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/discount_add.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function edit_discount(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/discount_edit.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

function edit_speed(){
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/speed_edit.php',
			  data: a,
			  success: function(data) {
			  	$("#user_data").slideUp(400);
			  	$("#message_result").html(data);
			  }
			});
			return false;
}

// function price(){
// 			var a = $("#main_form").serialize();
// 			$.ajax({
// 			  type: "POST",
// 			  url: '/ajax/price_word.php',
// 			  data: a,
// 			  success: function(data) {
// 			  	$("#user_data").slideUp(400);
// 			  	$("#message_result").html(data);
// 			  }
// 			});
// 			return false;
// }

function calc(){
			$("#price").val('');
			$("#price_day").val('');
			var a = $("#main_form").serialize();
			$.ajax({
			  type: "POST",
			  url: '/ajax/calc.php',
			  data: a,
			  dataType: 'json',
			  success: function(data) {
			  	if(data !=''){
			  		$("#price").val(data.summa);
			  		$("#price_day").val(data.price_day);
			  	}
			  }
			});
			return false;
}

function num_days(){
			var a = $("#released").val();
			var arrDays = a.split(',');
			var num = arrDays.length;
			$("#days").val(num);
			return false;
}

function num_words(){
			var a = $(".text_advert").val();
			var arrWords = a.split(' ');
			var i = 0;
			for(var k in arrWords){
				if(arrWords[k].length > 2){
					i++;
				}
			}
			$("#words").val(i);
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
		    $('#max_time').val(data.max_time);
		    $(".data_clear").prop("checked", false);
		    jQuery.each(data.rights, function(i, val) {
		      	$("#right_"+i).prop("checked", true);
		    });
		    jQuery.each(data.channels, function(i, val) {
		      	$("#channel_"+i).prop("checked", true);
		    });
		    jQuery.each(data.items, function(i, val) {
		      	$("#item_"+i).prop("checked", true);
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
		dataType: 'json',
		success: function(data) {	  
		    if(data.active == '1'){
		    	$("#active").prop("checked", true);
		    }else{
		    	$("#active").prop("checked", false);
		    }
			$("#price_word").val(data.price);
		}
	});
}

function discount_data(){
	var a = $('#discount_id').val();
	$('#name').val($( "#discount_id option:selected" ).text());
	$.ajax({
		type: "GET",
		url: '/ajax/discount_data.php',
		data: 'discount_id='+a,
		dataType: 'json',
		success: function(data) {	  
		    if(data.active == '1'){
		    	$("#active").prop("checked", true);
		    }else{
		    	$("#active").prop("checked", false);
		    }
			$("#percent").val(data.percent);
		}
	});
}

function button_return(){
	$('#user_data').slideDown();
	$('#message_result').html('');
}
