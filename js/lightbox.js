function lightbox_service(serviceId) {
	$('#serviceForm')[0].reset();
	$('#lb_bak_service, #lb_box_service').animate({'opacity':'.8'}, 150, 'linear');
	$('#lb_box_service').animate({'opacity':'1.00'}, 150, 'linear');
	$('#lb_bak_service, #lb_box_service').css('display', 'block');
	if(serviceId) {
		loadServiceDetail(serviceId);
	}
}

function lightbox_application(applicationId) {
	$('#applicationForm')[0].reset();
    $('#lb_bak_application, #lb_box_application').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_application').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_application, #lb_box_application').css('display', 'block');
	if(applicationId) {
		loadApplicationDetail(applicationId);
	}
}

function lightbox_systemUser(systemUserId) {
	$('#systemUserForm')[0].reset();
    $('#lb_bak_systemUser, #lb_box_systemUser').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_systemUser').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_systemUser, #lb_box_systemUser').css('display', 'block');
	if(systemUserId) {
		loadSystemUserDetail(systemUserId);
	}
}

function lightbox_user(userId) {
    $('#lb_bak_user, #lb_box_user').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_user').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_user, #lb_box_user').css('display', 'block');
}
function lightbox_userRole(userId) {
    $('#lb_bak_userRole, #lb_box_userRole').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_userRole').animate({'opacity':'1.00'}, 150, 'linear'); 
    $('#lb_bak_userRole, #lb_box_userRole').css('display', 'block');
	$('#userId').val(userId);
}
function lightbox_delete(url) {
    $('#lb_bak_delete, #lb_box_delete').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_delete').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_delete, #lb_box_delete').css('display', 'block');
	$('#url_delete').attr('href',url);
}
function lightbox_configFile(url) {
    $('#lb_bak_configFile, #lb_box_configFile').animate({'opacity':'.8'}, 150, 'linear');
    $('#lb_box_configFile').animate({'opacity':'1.00'}, 150, 'linear');
    $('#lb_bak_configFile, #lb_box_configFile').css('display', 'block');
}
$(document).ready(function(){
	$('.lb_close').click(function(){
		$('.lb_bak, .lb_box').animate({'opacity':'0'}, 0, 'linear', function(){$('.lb_bak, .lb_box').css('display', 'none');});
	});
	$('.lb_bak').click(function(){
		$('.lb_bak, .lb_box').animate({'opacity':'0'}, 0, 'linear', function(){$('.lb_bak, .lb_box').css('display', 'none');});
	});
});

function lightbox_close() {
	$('.lb_bak, .lb_box').animate({'opacity':'0'}, 0, 'linear', function(){$('.lb_bak, .lb_box').css('display', 'none');});
}
function drop_down() {
	var config = {
		'.chzn-select' : {},
	}
	for (var selector in config) {
		$(selector).chosen(config[selector]);
	}
}	
