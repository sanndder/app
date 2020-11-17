/* ------------------------------------------------------------------------------
 *
 *  # Statistics widgets
 *
 *  Demo JS code for widgets_stats.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var StatisticWidgets = function()
{
	
	
	//
	// Setup module components
	//
	
	// Messages area chart
	var _areaChartWidget = function(element, chartHeight, color)
	{
		if( typeof d3 == 'undefined' )
		{
			console.warn('Warning - d3.min.js is not loaded.');
			return;
		}
		
		// Initialize chart only if element exsists in the DOM
		if( element )
		{
			
			
			// Basic setup
			// ------------------------------
			
			// Define main variables
			var d3Container = d3.select(element),
				margin = {top:0, right:0, bottom:0, left:0},
				width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
				height = chartHeight - margin.top - margin.bottom;
			
			// Date and time format
			var parseDate = d3.time.format('%Y-%m-%d').parse;
			
			
			// Create SVG
			// ------------------------------
			
			// Container
			var container = d3Container.append('svg');
			
			// SVG element
			var svg = container
			.attr('width', width + margin.left + margin.right)
			.attr('height', height + margin.top + margin.bottom)
			.append("g")
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
			
			
			// Construct chart layout
			// ------------------------------
			
			// Area
			var area = d3.svg.area()
			.x(function(d)
			{
				return x(d.date);
			})
			.y0(height)
			.y1(function(d)
			{
				return y(d.value);
			})
			.interpolate('monotone');
			
			
			// Construct scales
			// ------------------------------
			
			// Horizontal
			var x = d3.time.scale().range([0, width]);
			
			// Vertical
			var y = d3.scale.linear().range([height, 0]);
			
			
			// Load data
			// ------------------------------
			
			d3.json("dashboardData/omzetLaatsteWeken", function(error, data)
			{
				
				// Show what's wrong if error
				if( error ) return console.error(error);
				
				// Pull out values
				data.forEach(function(d)
				{
					d.date = parseDate(d.date);
					d.value = +d.value;
				});
				
				// Get the maximum value in the given array
				var maxY = d3.max(data, function(d)
				{
					return d.value;
				});
				
				// Reset start data for animation
				var startData = data.map(function(datum)
				{
					return {
						date:datum.date,
						value:0
					};
				});
				
				
				// Set input domains
				// ------------------------------
				
				// Horizontal
				x.domain(d3.extent(data, function(d, i)
				{
					return d.date;
				}));
				
				// Vertical
				y.domain([0, d3.max(data, function(d)
				{
					return d.value;
				})]);
				
				
				//
				// Append chart elements
				//
				
				// Add area path
				svg.append("path")
				.datum(data)
				.attr("class", "d3-area")
				.style('fill', color)
				.attr("d", area)
				.transition() // begin animation
				.duration(1000)
				.attrTween('d', function()
				{
					var interpolator = d3.interpolateArray(startData, data);
					return function(t)
					{
						return area(interpolator(t));
					};
				});
				
				
				// Resize chart
				// ------------------------------
				
				// Call function on window resize
				window.addEventListener('resize', messagesAreaResize);
				
				// Call function on sidebar width change
				var sidebarToggle = document.querySelector('.sidebar-control');
				sidebarToggle && sidebarToggle.addEventListener('click', messagesAreaResize);
				
				// Resize function
				//
				// Since D3 doesn't support SVG resize by default,
				// we need to manually specify parts of the graph that need to
				// be updated on window resize
				function messagesAreaResize()
				{
					
					// Layout variables
					width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;
					
					
					// Layout
					// -------------------------
					
					// Main svg width
					container.attr("width", width + margin.left + margin.right);
					
					// Width of appended group
					svg.attr("width", width + margin.left + margin.right);
					
					// Horizontal range
					x.range([0, width]);
					
					
					// Chart elements
					// -------------------------
					
					// Area path
					svg.selectAll('.d3-area').datum(data).attr("d", area);
				}
			});
		}
	};
	
	// Simple bar charts
	var _barChartWidget = function(element, barQty, height, color, tooltip)
	{
		if( typeof d3 == 'undefined' )
		{
			console.warn('Warning - d3.min.js is not loaded.');
			return;
		}
		
		// Initialize chart only if element exsists in the DOM
		if( element )
		{
			
			// Basic setup
			// ------------------------------
			
			// Add data set
			d3.json("dashboardData/omzetLaatsteWeken", function(error, data)
			{
				// Show what's wrong if error
				if( error ) return console.error(error);
				
				// Set input domains
				// ------------------------------
				
				// Horizontal
				x.domain(d3.extent(data, function(d, i)
				{
					return d.maand;
				}));
				
				// Vertical
				y.domain([0, d3.max(data, function(d)
				{
					return d.value;
				})]);
				
				
				console.log(bardata);
				
				// Main variables
				var d3Container = d3.select(element),
					width = d3Container.node().getBoundingClientRect().width;
				
				// Construct scales
				// ------------------------------
				
				// Horizontal
				var x = d3.scale.ordinal()
				.rangeBands([0, width], 0.3);
				
				// Vertical
				var y = d3.scale.linear()
				.range([0, height]);
				
				
				// Set input domains
				// ------------------------------
				
				// Horizontal
				x.domain(d3.range(0, bardata.length));
				
				// Vertical
				y.domain([0, d3.max(bardata)]);
				
				
				// Create chart
				// ------------------------------
				
				// Add svg element
				var container = d3Container.append('svg');
				
				// Add SVG group
				var svg = container
				.attr('width', width)
				.attr('height', height)
				.append('g');
				
				//
				// Append chart elements
				//
				
				// Bars
				var bars = svg.selectAll('rect')
				.data(bardata)
				.enter()
				.append('rect')
				.attr('class', 'd3-random-bars')
				.attr('width', x.rangeBand())
				.attr('x', function(d, i)
				{
					return x(i);
				})
				.style('fill', color);
				
				
				// Tooltip
				// ------------------------------
				
				// Initiate
				var tip = d3.tip()
				.attr('class', 'd3-tip')
				.offset([-10, 0]);
				
				// Show and hide
				bars.call(tip)
				.on('mouseover', tip.show)
				.on('mouseout', tip.hide);
				
				tip.html(function(d, i)
				{
					return "<div class='text-center'>" +
						"<h6 class='mb-0'>" + d + "0" + "</h6>" +
						"<span class='font-size-sm'>members</span>" +
						"</div>";
				});
				
				
				// Bar loading animation
				// ------------------------------
				
				// Choose between animated or static
				withoutAnimation();
				
				
				// Load without animateion
				function withoutAnimation()
				{
					bars
					.attr('height', function(d)
					{
						return y(d);
					})
					.attr('y', function(d)
					{
						return height - y(d);
					});
				}
				
				
				// Resize chart
				// ------------------------------
				
				// Call function on window resize
				window.addEventListener('resize', barsResize);
				
				// Call function on sidebar width change
				var sidebarToggle = document.querySelector('.sidebar-control');
				sidebarToggle && sidebarToggle.addEventListener('click', barsResize);
				
				// Resize function
				//
				// Since D3 doesn't support SVG resize by default,
				// we need to manually specify parts of the graph that need to
				// be updated on window resize
				function barsResize()
				{
					
					// Layout variables
					width = d3Container.node().getBoundingClientRect().width;
					
					
					// Layout
					// -------------------------
					
					// Main svg width
					container.attr("width", width);
					
					// Width of appended group
					svg.attr("width", width);
					
					// Horizontal range
					x.rangeBands([0, width], 0.3);
					
					
					// Chart elements
					// -------------------------
					
					// Bars
					svg.selectAll('.d3-random-bars')
					.attr('width', x.rangeBand())
					.attr('x', function(d, i)
					{
						return x(i);
					});
				}
			});
		}
	};
	
	
	return {
		init:function()
		{
			// _areaChartWidget("#chart_area_color", 50, 'rgba(255,255,255,0.75)');
			_barChartWidget("#chart_bar_color", 24, 50, "rgba(255,255,255,0.75)", "omzet");
		}
	}
}();


// Initialize module
// ------------------------------

// When content loaded
document.addEventListener('DOMContentLoaded', function()
{
	StatisticWidgets.init();
});