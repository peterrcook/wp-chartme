google.load('visualization', '1.0', {'packages':cmeParams.packages});
google.setOnLoadCallback(drawChart);
function drawChart() {
  for(var i = 0, l = cmeParams.charts.length; i < l; i++) {
    var p = cmeParams.charts[i];

    // Create the data table.
    var data = new google.visualization.arrayToDataTable(p.data, p.attr.firstrowdata === 'true' ? true : false);
    console.log(p);

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization[p.attr.type](document.getElementById(p.attr.id));
    chart.draw(data, p.options);
  }

}
