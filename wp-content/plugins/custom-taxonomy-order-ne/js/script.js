
/*
 * Custom Taxonomy Order NE
 */


jQuery(document).ready(function(jQuery) {

	/* Submit button click event */
	jQuery("#custom-loading").hide();
	jQuery("#order-submit").click(function() {
		customtaxorder_ordersubmit();
	});

	/* Button to sort the list alphabetically */
	jQuery("#order-alpha").click(function(e) {
		e.preventDefault();
		jQuery("#custom-loading").show();
		customtaxorder_orderalpha();
		//jQuery("#order-submit").trigger("click");
		setTimeout(function(){
			jQuery("#custom-loading").hide();
		},500);
		jQuery("#order-alpha").blur();
	});

});


function customtaxorder_addloadevent(){

	/* Make the Terms sortable */
	jQuery("#custom-order-list").sortable({
		placeholder: "sortable-placeholder",
		revert: false,
		tolerance: "pointer"
	});

	/* The same for the Taxonomies */
	jQuery("#custom-taxonomy-list").sortable({
		placeholder: "sortable-placeholder",
		revert: false,
		tolerance: "pointer"
	});

};
addLoadEvent(customtaxorder_addloadevent);


function customtaxorder_ordersubmit() {

	/* Terms */
	var newOrder = jQuery("#custom-order-list").sortable("toArray");
	jQuery("#custom-loading").show();
	jQuery("#hidden-custom-order").val(newOrder);

	/* Taxonomies */
	var newOrder_tax = jQuery("#custom-taxonomy-list").sortable("toArray");
	jQuery("#custom-loading").show();
	jQuery("#hidden-taxonomy-order").val(newOrder_tax);

	return true;
}


function customtaxorder_orderalpha() {
	jQuery("#custom-order-list li").sort(customtaxorder_asc_sort).appendTo('#custom-order-list');
	var newOrder = jQuery("#custom-order-list").sortable("toArray");
	jQuery("#custom-loading").show();
	jQuery("#hidden-custom-order").val(newOrder);
	return true;
}


// Ascending sort
function customtaxorder_asc_sort(a, b) {
	//return (jQuery(b).text()) < (jQuery(a).text()) ? 1 : -1;
	//console.log (jQuery(a).text());
	return jQuery(a).text().toUpperCase().localeCompare(jQuery(b).text().toUpperCase());
}

