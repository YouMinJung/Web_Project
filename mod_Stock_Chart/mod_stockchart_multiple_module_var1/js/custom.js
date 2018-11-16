
// function showChart($data){
// 	// console.log($data);
// 	$data = JSON.parse($data);
// 	$closePrices = $data.close;
// 	$volumes = $data.volumes;
// 	$dates = $data.dates;
// 	$symbol = $data.symbol;
// 	var chartData1 = [];
	
	
// 	for (var i = 0; i < $dates.length; i++) {
// 		var newDate = new Date($dates[i]*1000);
// 		var a1 = $closePrices[i];
// 		var b1 = $volumes[i];
		
// 		chartData1.push({
// 			date: newDate,
// 			value: a1,
// 			volume: b1
// 		});
// 	};

// 			AmCharts.makeChart("chartdiv "+$symbol+"", {
// 				type: "stock",
// 				dataSets: [{
// 					title: ""+$symbol+"",
// 					fieldMappings: [{
// 						fromField: "value",
// 						toField: "value"
// 					}, {
// 						fromField: "volume",
// 						toField: "volume"
// 					}],
// 					dataProvider: chartData1,
// 					categoryField: "date"
// 				},

// 				// {
// 				// 	title: "second data set",
// 				// 	compared:true,
// 				// 	fieldMappings: [{
// 				// 		fromField: "value",
// 				// 		toField: "value"
// 				// 	}, {
// 				// 		fromField: "volume",
// 				// 		toField: "volume"
// 				// 	}],
// 				// 	dataProvider: chartData2,
// 				// 	categoryField: "date"
// 				// },

// 				// {
// 				// 	title: "third data set",
// 				// 	fieldMappings: [{
// 				// 		fromField: "value",
// 				// 		toField: "value"
// 				// 	}, {
// 				// 		fromField: "volume",
// 				// 		toField: "volume"
// 				// 	}],
// 				// 	dataProvider: chartData3,
// 				// 	categoryField: "date"
// 				// },

// 				// {
// 				// 	title: "fourth data set",
// 				// 	fieldMappings: [{
// 				// 		fromField: "value",
// 				// 		toField: "value"
// 				// 	}, {
// 				// 		fromField: "volume",
// 				// 		toField: "volume"
// 				// 	}],
// 				// 	dataProvider: chartData4,
// 				// 	categoryField: "date"
// 				// }
// 				],

// 				panels: [{

// 					showCategoryAxis: false,
// 					title: "Close",
// 					percentHeight: 70,

// 					stockGraphs: [{
// 						id: "g1",

// 						valueField: "value",
// 						comparable: true,
// 						compareField: "value",
// 						bullet: "round",
// 						bulletBorderColor: "#FFFFFF",
// 						bulletBorderAlpha: 1,
// 						balloonText: "[[title]]:<b>[[value]]</b>",
// 						compareGraphBalloonText: "[[title]]:<b>[[value]]</b>",
// 						compareGraphBullet: "round",
// 						compareGraphBulletBorderColor: "#FFFFFF",
// 						compareGraphBulletBorderAlpha: 1
// 					}],

// 					stockLegend: {
// 						periodValueTextComparing: "[[percents.value.close]]%",
// 						periodValueTextRegular: "[[value.close]]"
// 					}
// 				},

// 				{
// 					title: "Volume",
// 					percentHeight: 30,
// 					stockGraphs: [{
// 						valueField: "volume",
// 						type: "column",
// 						showBalloon: false,
// 						fillAlphas: 1
// 					}],


// 					stockLegend: {
// 						periodValueTextRegular: "[[value.close]]"
// 					}
// 				}],

// 				chartScrollbarSettings: {
// 					graph: "g1",
// 					updateOnReleaseOnly:false
// 				},

// 				chartCursorSettings: {
// 					valueBalloonsEnabled: true,
// 					valueLineEnabled:true,
// 					valueLineBalloonEnabled:true
// 				},

// 				periodSelector: {
// 					position: "left",
// 					periods: [{
// 						period: "DD",
// 						count: 10,
// 						label: "10 days"
// 					}, {
// 						period: "MM",
// 						selected: true,
// 						count: 1,
// 						label: "1 month"
// 					},{
// 						period: "MM",
// 						selected: true,
// 						count: 3,
// 						label: "3 months"
// 					}, {
// 						period: "YYYY",
// 						count: 1,
// 						label: "1 year"
// 					}, {
// 						period: "YTD",
// 						label: "YTD"
// 					}, {
// 						period: "MAX",
// 						label: "MAX"
// 					}]
// 				},

// 				dataSetSelector: {
// 					position: "left"
// 				}
// 	});
// }