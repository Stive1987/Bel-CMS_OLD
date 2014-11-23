jQuery(function($) {
	$('#formSendComment').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});
});
function bel_cms_send_return_ajax (objet) {
	/* Get Url */
	if (objet.attr('href')) {
		var url = objet.attr('href');
	} else if (objet.attr('action')) {
		var url = objet.attr('action');
	} else if (objet.data('url')) {
		var url = objet.data('url');
	} else {
		alert('No link sets');
	}
	if ($(objet).is('form')) {
		var dataValue  = $(objet).serialize();
	} else {
		var dataValue  = '';
	}

    if ($('.bel_cms_msg').height()) {
        $('.bel_cms_msg').remove();
    }

	$.ajax({
		type: 'POST',
		url: url,
		data: dataValue,
		success: function(data) {
			var data = $.parseJSON(data);

			if (data.linkReturn == undefined) {
				var returnLink = false;
			} else {
				var returnLink = true;
			}

			if (data.type == undefined) {
				var type = 'blue';
			} else {
				var type = data.type;
			}

			if (returnLink) {
				setTimeout(function() {
					document.location.href=data.linkReturn;
				}, 3250);
			}

			$('.bel_cms_msg').addClass(type).empty().append(data.text);

		},
		error: function() {
			alert('Error function ajax');
		},
		beforeSend:function() {
		    $('body').append('<div class="bel_cms_msg">loading...</div>');
		    $('.bel_cms_msg').animate({ bottom: 0 }, 300);
		    /* Remove effect background
		    $('body').append('<div id="bel_cms_overlay"></div>').addClass('effectGray');
		    $('#bel_cms_overlay').fadeIn(350);
		    */
		},
		complete: function() {
			$('textarea').val('');
			endAjax(1);
		}
	});
}
function endAjax (time) {
	parseInt(time);
	if (time <= 3) {
        setTimeout(function() {
        	endAjax(time+1);
        }, 1000);
	} else {
		/* Remove effect background
        $("#bel_cms_overlay").fadeOut(350, function() {
        $(this).remove();
        })
		*/
        $('.bel_cms_msg').animate({ bottom: '-60px' }, 300, function() {
            $(this).remove();
        });
        $('body').removeClass('effectGray');
	}
}
