jQuery(function($) {
	allFormUser ();
	changePage ();
	changeAvatar ();
	addAvatar ();
	delAvatar ();
});

function allFormUser () {

	$('#bel_cms_logout').click(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});

	$('#formSendLogin').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});

	$('#formSendAccount').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});

	$('#formSendConfig').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});

	$('#formSendLostPassword').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});

	$('#formSendRegistration').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});

	$('#lostPassword, #registration').click(function(event) {
		event.preventDefault();
		getPageAjax($(this).attr('href'));
	});
}

function delAvatar () {
	$('.user_del_avatar').click(function(event) {
		if ($('.bel_cms_msg').height()) {
			$('.bel_cms_msg').remove();
		}
		event.preventDefault();
		var href = $(this).attr('href').replace('#', '');
		var type = $(this).data('type');
		var url  = 'User/send/ajax';
		var data = 'type=delavatar&href='+href+'&file='+type;
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(data) {
				var data = $.parseJSON(data);
				$('.bel_cms_msg').addClass(data.type).empty().append(data.text);
			},
			beforeSend:function() {
				$('body').append('<div class="bel_cms_msg">loading...</div>');
				$('.bel_cms_msg').animate({ bottom: 0 }, 300);
			},
			complete: function() {
				endAjax(1);
			}
		});
	});
}

function addAvatar () {
	if ($('.bel_cms_msg').height()) {
		$('.bel_cms_msg').remove();
	}
	var fileInput = document.querySelector('#file_avatar'),
		progress  = document.querySelector('#progress'),
		post      = $('form#formSendAvatar').attr('action');

	fileInput.addEventListener('change', function() {
		var xhr = new XMLHttpRequest(),
		    ext = fileInput.files[0].name.split('.').pop().toLowerCase();
		if ($.inArray(ext,['gif','png','jpg','jpeg']) == -1) {
		    alert("Désolé, l'extension du fichier " + fileInput.files[0].name + " n'est pas valide");
		} else {
			$('body').append('<div class="bel_cms_msg">Upload in progress...</div>');
			$('.bel_cms_msg').animate({ bottom: 0 }, 300);
			xhr.open('POST', post);

     		xhr.upload.addEventListener('progress', changeBackgroudColorUpload, false);  

			xhr.upload.addEventListener('error', uploadFailed, false);			

			xhr.addEventListener('load', function(e) {
				var data = $.parseJSON(xhr.responseText);
				$('.bel_cms_msg').addClass(data.type).empty().append(data.text);
				endAjax(1);
				$('#progress-bar').animate({ 'width' : 0+'%' }, 3000);
			}, false);
			var form = new FormData();
			form.append('avatar', fileInput.files[0]);
			xhr.send(form);
		}
	}, false);
}
function uploadFailed () {
	$('.bel_cms_msg').addClass('red').empty().append('Upload Failed');
	endAjax(1);
}

function changeBackgroudColorUpload(e) 
{
	var loaded = Math.round((e.loaded / e.total) * 100);
	if (loaded <= 5) {
		color = '#f63a0f';
	} else if (loaded <= 25) {
		color = '#f27011';
	} else if (loaded <= 50) {
		color = '#f2b01e';
	} else if (loaded <= 50) {
		color = '#f2b01e';
	} else if (loaded <= 50) {
		color = '#f2d31b';
	} else {
		color = '#86e01e';
	}
	$('#progress-bar').css({ 'width' : loaded+'%' }).css("background-color", color);
}

function changeAvatar () {
	$('.select_avatar').click(function(event) {
		if ($('.bel_cms_msg').height()) {
			$('.bel_cms_msg').remove();
		}
		event.preventDefault();
		var href = $(this).attr('href');
		var url  = 'User/send/ajax';
		var data = 'type=avatar&href='+href;
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(data) {
				var data = $.parseJSON(data);
				$('.bel_cms_msg').addClass('green').empty().append(data.text);
			},
			beforeSend:function() {
				$('body').append('<div class="bel_cms_msg">loading...</div>');
				$('.bel_cms_msg').animate({ bottom: 0 }, 300);
			},
			complete: function() {
				$('#bel_cms_user_avatar > img').attr('src', href);
				endAjax(1);
			}
		});
	});
}

function changePage() {
	$('a.user_change_page').click(function(event) {
		event.preventDefault();
		var id = $(this).attr('href').replace('#', '');
		$('#bel_cms_user_right_content > section').animate({
			opacity: 0,
		},
		250, function () {
			$('#bel_cms_user_right_content > section').removeClass('active');
			$('#bel_cms_user_right_content > section#' + id).animate({
				opacity: 1,
			},
			250).addClass('active');
		});
	});
}

function getPageAjax (url) {
	$.ajax({
		type: 'GET',
		url: url,
		success: function(html) {
			$('#bel_cms_user_login').empty().append(html);
		},
		error: function() {
			alert('Error function ajax');
		},
		beforeSend:function() {
			$('body').addClass('effectGray');
		},
		complete: function() {
			$('body').removeClass('effectGray');
			allFormUser ();
		}
	});
}