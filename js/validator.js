//http://jquery.bassistance.de/validate/demo/
$().ready(function() {
	$("#searchCronForm").validate({
        onkeyup: false,
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            'serverId[]': {
				required: function(element) {
					return $("#applicationId").val() == null;
				}
            },
            'applicationId[]': {
				required: function(element) {
					return $("#serverId").val() == null;
				}
				
            },
        },
        messages: {
            'serverId[]': {
                required: "Please choose Application or Server",
            },
            'applicationId[]': {
                required: "Please choose Application or Server",
            },
        }
	});
	$("#deployCronFrom").validate({
        onkeyup: false,
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            serverId: {
                required: true,
            },
			user: {
				required: true,
			},
        },
        messages: {
			serverId: {
                required: "Please choose Server",
            },
			user: {
				required: "Please enter the cron user",
			},
        }
	});
	/*
	$("#viewLogCronFrom").validate({
        onkeyup: false,
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            applicationId: {
                required: true,
            },
        },
        messages: {
            applicationId: {
                required: "Please choose Application",
            },
		}
	});
	*/
    $("#cronForm").validate({
        onkeyup: false,
		errorPlacement: function(error, element) {
			error.appendTo(element.parent());
		},
        rules: {
			applicationId: {
				required: true,
			},
            minute: {
                required: true,
            },
            hour: {
                required: true,
            },
            day: {
                required: true,
            },
            month: {
                required: true,
            },
            weekday: {
                required: true,
            },
            command: {
                required: true,
            },
            'serverId[]': {
                required: true,
            },
			user: {
				required: true,
			},
            /*completionTime: {
                required: true,
				min: 1
            },*/
            comment: {
                required: true,
            },
			fromEmail: {
				email: true,
			},
			maxConcurrency: {
				required: true,
				min: 1
			},
			timeAlert: {
				required: true,
				min: 1
			},
			toEmail: {
				required: true,
				multiemail: true,
			}
        },
        messages: {
			applicationId: {
				required: "Please choose Application",
			},
            minute: {
                required: "Please provide minute",
            },
            hour: {
                required: "Please provide hour",
            },
            day: {
                required: "Please provide day",
            },
            month: {
                required: "Please provide month",
            },
            weekday: {
                required: "Please provide weekday",
            },
            command: {
                required: "Please provide command",
            },
            'serverId[]': {
                required: "Please choose server",
            },
            /*completionTime: {
                required: "Please provide expected completion time",
				min: "Please enter a value greater than or equal to 1",
            },*/
			user: {
				required: "Please provide the cron user",
			},
            comment: {
                required: "Please provide comment",
            },
			fromEmail: {
				email: 'Please enter valid email',
			},
			maxConcurrency: {
				required: "Please provide the maximum concurrency",
				min: "Please enter a value greater than or equal to 1",
			},
			timeAlert: {
				required: "Please provide the time alert",
				min: "Please enter a value greater than or equal to 1",
			},
			toEmail: {
				required: "Please provide a to email(s)",
				multiemail: "You must enter a valid email, or comma separate multiple",
			}
        }
    });
	$("#changePasswordForm").validate({
		onkeyup: false,
		rules: {
			password: {
				required: true,
				minlength: 5
			},
			confirmPassword: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			}
		},
		messages: {
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			confirmPassword: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			}
		}
	});
	$("#configureApplicationForm").validate({
		onkeyup: false,
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
		rules: {
			applicationId: {
				required: true,
				validateConfigureApplication: true
			},
			'serverId[]': 'required',
			configPath: 'required',
			//configFile: {
			//	required: true,
			//	validateFormat: true
			//}
		},
		messages: {
			applicationId: {
				required: 'Please choose application',
				validateConfigureApplication: 'Application aleady configured'
			},
			'serverId[]': 'Please choose server(s)',
			configPath: 'Please enter config path',
			//configFile: {
			//	required: 'Please upload the configuration template file',
			//	validateFormat: 'Please enter valid file (Allowed yml,xml,php,txt)'
			//}
		}
	});
    $("#configFileForm").validate({
        onkeyup: false,
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            configFile: {
                required: true,
                validateFormat: true
            }
        },
        messages: {
            configFile: {
                required: 'Please upload the configuration template file',
                validateFormat: 'Please enter valid file (Allowed yml,xml,php,txt)'
            }
        }
    });
	$("#userForm").validate({
		onkeyup: false,
		rules: {
			email: {
				required: true,
				email: true,
				validateEmail: true
			}
		},
		messages: {
			email: {
				required: 'Please enter email',
				email: 'Please enter valid email',
				validateEmail: 'Email already exists'
			}
		},
        submitHandler: function(form) {
            var data;
            var response;
            var DOMAIN  = $('#DOMAIN').val();
            var fdata   = $('#userForm').serialize();
            $.ajax({
                type: "POST",url: DOMAIN+'user/add',data:fdata,async:false,
                success:function(data){
                    response = JSON.parse(data);
					if(response.error) {
                        if(response.email) {
                            $('#emailErr').css("display", "block");
                            $('#emailErr').text(response.email);
                        }
						return false;
					}			
                }
            });
            if(response.userId) {
                lightbox_close();
                var url = DOMAIN+'user/list?userId='+response.userId+'&action=addUser';
				window.location = url;
				return true;
            }
            return response.userId;
        }

	});
    $("#userRoleForm").validate({
        submitHandler: function(form) {
            var data;
            var response;
            var DOMAIN  = $('#DOMAIN').val();
            var fdata   = $('#userRoleForm').serialize();
            $.ajax({
                type: "POST",url: DOMAIN+'user/role',data:fdata,async:false,
                success:function(data){
                    response = JSON.parse(data);
                }
            });
            if(response.userId) {
                lightbox_close();
                var url = DOMAIN+'user/list?userId='+response.userId+'&action=changeRole';
                window.location = url;
                return true;
            }
            return response.userId;
        }
    });
	$("#applicationForm").validate({
		onkeyup: false,
		rules: {
			applicationGroupName: {
				required: true
			},
			applicationName: {
				required: true,
				validateApplicationName:true,
			},
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			applicationGroupName: {
				required: 'Please enter application group name'
			},
			applicationName: {
				required: 'Please enter Application Name',
				validateApplicationName: 'Application already Exists'
			},
			email: {
				required: 'Please enter email',
				email: 'Please enter valid email'
			}
		},
        submitHandler: function(form) {
            var data;
			var url;
			var response;
            var DOMAIN  = $('#DOMAIN').val();
            var MODULE  = $('#MODULE').val();
			var applicationId = $('#applicationId').val();
			var applicationName = $('#applicationName').val();
            var fdata   = $('#applicationForm').serialize();
			if(applicationId) {
				url = DOMAIN+'application/edit';
			}
			else {
				url = DOMAIN+'application/add';
			}
            $.ajax({
	            type: "POST",url: url,data:fdata,async:false,
		        success:function(data){
					response = JSON.parse(data);
					if(response.error) {
						if(response.applicationName) {
							$('#applicationNameErr').css("display", "block");
							$('#applicationNameErr').text(response.applicationName);
						}
						if(response.email) {
							$('#emailErr').css("display", "block");
							$('#emailErr').text(response.email);
						}
						return false;
					}
				}
            });
			if(response.applicationId || applicationId) {
				lightbox_close();
				if(MODULE == 'application') {
					if(applicationId) {
						var url = DOMAIN+'application/list?applicationId='+response.applicationId+'&action=editApplication';
					}
					else {
						var url = DOMAIN+'application/list?applicationId='+response.applicationId+'&action=addApplication';
					}
					window.location = url;
					return true;
				}
				else {
					$("#applicationId").append('<option selected value='+response.applicationId+'>'+applicationName+'</option>');
					$("#applicationId").trigger("liszt:updated");
				}
			}
			return response.applicationId;
        }
	});
    $("#systemUserForm").validate({
        onkeyup: false,
        rules: {
            systemUserRefName: {
				required: true,
				validateSystemUserRefName: true
			},
			username: 'required',
			password: 'required',
			
        },
        messages: {
            systemUserRefName: {
				required: 'Please enter Reference Name',
				validateSystemUserRefName: 'Reference name already exists'
			},
			username: 'Please enter username',
			password: 'Please enter password'
        },
        submitHandler: function(form) {
            var data;
			var url;
            var response;
			var MODULE  = $('#MODULE').val();
            var DOMAIN  = $('#DOMAIN').val();
            var systemUserId		= $('#systemUserId').val();
            var systemUserRefName	= $('#systemUserRefName').val();
            var username			= $('#username').val();
            var fdata				= $('#systemUserForm').serialize();
			if(systemUserId) {
				url = DOMAIN+'systemUser/edit';
			}
			else {
				url = DOMAIN+'systemUser/add';
			}
            $.ajax({
                type: "POST",url: url,data:fdata,async:false,
                success:function(data){
                    response = JSON.parse(data);
					if(response.error) {
						if(response.systemUserRefName) {
							$('#systemUserRefNameErr').css("display", "block");
							$('#systemUserRefNameErr').text(response.systemUserRefName);
						}
						if(response.username) {
							$('#usernameErr').css("display", "block");
							$('#usernameErr').text(response.username);
						}
						if(response.password) {
							$('#passwordErr').css("display", "block");
							$('#passwordErr').text(response.password);
						}
						return false;
					}
                }
            });
            if(response.systemUserId || systemUserId) {
                lightbox_close();
				if(MODULE == 'systemUser') {
					if(systemUserId) {
						var url = DOMAIN+'systemUser/list?systemUserId='+response.systemUserId+'&action=editSystemUser';
					}
					else {
						var url = DOMAIN+'systemUser/list?systemUserId='+response.systemUserId+'&action=addSystemUser';
					}
					window.location = url;
					return true;
				}
				else {
	                $("#systemUserId").append('<option selected value='+response.systemUserId+'>'+systemUserRefName+' ( '+ username + ' )</option>');
		            $("#systemUserId").trigger("liszt:updated");
	                $("#systemUserId2").append('<option selected value='+response.systemUserId+'>'+systemUserRefName+' ( '+ username + ' )</option>');
		            $("#systemUserId2").trigger("liszt:updated");
				}
            }
            return response.systemUserId;
        }
    });
	$("#configurationForm").validate({
		onkeyup: false,
		rules: {
			applicationId: 'required',
			serviceId: {
				required: true,
				validateConfiguration: true
			},
			systemUserId: 'required',
			systemUserId2: 'required',
			configurationTag: {
				required: true,
				validateConfigurationTag: true
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent());
		},
		messages: {
			applicationId: 'Please choose application',
			serviceId: {
				required: 'Please choose Service',
				validateConfiguration: 'Configuration already exists'
			},
			systemUserId: 'Please choose active System User',
			systemUserId2: 'Please choose inactive System User',
			configurationTag: {
				required: 'Please enter Tag Name',
				validateConfigurationTag: 'Tag name already exists'
			}
		},
	});
	$("#loginForm").validate({
		onkeyup: false,
		rules: {
			email: {
				required: true,
				email: true
			},
			password: 'required'
		},
		messages: {
			email: {
				required: "Please enter email",
				email: "Please enter valid email"
			},
			password: "Please enter password"
		}
	});
    $("#serverForm").validate({
		onkeyup: false,
        rules: {
            serverName: {
				required: true,
				validateServerName: true
			},
            serverIP: {
				required: true,
				validateServerIP: true
			},
            serverRefName: {
				required: true,
				validateServerRefName: true
			}
        },
        messages: {
            serverName: {
				required: "Please enter Server Name",
				validateServerName: "Server with this name already exists"
			},
            serverIP: {
				required: "Please enter Server IP",
				validateServerIP: "Server with this IP already exists"
			},
            serverRefName: {
				required: "Please enter Server Reference Name",
				validateServerRefName: "Server with this reference name already exists"
			}
        }
    });
    $("#serviceForm").validate({
		onkeyup: false,
        rules: {
            serviceTypeId: "required",
            servicePort: {
				required: true,
				validateService: true
			},
            serviceRefName: {
				required: true,
				validateServiceRefName: true
			},	
        },
        messages: {
            serviceTypeId: "Please choose Service Type",
            servicePort: {
				required: "Please enter the port",
				validateService: "Service on this port already added"
			},
            serviceRefName: {
				required : "Please enter Service Reference Name",
				validateServiceRefName: "Service with this reference name already exists"
			}
        },
		submitHandler: function(form) {
			var data;
			var url;
			var response;
			var DOMAIN	= $('#DOMAIN').val();
			var fdata	= $('#serviceForm').serialize();
			var serverId= $('#serverId').val();		
			var serviceId= $('#serviceId').val();		
			$.ajax({
				type: "POST",url: DOMAIN+'service/add',data:fdata,async:false,			
				success:function(data){
					response = JSON.parse(data);
					if(response.error) {
                       if(response.serviceRefName) {
                            $('#serviceRefNameErr').css("display", "block");
                            $('#serviceRefNameErr').text(response.serviceRefName);
                        }
                        if(response.servicePort) {
                            $('#servicePortErr').css("display", "block");
                            $('#servicePortErr').text(response.servicePort);
                        }
						return false;
					}
					if(serviceId) {
						url = DOMAIN+'server/view?serverId='+serverId+'&action=editService';
					}
					else {
						url = DOMAIN+'server/view?serverId='+serverId+'&action=addService';
					}
					window.location = url;
				}
			});
		}
    });
    $("#deployForm").validate({
        onkeyup: false,
        rules: {
            username: 'required',
            password: 'required',
			port: 'required'
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent());
        },
        messages: {
            username: 'Please enter the username',
            password: 'Please enter the password',
			port: 'Please enter the port'
        },
    });
    $("#releaseCommentForm").validate({
        onkeyup: false,
        rules: {
            releaseComment: 'required',
        },
        messages: {
            releaseComment: 'Please enter the comment',
        },
    });

    jQuery.validator.addMethod("validateFormat", function(value, element) {
		var fileExtension = ['txt', 'xml', 'php', 'yml'];
		 if ($.inArray(value.split('.').pop().toLowerCase(), fileExtension) == -1) {
			return false;
		}		
		return true;
    });
    jQuery.validator.addMethod("validateEmail", function(value, element) {
        var response;
        var DOMAIN = $('#DOMAIN').val();
        var fdata   = $('#userForm').serialize();
        $.ajax({
           type: "POST",url: DOMAIN+'user/validateEmail',data:fdata,async:false,
            success:function(data){
                response = JSON.parse(data);
            }
        });
        return response.status;
    });

    jQuery.validator.addMethod("validateSystemUserRefName", function(value, element) {
        var response;
        var DOMAIN = $('#DOMAIN').val();
		var systemUserRefName_old = $('#systemUserRefName_old').val();
        var fdata   = $('#systemUserForm').serialize();
		if((systemUserRefName_old==$('#systemUserRefName').val()))
			return true;
        $.ajax({
           type: "POST",url: DOMAIN+'systemUser/validateSystemUserRefName',data:fdata,async:false,
            success:function(data){
                response = JSON.parse(data);
            }
        });
        return response.status;
    });
    jQuery.validator.addMethod("validateApplicationName", function(value, element) {
        var response;
        var DOMAIN = $('#DOMAIN').val();
        var fdata   = $('#applicationForm').serialize();
		var applicationName_old	= $('#applicationName_old').val();
		if((applicationName_old==$('#applicationName').val()))
			return true;
        $.ajax({
           type: "POST",url: DOMAIN+'application/validateApplicationName',data:fdata,async:false,
            success:function(data){
                response = JSON.parse(data);
            }
        });
        return response.status;
    });
	jQuery.validator.addMethod("validateConfiguration", function(value, element) {
		var response;
		var DOMAIN = $('#DOMAIN').val();
		var fdata   = $('#configurationForm').serialize();
		var applicationId_old	= $('#applicationId_old').val();
		var serviceId_old		= $('#serviceId_old').val();
		var systemUserId_old	= $('#systemUserId_old').val();
		if((applicationId_old==$('#applicationId').val()) && (serviceId_old==$('#serviceId').val()) && (systemUserId_old==$('#systemUserId').val()))
			return true;
		$.ajax({
		   type: "POST",url: DOMAIN+'configuration/validateConfiguration',data:fdata,async:false,
			success:function(data){
				response = JSON.parse(data);
			}
	    });
		return response.status;
	});
	jQuery.validator.addMethod("validateConfigurationTag", function(value, element) {
		var response;
		var DOMAIN = $('#DOMAIN').val();
		if($('#configurationTag_old').val()==value)
			return true;
		$.ajax({
		   type: "POST",url: DOMAIN+'configuration/validateConfigurationTag',data:"configurationTag="+value,async:false,
			success:function(data){
				response = JSON.parse(data);
			}
	    });
		return response.status;
	});
	
	jQuery.validator.addMethod("validateServerName", function(value, element) {
		var response;
		var DOMAIN = $('#DOMAIN').val();
		if($('#serverName_old').val()==value)
			return true;
		$.ajax({
		   type: "POST",url: DOMAIN+'server/validateServerName',data:"serverName="+value,async:false,
			success:function(data){
				response = JSON.parse(data);
			}
	    });
		return response.status;
	});
    jQuery.validator.addMethod("validateServerIP", function(value, element) {
        var response;
        var DOMAIN = $('#DOMAIN').val();
		if($('#serverIP_old').val()==value)
			return true;
        $.ajax({
           type: "POST",url: DOMAIN+'server/validateServerIP',data:"serverIP="+value,async:false,
            success:function(data){
                response = JSON.parse(data);
            }
        });
		return response.status;
    });
    jQuery.validator.addMethod("validateServerRefName", function(value, element) {
        var response;
        var DOMAIN = $('#DOMAIN').val();
		if($('#serverRefName_old').val()==value)
			return true;
        $.ajax({
           type: "POST",url: DOMAIN+'server/validateServerRefName',data:"serverRefName="+value,async:false,
            success:function(data){
                response = JSON.parse(data);
            }   
        }); 
		return response.status;
    });
	jQuery.validator.addMethod("validateServiceRefName", function(value, element) {
		var response;
		var DOMAIN = $('#DOMAIN').val();
		if($('#serviceRefName_old').val()==value)
			return true;
		$.ajax({
		   type: "POST",url: DOMAIN+'service/validateServiceRefName',data:"serviceRefName="+value,async:false,
			success:function(data){
				response = JSON.parse(data);
			}
	    });
		return response.status;
	});
	jQuery.validator.addMethod("validateService", function(value, element) {
		var response;
		var serverId = $('#serverId').val();
		var serviceTypeId = $('#serviceTypeId').val();
		var servicePort = $('#servicePort').val();
		var DOMAIN = $('#DOMAIN').val();
		if($('#serviceTypeId_old').val()==serviceTypeId && $('#servicePort_old').val()==servicePort)
			return true;
		$.ajax({
		   type:"POST",url:DOMAIN+'service/validateService',data:"serverId="+serverId+"&serviceTypeId="+serviceTypeId+"&servicePort="+servicePort,async:false,
			success:function(data){
				response = JSON.parse(data);
			}
	    });
		return response.status;
	});
	jQuery.validator.addMethod("validateConfigureApplication", function(value, element) {
		var response;
		var DOMAIN = $('#DOMAIN').val();
		var applicationId = $('#applicationId').val();	
		$.ajax({
			type:"POST",url:DOMAIN+'configureApplication/validateConfigureApplication',data:"applicationId="+applicationId,async:false,
			success:function(data){
				response = JSON.parse(data);
			}
		});
		if(!response.status) {
			url = DOMAIN+'configureApplication/edit?applicationId='+applicationId;
			window.location = url;
			return true;
		}
		return response.status;
	});
	jQuery.validator.addMethod("multiemail", function (value, element) {
		if (this.optional(element)) {
			return true;
		}
		var emails = value.split(',');
		valid = true;
		for (var i = 0, limit = emails.length; i < limit; i++) {
			value = emails[i];
			valid = valid && jQuery.validator.methods.email.call(this, value, element);
		}
		return valid;
	}, "Invalid email format: please use a comma to separate multiple email addresses.");
});
