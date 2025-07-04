

// Pie chart
// ------------------------------
$(window).on("load", function(){

    var options = {
        series: {
            pie: {
                show: true
            }
        },
        colors: ['#99B898', '#FECEA8', '#FF847C', '#E84A5F', '#2A363B', '#6C5B7B']
    };

    var data = [
        { label: "Series1",  data: 50},
        { label: "Series2",  data: 70},
        { label: "Series3",  data: 60},
        { label: "Series4",  data: 90},
        { label: "Series5",  data: 80},
        { label: "Series6",  data: 110}
    ];

    $.plot("#simple-pie-chart", data, options);
});