
(function(window, document, $) {
	'use strict';
	// Default usage
	$('.basic-maxlength').maxlength({
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
	});

	// Change the threshold value
	$('.threshold-maxlength').maxlength({
		threshold: 15,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
	});

	// AlwaysShow
	$('.always-show-maxlength').maxlength({
		alwaysShow: true,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
	});

	// Change Badge Color using warningClass & limitReachedClass
	$('.badge-maxlength').maxlength({
		warningClass: "badge badge-info",
		limitReachedClass: "badge badge-warning"
	});

	// Change Badge Format
	$('.badge-text-maxlength').maxlength({
		alwaysShow: true,
		separator: ' of ',
		preText: 'You have ',
		postText: ' chars remaining.',
		validate: true,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
	});

	// Position
	$('.position-maxlength').maxlength({
		alwaysShow: true,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
		placement: 'top'
		// Options : top, bottom, left or right
		//  bottom-right, top-right, top-left, bottom-left and centered-right.
	});

	$('.position-corner-maxlength').maxlength({
		alwaysShow: true,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
		placement: 'top-left'
		//  bottom-right, top-right, top-left, bottom-left and centered-right.
	});

	$('.position-inside-maxlength').maxlength({
		alwaysShow: true,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
		placement: 'centered-right'
		// Option : centered-right.
	});

	$('.featured-maxlength').maxlength({
		alwaysShow: true,
		threshold: 10,
		warningClass: "badge badge-info",
		limitReachedClass: "badge badge-warning",
		placement: 'top',
		message: 'Used %charsTyped% of %charsTotal% chars.'
	});

	// Teatarea Maxlength
	$('.textarea-maxlength').maxlength({
		alwaysShow: true,
		warningClass: "badge badge-success",
		limitReachedClass: "badge badge-danger",
	});

})(window, document, jQuery);