

// Donut rotated chart
// ------------------------------

// Load the Visualization API and the corechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawDonutRotated);

// Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
function drawDonutRotated() {

    // Create the data table.
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Work',     11],
        ['Eat',      2],
        ['Commute',  2],
        ['Watch TV', 2],
        ['Sleep',    7]
    ]);


    // Set chart options
    var options_donut_rotated = {
        title: 'My Daily Activities',
        height: 400,
        fontSize: 12,
        colors:['#99B898','#FECEA8', '#FF847C', '#E84A5F', '#474747'],
        pieHole: 0.55,
        pieStartAngle: 180,
        chartArea: {
            left: '5%',
            width: '90%',
            height: 350
        },
    };

    // Instantiate and draw our chart, passing in some options.
    var donutRotated = new google.visualization.PieChart(document.getElementById('donut-rotated'));
    donutRotated.draw(data, options_donut_rotated);

}


// Resize chart
// ------------------------------

$(function () {

    // Resize chart on menu width change and window resize
    $(window).on('resize', resize);
    $(".menu-toggle").on('click', resize);

    // Resize function
    function resize() {
        drawDonutRotated();
    }
});