if (typeof pantera == "undefined" || !pantera) {
    var pantera = {};
}

pantera.seo = {
    init: function () {
	$('.pantera-seo .toggle').on('click', function() {
            $($(this).attr('href')).toggle();
            return false;
	});
    },
}

pantera.seo.init();