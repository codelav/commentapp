(function ($) {
	$('a.reply').click(function() {
		$('#comment-parent_id').val($(this).data('id'));
		$('#comment-reply_to').html('Reply to <b>' + $(this).data('name') + '</b>');
		$("html, body").animate({ scrollTop: $(document).height() }, 1000);
		return false;
	});

	$('button.reply-cancel').click(function() {
		$('#comment-reply_to').html('');
		$('#comment-parent_id').val(null);
	});

})(window.jQuery);