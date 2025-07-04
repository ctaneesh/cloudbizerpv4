
(function(window, document, $) {
  'use strict';

	// Checkbox & Radio 1
    $('.icheck-task input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
    });

    // Basic Map
    // ------------------------------
    var movedBrooklyn =  new GMaps({
        div: '#moved-brooklyn',
        lat: 40.650002,
        lng: -73.949997,
        height: 400,
    });
    movedBrooklyn.addMarker({
        lat: 40.650002,
        lng: -73.949997,
        title: 'Moved Brooklyn',
        infoWindow: {
            content: '<h3>Moved Brooklyn</h3> <p>Our new office with more team members.</p>'
        }
    });

})(window, document, jQuery);