<script>
$(function() {	
	Morris.Bar({		
		element: 'morris-bar-chart',		
		/*data: [{ y: '2006', a: 60},			
		{ y: '2007', a: 75},			
		{ y: '2008', a: 50},			
		{ y: '2009', a: 75},			
		{ y: '2010', a: 50},
		{ y: '2011', a: 75},			
		{ y: '2012', a: 100}],*/		
		data: [<?php echo $top_sales_medicine ?>],
		xkey: 'y',		
		ykeys: ['a'],		
		labels: ['Quantity'],		
		hideHover: 'auto',		
		resize: true,		
		barColors: ['#1ab394', '#cacaca'],	
	});
});

$(function() {	
	Morris.Bar({		
		element: 'morris-bar-chart1',		
		/*data: [{ y: '2006', a: 60},			
		{ y: '2007', a: 75},			
		{ y: '2008', a: 50},			
		{ y: '2009', a: 75},			
		{ y: '2010', a: 50},
		{ y: '2011', a: 75},			
		{ y: '2012', a: 100}],*/		
		data: [<?php echo $top_search_medicine ?>],
		xkey: 'y',		
		ykeys: ['a'],		
		labels: ['Quantity'],		
		hideHover: 'auto',		
		resize: true,		
		barColors: ['#1ab394', '#cacaca'],	
	});
});
</script>