$(document).ready(function () {
	contentBox = $('#content-box');
	message = $('#message');
	contentBox.scrollTop(contentBox[0].scrollHeight);
	// click send button
	$('#send').click(function () {
		sendMessage();
	});

	// press enter 
	$('#message').pressEnter(function () {
		sendMessage();
	});

	$('div.m input[type=text]').pressEnter(function () {
		editMessage($(this));
		// alert('xxx');
	});

	$(document).on('pressEnter', 'div.m input[type=text]', function () {
		// editMessage($(this));
		alert('xxxx');
	});

	// press edit
	$(document).on('click', '.edit-message', function (event) {
		event.preventDefault();
		console.log('edit message');
		var m = $(this).parent().parent(),
			input = m.find('input[type=text]').first();
		input.removeAttr('readonly').addClass('active');
	});

	// press edit
	$(document).on('click', '.delete-message', function (event) {
		event.preventDefault();
		deleteMessage($(this));
	});

	//reload
	setInterval(function() {
		$.ajax({
			type: "GET",
			url: "message.php",
			dataType: "JSON",
			data: { 
				action: "reload",
				lastUpdate: contentBox.data('lastupdate'),
			},
		}).done(function( data ) {
			// console.log('last update: ' + data.lastUpdate);
			// console.log(data);
			if (data.hasUpdate) {
				contentBox.data('lastupdate', data.lastUpdate);
				// new message
				newMessage = data.newMessage;
				for(var i=0; i < newMessage.length; i++) {
					var m = newMessage[i];
					contentBox.append('<div class="m" data-id="' + m.message_id + '">' +
						'<p><span>' + m.username + '</span>: <input type="text" value="' + m.content + '" readonly="readonly"></p>'+
						'<p class="meta">'+
							'<a href="#" class="edit-message">[EDIT]</a> <a href="#" class="delete-message">[DELETE]</a> 12:5:30 pm, 17/12/2013'+
						'</p>'+
						'<div class="clear"></div>'+
					'</div>');
				}
				$('div.m input[type=text]').pressEnter(function () {
					editMessage($(this));
					// alert('xxx');
				});

				// deleted message
				deletedMessage = data.deletedMessage;
				for(var i = 0; i <deletedMessage.length; i++) {
					$('div.m[data-id=' + deletedMessage[i] + ']').remove();
				}

				// edited message
				editedMessage = data.editedMessage;
				// console.log(editedMessage);
				for(var i = 0; i < editedMessage.length; i++) {
					var m = editedMessage[i];
					// console.log(m);
					$('div.m[data-id=' + m.id + ']').find('input[type=text]').first().val(m.content);
				}
			}
			contentBox.scrollTop(contentBox[0].scrollHeight);
		}).fail(function( jqXHR, textStatus ) {
			console.log("Request failed: " + textStatus );
		});
	}, 1000);
});

function sendMessage (aEdit) {
	// alert('send message');
	$.ajax({
		type: "GET",
		url: "message.php",
		data: { 
			action: "add",
			content: message.val(),
		},
	}).done(function( data ) {
		// console.log(data);
		message.val('');
	});
}

function deleteMessage(aDelete) {
	// alert('delete message');
	// console.log('delete message');
	var messageId = aDelete.parent().parent().data('id');
	// console.log('id: ' + messageId);
	$.ajax({
		type: "GET",
		url: "message.php",
		dataType: "JSON",
		data: { 
			action: "delete",
			id: messageId,
		},
	}).done(function( data ) {
		// console.log(data);
	}).fail(function( jqXHR, textStatus ) {
		console.log("Request failed: " + textStatus );
	});
}

function editMessage(inputEdit) {
	// console.log('save content: ' + inputEdit.val());
	var m = inputEdit.parent().parent(),
		messageId = m.data('id');
	$.ajax({
		type: "GET",
		url: "message.php",
		dataType: "JSON",
		data: { 
			action: "edit",
			id: messageId,
			content: inputEdit.val(),
		},
	}).done(function( data ) {
		// console.log(data);
		inputEdit.attr('readonly', 'readonly');
		inputEdit.removeClass('active');
	}).fail(function( jqXHR, textStatus ) {
		console.log("Request failed: " + textStatus );
	});
}

$.fn.pressEnter = function (fn) {
	return this.each(function() {  
        $(this).bind('enterPress', fn);
        $(this).keyup(function(e){
            if (e.keyCode == 13) {
              $(this).trigger("enterPress");
            }
        })
    });  
};