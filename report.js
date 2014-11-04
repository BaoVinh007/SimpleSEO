
function toggleSigns(node) {
	if (node.hasClass('advice_open')) {
		node.removeClass('advice_open');
		node.addClass('advice_close');
		node.text('Hide advice');
	}
	else {
		node.removeClass('advice_close');
		node.addClass('advice_open');
		node.text('Show advice');
	}
}

function addToggleAdviceEvents(type) {
	$('#section_'+type+' .advice_toggle').click(function() {
		toggleSigns($(this));
		$(this).parent().next().toggle();
		return false;
	});
	$('#section_'+type+' .criterion_value h4').css('cursor', 'pointer').click(function() {
		toggleSigns($(this).parent().find('.advice_toggle'));
		$(this).parent().next().toggle();
	});
}

var check_screenshot_ready_limit = 0;

$(document).ready(function() {
	
	$('.criterion_info_advice').hide();
	$('#view_text_content').hide();
	
	$('.report_section').each(function() {
		var type = $(this).attr('id').substr(8);
		addToggleAdviceEvents(type);
	});
	
	$('#view_text_link').click(function() {
		$('#view_text_content').show();
		$(this).parent().hide();
		return false;
	});
	
	$('.linkedin').click(function() {
		var score = $('#rank .value').text();
		var site = $('h2 a strong').text();
		var url = window.location.protocol+'//'+window.location.hostname+window.location.pathname;
		window.open('http://www.linkedin.com/shareArticle?mini=true&url='+url+'&title=WooRank of '+site+' is '+score+'!&source=woorank.com','sharer','toolbar=0,status=0,resizable=1,width=626,height=563');
		return false;
	});
	$('.facebook').click(function() {
		var url = window.location.protocol+'//'+window.location.hostname+window.location.pathname;
		window.open('http://www.facebook.com/sharer.php?u='+url,'sharer','toolbar=0,status=0,resizable=1,width=626,height=436');
		return false;
	});
	$('.twitter').click(function() {
		var score = $('#rank .value').text();
		var site = $('h2 a strong').text();
		var url = window.location.protocol+'//'+window.location.hostname+window.location.pathname;
		window.open('http://twitter.com/home?status=WooRank of '+site+' is '+score+'! '+url);
		return false;
	});
	
	$('.catch_email_overlay').click(function() {
		$(this).parent().hide();
	});
	$('.catch_email_content .close').click(function() {
		$(this).parent().parent().hide();
		return false;
	});
	
	var text = $('#catch_email_content button').text();
	$('#catch_email_content button').remove();
	$('#catch_email_content form div').append('<a href="#" class="submit"></a>');
	$('#catch_email_content a.submit').text(text).click(function() {
		$(this).parent().parent().submit();
		return false;
	});
	
	text = $('#catch_email_pdf_content button').text();
	$('#catch_email_pdf_content button').remove();
	$('#catch_email_pdf_content form div').append('<a href="#" class="submit"></a>');
	$('#catch_email_pdf_content a.submit').text(text).click(function() {
		$(this).parent().parent().submit();
		return false;
	});
	
	$('.premium').click(function() {
		$('#catch_email').show();
		$('#catch_email_content input.text').focus();
		return false;
	});
	

	$('.criterion .criterion_value h4').mouseover(function() {
		$(this).css('color', '#2D7BA5');
	});
	$('.criterion .criterion_value h4').mouseout(function() {
		$(this).css('color', '#666666');
	});

        $('ul#portion_headings_list > li:gt(5)').hide();
        $('#portion_headings_list_less').hide();
        $('#portion_headings_list_more, #portion_headings_list_less').click(function() {
            $('ul#portion_headings_list > li:gt(5)').fadeToggle(100);
            $('#portion_headings_list_less').toggle();
            $('#portion_headings_list_more').toggle();
            return false;
        });
});