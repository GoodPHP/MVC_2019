function btc_send() {
	alert("Loading form...");
}


function btc_generate_qr_code(address) {
	var url = $("#url").val();
	var data_url = url + "requests/btc_forms/qr_code/"+address;
	$.ajax({
		type: "POST",
		url: data_url,
		data: $("#btc_generate_qr_code").serialize(),
		dataType: "html",
		success: function (data) {
			$("#btc_qr_code").html(data);
		}
	});
}


function setAddress(address){
	return	$("input[name='to_address']").val(address);
}


function showMyAddresses(){
	return $("#MyAddressesBlock").show();
}

function hideMyAddresses(){
	return $("#MyAddressesBlock").hide();
}

function callModalQrCode(address){
	var address = $("#ModalQrCode #address_name").val(address);
	var amount = $("#ModalQrCode #amount_count").val();

	$("#ModalQrCode #class_generate_qrcode").html('<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=bitcoin:'+address+'?amount='+amount+'&choe=UTF-8" style="width:80%;">');

	$('#ModalQrCode').modal('show');
}

function generateQR(){
	var address = $("#ModalQrCode #address_name").val();
	var amount = $("#ModalQrCode #amount_count").val();

	$("#ModalQrCode #class_generate_qrcode").html('<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=bitcoin:'+address+'?amount='+amount+'&choe=UTF-8" style="width:80%;">');
}
