(function( $ ) {
	'use strict';
	
	$(document).ready(function(){
		let type = $('#cus_select_post_type');
		let clean_button = $("#cus_clean_shortcodes");
		type.select2();
		clean_button.click(function(){
			clean_button.addClass('cus_loading');
			clean_button.attr( 'disabled', true );
			if ( type.val() == '' ) {
				clean_button.attr( 'disabled', false );
				clean_button.removeClass('cus_loading');
				$('#cus_feedback_modal p').html('Please select at least one post type.');
				tb_show( 'ERROR', "#TB_inline?width=300&inlineId=cus_feedback_modal");
				return;
			}
			jQuery.ajax({
				method: "POST",
				url: cus_ajax_object.admin_ajax,
				data: { 
					action: "cus_clean_shortcodes",
					nonce: cus_ajax_object.cus_ajax_nonce,
					types: type.val(), 
				},
				success: function (response) {
					if (response.success) {
						clean_button.attr( 'disabled', false );
						clean_button.removeClass('cus_loading');
						$('#cus_feedback_modal p').html(response.data);
						tb_show( 'SUCCESS', "#TB_inline?width=300&inlineId=cus_feedback_modal");
					} else {
						clean_button.attr( 'disabled', false );
						clean_button.removeClass('cus_loading');
						$('#cus_feedback_modal p').html(response.data);
						tb_show( 'ERROR', "#TB_inline?width=300&inlineId=cus_feedback_modal");
					}
				},
				error: function () {
					clean_button.attr( 'disabled', false );
					clean_button.removeClass('cus_loading');
					$('#cus_feedback_modal p').html('Something went wrong');
					tb_show( 'ERROR', "#TB_inline?width=300&inlineId=cus_feedback_modal");
				}
			});
		});
	});
})( jQuery );
