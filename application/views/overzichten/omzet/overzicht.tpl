{extends file='../../layout.tpl'}
{block "title"}Omzet & Kosten{/block}
{block "header-icon"}icon-chart{/block}
{block "header-title"}Overzicht - Omzet & Kosten{/block}

{block "content"}
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}

			<!---------------------------------------------------------------------------------------------------------
			|| Grafiek
			---------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-body">

					<div id="chart"></div>
					<script>

						var options = {
							chart: {
								height: 350,
								type: 'line',
								zoom: {
									enabled: false
								}
							},
							dataLabels: {
								enabled: false
							},
							tooltip:{
								shared:true,
							},
							series: [],
							title: {
								text: 'Omzet & Kosten 2020',
							},
							noData: {
								text: 'Gegevens laden...'
							},
							stroke: {
								width: [3, 3, 3, 3],
								curve: 'straight'
							},

							fill: {
								opacity: [1, 1, 1,0.25],
								gradient: {
									inverseColors: false,
									shade: 'light',
									type: "vertical",
									opacityFrom: 0.85,
									opacityTo: 0.55,
									stops: [0, 100, 100, 100]
								}
							}
						};

						var chart = new ApexCharts(document.querySelector("#chart"), options);
						chart.render();

						var url = 'overzichten/omzet/json';

						$.getJSON(url, function(response) {
							chart.updateSeries([{
								name: 'Omzet uitzenden',
								data: response.omzetuitzenden
							},
							{
								name: 'Loonkosten',
								data: response.loonkosten
							},
							{
								name: 'Winst',
								data: response.winst
							},
								{
									name: 'Winst Cummelatief',
									data: response.winstcum,
									type: 'area'
								}
							])
						});


					</script>


				</div>
			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}