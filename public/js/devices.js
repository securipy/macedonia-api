$(document).ready(function(){

	$('#devices_datatable').dataTable({
		"language": {
			"url": "/../locale/"+language_site+"/datatable.json",
        }
	});


	var id_scan = 0;


	$('#devices_datatable').on("click", ".delete_device", function() {
		
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
						oTable.fnAddData( [
							server_ip_domain,
							'<button type="button" class="btn btn-default select_audit" data-id="'+data.result[0].id+'">Details</button><button type="button" class="btn btn-danger delete_audit" data-id="'+data.result[0].id+'">eliminar</button>'] ); 
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