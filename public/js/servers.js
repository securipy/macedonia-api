$(document).ready(function(){

	var id_server = 0;

	infoserver = function(id_server){
			return $.ajax({
			url: "/server/"+id_server,
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
			},
			type: "get",
			dataType: "json",
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
	}


	scripts = function(){
		 	return $.ajax({
			url: "/scripts/list",
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
			},
			type: "get",
			dataType: "json",
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
	}


	$('#datatable').on("click", ".delete_server", function() {
		id_server = $(this).data("id");
		$("#myModal_delete_server").modal('show');
	});


	$("#delete_server").click(function(){
		$.ajax({
			url: "/server/"+id_server,
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
			},
			type: "delete",
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'Server',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});

					var oTable = $('#datatable').dataTable()
					var nRow = $("button[data-id="+id_server+"]").parent().parent('tr')[0];
					oTable.fnDeleteRow( nRow );
					$("#myModal_delete_server").modal('hide');

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



	$('#datatable').on("click", ".edit_server", function() {
		id_server = $(this).data("id");
		$.when( infoserver(id_server), scripts() ).done(function ( is, sc ) {
			is = is[0];
			sc = sc[0];
			if(is.response == true && sc.response == true){
				$("#scripts").html("");
				active_scripts = is.result[0].scripts.split(",");
					$.each( sc.result, function( index, value ){
						if($.inArray(value.name,active_scripts) != -1){
							$("#scripts").append('<div class="checkbox"><label><input type="checkbox" checked name="'+value.name+'" value="'+value.id+'">'+value.name+'</label></div>');
						}else{
						$("#scripts").append('<div class="checkbox"><label><input type="checkbox" name="'+value.name+'" value="'+value.id+'">'+value.name+'</label></div>');	
						}
					});
				$("#server-ip-domain").val(is.result[0].ip_domain);
				$("#server-name").val(is.result[0].name);
				$("#add-new-server-modal").modal('show');
			}else{
				if(is.response == false){
					new PNotify({
						title: 'Error server',
						text: data.message,
						styling: 'bootstrap3'
					});
				}
				if(sc.response == false){
					new PNotify({
						title: 'Error server',
						text: data.message,
						styling: 'bootstrap3'
					});
				}
			}	
		});
	});


	$("#add-server").click(function(){
		$("#server-name").val("");
		$("#server-ip-domain").val("");
		$("#keys").html("");
		id_server = 0;
		$.ajax({
			url: "/scripts/list",
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
			},
			type: "get",
			dataType: "json",
			success: function(data) {
				if(data.response==true){

					$("#scripts").html("");
					$.each( data.result, function( index, value ){
						$("#scripts").append('<div class="checkbox"><label><input type="checkbox" value="'+value.id+'">'+value.name+'</label></div>');
					});
					$("#add-new-server-modal").modal('show')


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



	$('#add-new-server-modal').on("click", "#save-server", function() {
		
		var scripts_vals = [];
		
		$('#scripts input:checkbox:checked').each(function(index) {
			scripts_vals.push($(this).val());
		});
		var server_name = $("#server-name").val();
		var server_ip_domain = $("#server-ip-domain").val();
		
		var data = {
			'name':server_name,
			'ip_domain':server_ip_domain,
			'scripts':scripts_vals,
		}

		if(id_server == 0){
			url = "/server/new";
			type = "POST"

		}else{
			url = "/server/update/"+id_server;
			type = "PUT"
		}


		$.ajax({
			url: url,
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
			},
			type: type,
			data:data,
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'Server',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
					var oTable = $('#datatable').dataTable();
					if(id_server == 0){
						$("#keys").html('<div class="alert alert-warning alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>COPY THE KEYs not show other time:</strong><br>Public Key:<br>'+data.result[0].public_key+'<br>Private Key:<br>'+data.result[0].private_key+' </div>');
						oTable.fnAddData( [
							data.result[0].name,
							data.result[0].ip_domain,
							data.result[0].scripts,
							'<button type="button" class="btn btn-default select_audit" data-id="'+data.result[0].id+'">Editar</button><button type="button" class="btn btn-danger delete_audit" data-id="'+data.result[0].id+'">eliminar</button>'] ); 
					}else{
						var nRow = $("button[data-id="+id_server+"]").parent().parent('tr')[0];
							oTable.fnUpdate(data.result[0].name, nRow, 0  ); // Single cell
							oTable.fnUpdate(data.result[0].ip_domain, nRow, 1  ); // Single cell*/
							oTable.fnUpdate(data.result[0].scripts, nRow, 2 ); // Single cell*/
							$("#add-new-server-modal").modal('hide');
					}
				}else{
					new PNotify({
						title: 'Error server',
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