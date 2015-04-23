
//Фокус на первом поле
$("#item").focus();
//Автозаполнение ФИО и телефона
  $('input#client_name').autocomplete({
    source: '/ajax/client_autocomplete.php', // Страница для обработки запросов автозаполнения
    minLength: 2, // Минимальная длина запроса для срабатывания автозаполнения
    //autoFocus: true,
    //selectFirst: true,
    select: function(event, ui) {
	    	//alert(ui.item.value);
	    	var a = ui.item.phone;
	    	$("#client_phone").val(a);
	    	$("#ad").focus();
	    	//return false;
	    }
  });
//Подсчёт слов
$(document).on("keyup", ".text_advert", function(){
	num_words();
	calc();
});
//Подсчёт стоимости
$(document).on("change keyup", ".calc", function(){
	calc();
});
//Включаем/отключаем кнопку сохранения
$("#paid").bind("change click", function () {
    if($(this).prop("checked")){
    	$("#save_button").prop("disabled", false);
    } else {
    	$("#save_button").prop("disabled", true);
    }

});