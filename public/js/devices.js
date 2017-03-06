$(document).ready(function(){

	//console.log(scripts_list)



	$('#devices_datatable').dataTable({
		"language": {
			"url": "/../locale/"+language_site+"/datatable.json",
        }
	});

	var id_device = 0;
	var id_scan = 0;


	$("#delete_device_confirm").click(function(){
		$.ajax({
			url: "/"+language_site+"/device/"+id_device,
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
				'audit':readCookie('audit'),

			},
			type: "delete",
			dataType: "json",
			success: function(data) {
				if(data.response==true && data.result == 1){
					new PNotify({
						title: 'Server',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});

					var oTable = $('#devices_datatable').dataTable()
					var nRow = $("button[data-id="+id_device+"]").parent().parent('tr')[0];
					oTable.fnDeleteRow( nRow );
					$("#myModal_delete_device").modal('hide');

				}else{
					new PNotify({
						title: 'Error Server',
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



	$('#devices_datatable').on("click", ".delete_device", function() {
		id_device = $(this).data("id");
		$("#myModal_delete_device").modal('show');
	});



	$("#add-device").click(function(){
		id_scan = 0;
		$("#server-ip-domain").val("");
		$("#add-new-device-modal").modal('show');
	});



	$('#add-new-device-modal').on("click", "#save-device", function() {

		var server_ip_domain = $("#server-ip-domain").val();
		
		var data = {
			'ip_domain':server_ip_domain,
		}

		if(id_scan == 0){
			url = "/"+language_site+"/device/new";
			type = "POST"

		}else{
			url = "/"+language_site+"/device/update/"+id_scan;
			type = "PUT"
		}


		$.ajax({
			url: url,
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
				'audit':readCookie('audit'),
			},
			type: type,
			data:data,
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'Device',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					var oTable = $('#devices_datatable').dataTable();
					if(id_scan == 0){
						var_html = '<div class="btn-group">'+
                                '<button type="button" class="btn btn-default">Actions</button>'+
                                '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'+
                                  '<span class="caret"></span>'+
                                  '<span class="sr-only">Toggle Dropdown</span>'+
                                '</button>'+
                                '<ul class="dropdown-menu" role="menu">'
									$.each(scripts_list, function(i, plugin) {
										var_html =var_html + '<li><a href="'+plugin.url+data.result[0]+'">'+plugin.name+'</a></li>'
									});      
                                 var_html = var_html+ '</li>'+
                                '</ul>'+
                              '</div>';
						oTable.fnAddData( [
							server_ip_domain,
							
							var_html+' <button type="button" class="btn btn-danger delete_device" data-id="'+data.result[0]+'">eliminar</button>'] ); 
						$("#add-new-device-modal").modal('hide')

					}else{
						var nRow = $("button[data-id="+id_server+"]").parent().parent('tr')[0];
							oTable.fnUpdate(data.result[0].ip_domain, nRow, 0  ); // Single cell
							$("#add-new-device-modal").modal('hide');
					}
				}else{
					new PNotify({
						title: 'Error device',
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