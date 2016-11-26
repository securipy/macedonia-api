$(document).ready(function(){

	$('#datatable').on("click", ".select_audit", function() {
		document.cookie = "audit="+$(this).data("id")+";path=/";
		$("#menu-audit").show();
	});

	$('#datatable').on("click", ".delete_audit", function() {
		//alert($(this).data("id"));



	});





	$("#save-new-adit").click(function(){
		var audit_name = $("#audit-name").val();

		var datos = {
			'name':audit_name
		}

		$.ajax({
			url: "/audit/new",
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
					
					var oTable = $('#datatable').dataTable();
						oTable.fnAddData( [
							data.result[0].name,
							data.result[0].date_create,
							'<button type="button" class="btn btn-default select_audit" data-id="'+data.result[0].id+'">Selecionar</button><button type="button" class="btn btn-danger delete_audit" data-id="'+data.result[0].id+'">eliminar</button>'] ); 
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