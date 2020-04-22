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
							series: [],
							title: {
								text: 'Omzet & Kosten 2020',
							},
							noData: {
								text: 'Gegevens laden...'
							},
							stroke: {
								width: [3, 3, 3],
								curve: 'straight'
							},
						};

						var chart = new ApexCharts(document.querySelector("#chart"), options);
						chart.render();

						var url = 'overzichten/omzet/json';

						$.getJSON(url, function(response) {
							chart.updateSeries([{
								name: 'Omzet',
								data: response.omzet
							},
								{
									name: 'Kosten',
									data: response.kosten
								}])
						});


					</script>


				</div>
			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}