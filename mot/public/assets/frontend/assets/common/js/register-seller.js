$(document).ready(function(){
	var accountType = $('input[name="type"]:checked').attr("id");

	handleTaxInputs(accountType);
});

$('input[name="type"]').on('change', function(){
	let type = $(this).attr("id");

	handleTaxInputs(type);
	/*hideTaxInputs();
	if(type == 'private_company' || type == 'limited_stock_company'){
		showTaxInputs();
	}*/
});

function handleTaxInputs(type){
	hideTaxInputs();
	if(type == 'private_company' || type == 'limited_stock_company'){
		showTaxInputs();
	}
}

function showTaxInputs(){
	$('#tax_info').show();
}

function hideTaxInputs(){
	$('#tax_info').hide();
}