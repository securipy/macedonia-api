$(document).ready(function(){



	$('#dashboard_datatable').dataTable({
		"language": {
			"url": "../locale/"+language_site+"/datatable.json",
        }
	});



	$('#dashboard_datatable').on("click", ".select_audit", function() {
		document.cookie = "audit="+$(this).data("id")+";path=/";
		$("#menu-audit").show();
	});

	$('#dashboard_datatable').on("click", ".delete_audit", function() {
		
	});





	$("#save-new-adit").click(function(){
		var audit_name = $("#audit-name").val();

		var datos = {
			'name':audit_name
		}

		$.ajax({
			url: "/"+language_site+"/audit/new",
	        headers: {
        		'GRANADA-TOKEN':readCookie('token'),
        	},
			type: "post",
			data:datos,
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'New audit',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					
					var oTable = $('#dashboard_datatable').dataTable();
						oTable.fnAddData( [
							data.result[0].name,
							data.result[0].date_create,
							'<button type="button" class="btn btn-default select_audit" data-id="'+data.result[0].id+'">'+i18n.select+'</button><button type="button" class="btn btn-danger delete_audit" data-id="'+data.result[0].id+'">'+i18n.delete+'</button>'] ); 
					$('#add_new').modal('hide');

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
});