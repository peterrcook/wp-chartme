/*if(cmeParams.attr.package)
	google.load('visualization', '1.0', {'packages':cmeParams.attr.package});
else*/ 
	google.load('visualization', '1.0', {'packages':['corechart ']});

google.setOnLoadCallback(drawChart);
function drawChart() {
	for(var i = 0, l = cmeParams.length; i < l; i++) {
		var p = cmeParams[i];

		// Create the data table.
		var data = new google.visualization.arrayToDataTable(p.data, p.attr.firstrowdata === 'true' ? true : false);
		console.log(p);

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization[p.attr.type](document.getElementById(p.attr.id));
		chart.draw(data, p.options);
	}

}
