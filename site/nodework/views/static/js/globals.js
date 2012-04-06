function initTooltips(){
	jQuery("span[title], a[title]").tooltip({
		offset:[-6,0]
	});

	jQuery("img[title]").tooltip({
		offset:[30,0]
	});
}

jQuery(document).ready(function(){
	initTooltips();
});
