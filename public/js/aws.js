$(document).ready(function(){

	$('#datatable').on("click", ".start_aws", function() {
		$.ajax({
			url: "/aws/instance/start/"+$(this).data("id"),
	        headers: {
        		'GRANADA-TOKEN':readCookie('token'),
        	},
			type: "get",
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'AWS Start',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					window.location = '/aws/instances';
				}else{
					new PNotify({
						title: 'Error Audit',
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


	$('#datatable').on("click", ".stop_aws", function() {
		$.ajax({
			url: "/aws/instance/stop/"+$(this).data("id"),
	        headers: {
        		'GRANADA-TOKEN':readCookie('token'),
        	},
			type: "get",
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'AWS Start',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					window.location = '/aws/instances';
				}else{
					new PNotify({
						title: 'AWS Start Error',
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

