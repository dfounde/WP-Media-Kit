//trigger on document ready
jQuery(document).ready(function($){	
	$('.magazine-list').sortable({
	//	axis: "x",
		cursor: "move",
		items: "> li",
		opacity: 0.5,
		placeholder: "sortable-placeholder",
		// handle: "img",
		// containment: ".magazine-list",
		update: function (event, ui) {
			$(".ui-sortable li > label").each(function(i, el){
				var monthindex = i+1;
                var monthName = moment.months(monthindex - 1);      // "September"
                var shortName = moment.monthsShort(monthindex - 1); // "Sep"
				var monthId = 'magazine_cover_month_'+monthindex;
				$(this).attr('for',monthId);
				$(this).html(monthName);
	        });
			$(".ui-sortable li input.custom_upload_image").each(function(i, el){
				var monthindex = i+1;
				var month = 'magazine_cover_month_'+monthindex+'[src]';
				$(this).attr('id',month);
				$(this).attr('name',month);
	        });
		}
	});

    function addMag() {
        var maglength = $('ul.magazine-list li').length + 1;
        $('#add_month_button').click(function(e) {
            if ( maglength <= 12 ) {
                var monthName = moment.months(maglength - 1);
                var monthId = 'magazine_cover_month_'+maglength;
                $('ul.magazine-list').append($('<li class="ui-sortable-handle">\
                    <label for="'+monthId+'">'+monthName+'</label>\
                    <img class="'+monthId+'_preview" src="" width="185" height="240">\
                    <input class="hidden custom_upload_image" type="text" name="'+monthId+'[src]" id="'+monthId+'[src]" value="" size="70"><br/>\
                    <input id="'+monthId+'_button" name="'+monthId+'_button" class="custom_upload_image_button button" type="button" value="Choose Image">\
                    </li>'));
                maglength++;
                monthListener();
            } else {
                alert('All Months Displayed');
            }
        });
    }
    addMag();
    
    function monthListener() {
    	$('[id^=magazine_cover_month_][id$=_button]').on('click',function(e) {
            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(this);
            var id = button.attr('id').replace('_button', '\\[src\\]');
            var preview = button.attr('id').replace('_button', '_preview');
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media ) {
                    $("#"+id).val(attachment.url);
                    var previewimage = $("#"+id).val();
                    $("."+preview).attr('src', previewimage);
                } else {
                    return _orig_send_attachment.apply( this, [props, attachment] );
                };
            }
            wp.media.editor.open(button);
            return false;
        });
    }
    monthListener();

    $('.add_media').on('click', function(){
        _custom_media = false;
    });

    $('#custom_meta_ad_leaderboard_remove, #custom_meta_ad_mrec_remove, .custom_meta_ad_mrec_remove, #custom_meta_author_logo_remove,#custom_meta_food_image_remove,#custom_meta_exec_magazine_cover_remove').click(function() {
        var button = $(this);
        var id = button.attr('id').replace('_remove', '');
        var preview = button.attr('id').replace('_remove', '_preview');
        $("#"+id).val('');
        $("."+preview).attr('src', '');
        return false;
    });
});