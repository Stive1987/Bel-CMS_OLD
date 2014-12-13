jQuery(function($) {
	tooltips();
	$('#formSendComment').submit(function(event) {
		event.preventDefault();
		bel_cms_send_return_ajax($(this));
	});
	if (typeof tinymce !== 'undefined') {
		tinymce.init({
			selector: "textarea.editor",
			schema: "html5",
			language : "fr_FR",
			menubar : false,
			statusbar : false,
			toolbar: true,
			invalid_elements : "script, video, php, html",
			plugins: "image insertdatetime emoticons link fullscreen preview visualblocks",
			link_list: [
				{title: 'BEL-CMS', value: 'http://www.bel-cms.be'},
			],
			toolbar: "emoticons | undo redo | styleselect | bold italic | link image | insertdatetime | fullscreen | preview",
			insertdatetime_formats: ["%d.%m.%Y %H:%M", "%Y.%m.%d", "%H:%M"],
			entity_encoding : "raw",
		});

		tinymce.init({
			selector: "textarea.editor_light",
			schema: "html5",
			skin : 'bel-cms',
			language : "fr_FR",
			height : 50,
			menubar : false,
			toolbar: true,
			statusbar : false,
			plugins: "emoticons code textcolor link",
			invalid_elements : "script, video, php, html",
			toolbar: "emoticons | bold | italic | code | link",
			entity_encoding : "raw"
		});
	}
});

function bel_cms_send_return_ajax (objet) {
	if (typeof tinymce !== 'undefined') {
		tinyMCE.triggerSave();
	}
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

   // -> Initialize tooltips
function tooltips() {
	$('.tipN').tipsy({gravity: 'n',fade: true, html:true});
	$('.tipS').tipsy({gravity: 's',fade: true, html:true});
	$('.tipW').tipsy({gravity: 'w',fade: true, html:true});
	$('.tipE').tipsy({gravity: 'e',fade: true, html:true});

	$('.tipNw').tipsy({gravity: 'nw',fade: true, html:true});
	$('.tipNe').tipsy({gravity: 'ne',fade: true, html:true});
	$('.tipSw').tipsy({gravity: 'sw',fade: true, html:true});
	$('.tipSe').tipsy({gravity: 'se',fade: true, html:true});
}
// -> Initialize tooltips tipsy
(function(e){function t(e){if(e.attr("title")||typeof e.attr("original-title")!="string"){e.attr("original-title",e.attr("title")||"").removeAttr("title")}}function n(n,r){this.$element=e(n);this.options=r;this.enabled=true;t(this.$element)}n.prototype={show:function(){var t=this.getTitle();if(t&&this.enabled){var n=this.tip();n.find(".tipsy-inner")[this.options.html?"html":"text"](t);n[0].className="tipsy";n.remove().css({top:0,left:0,visibility:"hidden",display:"block"}).appendTo(document.body);var r=e.extend({},this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight});var i=n[0].offsetWidth,s=n[0].offsetHeight;var o=typeof this.options.gravity=="function"?this.options.gravity.call(this.$element[0]):this.options.gravity;var u;switch(o.charAt(0)){case"n":u={top:r.top+r.height+this.options.offset,left:r.left+r.width/2-i/2};break;case"s":u={top:r.top-s-this.options.offset,left:r.left+r.width/2-i/2};break;case"e":u={top:r.top+r.height/2-s/2,left:r.left-i-this.options.offset};break;case"w":u={top:r.top+r.height/2-s/2,left:r.left+r.width+this.options.offset};break}if(o.length==2){if(o.charAt(1)=="w"){u.left=r.left+r.width/2-15}else{u.left=r.left+r.width/2-i+15}}n.css(u).addClass("tipsy-"+o);if(this.options.fade){n.stop().css({opacity:0,display:"block",visibility:"visible"}).animate({opacity:this.options.opacity})}else{n.css({visibility:"visible",opacity:this.options.opacity})}}},hide:function(){if(this.options.fade){this.tip().stop().fadeOut(function(){e(this).remove()})}else{this.tip().remove()}},getTitle:function(){var e,n=this.$element,r=this.options;t(n);var e,r=this.options;if(typeof r.title=="string"){e=n.attr(r.title=="title"?"original-title":r.title)}else if(typeof r.title=="function"){e=r.title.call(n[0])}e=(""+e).replace(/(^\s*|\s*$)/,"");return e||r.fallback},tip:function(){if(!this.$tip){this.$tip=e('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"/></div>')}return this.$tip},validate:function(){if(!this.$element[0].parentNode){this.hide();this.$element=null;this.options=null}},enable:function(){this.enabled=true},disable:function(){this.enabled=false},toggleEnabled:function(){this.enabled=!this.enabled}};e.fn.tipsy=function(t){function r(r){var i=e.data(r,"tipsy");if(!i){i=new n(r,e.fn.tipsy.elementOptions(r,t));e.data(r,"tipsy",i)}return i}function i(){var e=r(this);e.hoverState="in";if(t.delayIn==0){e.show()}else{setTimeout(function(){if(e.hoverState=="in")e.show()},t.delayIn)}}function s(){var e=r(this);e.hoverState="out";if(t.delayOut==0){e.hide()}else{setTimeout(function(){if(e.hoverState=="out")e.hide()},t.delayOut)}}if(t===true){return this.data("tipsy")}else if(typeof t=="string"){return this.data("tipsy")[t]()}t=e.extend({},e.fn.tipsy.defaults,t);if(!t.live)this.each(function(){r(this)});if(t.trigger!="manual"){var o=t.live?"live":"bind",u=t.trigger=="hover"?"mouseenter":"focus",a=t.trigger=="hover"?"mouseleave":"blur";this[o](u,i)[o](a,s)}return this};e.fn.tipsy.defaults={delayIn:0,delayOut:0,fade:false,fallback:"",gravity:"n",html:false,live:false,offset:0,opacity:.8,title:"title",trigger:"hover"};e.fn.tipsy.elementOptions=function(t,n){return e.metadata?e.extend({},n,e(t).metadata()):n};e.fn.tipsy.autoNS=function(){return e(this).offset().top>e(document).scrollTop()+e(window).height()/2?"s":"n"};e.fn.tipsy.autoWE=function(){return e(this).offset().left>e(document).scrollLeft()+e(window).width()/2?"e":"w"}})(jQuery);

