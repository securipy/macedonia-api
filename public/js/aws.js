$(document).ready(function(){

	$('#aws_datatable').dataTable({
		"language": {
			"url": "/../locale/"+language_site+"/datatable.json",
        }
	});


	$('#aws_datatable').on("click", ".start_aws", function() {
		$.ajax({
			url: "/"+language_site+"/aws/instance/start/"+$(this).data("id"),
	        headers: {
        		'GRANADA-TOKEN':readCookie('token'),
        	},
			type: "get",
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'AWS',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					window.location = "/"+language_site+'/aws/instances';
				}else{
					new PNotify({
						title: 'Error AWS',
						text: data.message,
						styling: 'bootstrap3'
					});
				}
			},
			error: function(xhr, status, error) {
				new PNotify({
					title: 'Oh No!',
					text: xhr.responseText,
					type: 'error',
					styling: 'bootstrap3'
				});
				var err = eval("(" + xhr.responseText + ")");
				console.log(err);
			}
		});
	});


	$('#aws_datatable').on("click", ".stop_aws", function() {
		$.ajax({
			url: "/"+language_site+"/aws/instance/stop/"+$(this).data("id"),
	        headers: {
        		'GRANADA-TOKEN':readCookie('token'),
        	},
			type: "get",
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'AWS',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					window.location = '/aws/instances';
				}else{
					new PNotify({
						title: 'AWS Error',
						text: data.message,
						styling: 'bootstrap3'
					});
				}
			},
			error: function(xhr, status, error) {
				new PNotify({
					title: 'Oh No!',
					text: xhr.responseText,
					type: 'error',
					styling: 'bootstrap3'
				});
				var err = eval("(" + xhr.responseText + ")");
				console.log(err);
			}
		});
	});


	});

