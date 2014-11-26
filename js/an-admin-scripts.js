/*
	an_admin_scripts.js
	AdBlock Notify
	Copyright: (c) 2014 Brice CAPOBIANCO, b-website.com
*/
jQuery(document).ready(function($) {
	
	if ($('.an-stats-table').length > 0){

		//Widget
		var doughnutData = [
				{
					value: anWidgetOptions.totalNoBlocker,
					color:"#34495e"
				},
				{
					value : anWidgetOptions.anCountBlocked,
					color : "#e74c3c"
				}			
			];
		var doughnutDataToday = [
				{
					value: anWidgetOptions.totalNoBlockerToday,
					color:"#34495e"
				},
				{
					value : anWidgetOptions.anCountBlockedHistory,
					color : "#e74c3c"
				}			
			];
		
		var lineChartData = {
			labels : ["Today","Day -1","Day -2","Day -3","Day -4","Day -5","Day -6"],
			datasets : [
				{
					fillColor : "rgba(50, 82, 110,0.5)",
					strokeColor : "rgba(50, 82, 110,0.8)",
					pointColor : "rgba(250,250,250,1)",
					pointStrokeColor : "rgba(50, 82, 110,1)",
					data : anWidgetOptions.anDataHistotyTotal
				},
				{
					fillColor : "rgba(231, 76, 60,0.6)",
					strokeColor : "rgba(173, 52, 40,0.8)",
					pointColor : "rgba(250,250,250,0.8)",
					pointStrokeColor : "#e74c3c",
					data : anWidgetOptions.anDataHistotyBlocked
				}
			]			
		}
		var myLine = new Chart(document.getElementById("an-canvas-line").getContext("2d")).Line(lineChartData);
		var widthdonut = $("#an_dashboard_widgets .inside .an-canvas-container-donut").width();
		var widthline = $("#an_dashboard_widgets .inside").width();
		$("canvas").attr("width",widthdonut);
		$("canvas#an-canvas-line").attr("width",widthline);
		var myDoughnut = new Chart(document.getElementById("an-canvas-donut").getContext("2d")).Doughnut(doughnutData);
		var myDoughnut = new Chart(document.getElementById("an-canvas-donut-today").getContext("2d")).Doughnut(doughnutDataToday);
		var myLine = new Chart(document.getElementById("an-canvas-line").getContext("2d")).Line(lineChartData);
		
		//Admin page
		resetButton = $('p.submit button.button-secondary[value!="save"]');
		resetButtonVal = resetButton.attr('onclick');
		resetButton.attr('onclick', 'javascript:if(!confirm(\'Are you sure ? Your custom settings will be lost.\')) return false; ' + resetButtonVal);  
	
	}
	
});