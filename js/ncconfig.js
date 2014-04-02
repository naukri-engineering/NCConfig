function loadServiceDetail(serviceId) {
    var DOMAIN = $('#DOMAIN').val();
    $('#loading').css("display", "block");
    $.ajax({
        type: "POST",url: DOMAIN+'service/serviceDetail',data:"serviceId="+serviceId,async:false,
        success:function(data){
            response = data;
            var returnedData = JSON.parse(response);
            $('#serviceId').val(returnedData.serviceId);
            $('#serviceTypeId').val(returnedData.serviceTypeId);
            $('#servicePort').val(returnedData.servicePort);
            $('#serviceDNS').val(returnedData.serviceDNS);
            $('#serviceDNS2').val(returnedData.serviceDNS2);
            $('#serviceDNS3').val(returnedData.serviceDNS3);
            $('#serviceRefName').val(returnedData.serviceRefName);

            $('#serviceTypeId_old').val(returnedData.serviceTypeId);
            $('#servicePort_old').val(returnedData.servicePort);
            $('#serviceDNS_old').val(returnedData.serviceDNS);
            $('#serviceDNS2_old').val(returnedData.serviceDNS2);
            $('#serviceDNS3_old').val(returnedData.serviceDNS3);
            $('#serviceRefName_old').val(returnedData.serviceRefName);
            $('#loading').css("display", "none");

			if(returnedData.serviceDNS2) {
				$('#rowDNS2').css("display", "block");
				$('#addmore2').css("display", "none");
			}
			if(returnedData.serviceDNS3) {
				$('#rowDNS3').css("display", "block");
				$('#addmore3').css("display", "none");
			}
        }
    });
}
function loadApplicationDetail(applicationId) {
	var DOMAIN = $('#DOMAIN').val();
	$('#loading_application').css("display", "block");
	$.ajax({
		type: "POST",url: DOMAIN+'application/applicationDetail',data:"applicationId="+applicationId,async:false,
		success:function(data){
			response = data;
			var returnedData = JSON.parse(response);
			$('#applicationId').val(returnedData.applicationId);
			$('#applicationGroupName').val(returnedData.applicationGroupName);
			$('#applicationName').val(returnedData.applicationName);
			$('#email').val(returnedData.email);

			$('#applicationName_old').val(returnedData.applicationName);
			$('#email_old').val(returnedData.email);
			$('#loading_application').css("display", "none");
		}
	});
}
function loadSystemUserDetail(systemUserId) {
    var DOMAIN = $('#DOMAIN').val();
    $('#loading_systemUser').css("display", "block");
    $.ajax({
        type: "POST",url: DOMAIN+'systemUser/systemUserDetail',data:"systemUserId="+systemUserId,async:false,
        success:function(data){
            response = data;
            var returnedData = JSON.parse(response);
            $('#systemUserId').val(returnedData.systemUserId);
            $('#systemUserRefName').val(returnedData.systemUserRefName);
            $('#username').val(returnedData.username);
            $('#password').val(returnedData.password);
            $('#systemUserRefName_old').val(returnedData.systemUserRefName);
            $('#loading_systemUser').css("display", "none");
        }
    });
}
function autoSuggestor(source,id) {
	var suggArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
	sugg = $.map(source, function (value) { return value; });
	$('#'+id).autocomplete({
		lookup: suggArray,
	});
}
function select_single_option(t) {
	var n=$('#'+t+'_options').val();
	$('#'+t).val(n);
}
function select_common_option() {
	var t;
	var n=$('#common_options').val();
	if(n!="--") {
		var r=n.split(" ");
		$('#minute').val(r[0]);
		$('#hour').val(r[1]);
		$('#day').val(r[2]);
		$('#month').val(r[3]);
		$('#weekday').val(r[4]);
	}
}
