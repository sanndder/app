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

					<div id="chart2021"></div>
					<script>

						var options = {
							chart:{
								height:350,
								type:'line',
								zoom:{
									enabled:false
								}
							},
							dataLabels:{
								enabled:false
							},
							tooltip:{
								shared:true,
							},
							series:[],
							title:{
								text:'Omzet & Kosten 2021',
							},
							noData:{
								text:'Gegevens laden...'
							},
							stroke:{
								width:[3, 3, 3, 3, 3],
								curve:'straight'
							},

							fill:{
								opacity:[1, 1, 1, 0.25],
								gradient:{
									inverseColors:false,
									shade:'light',
									type:"vertical",
									opacityFrom:0.85,
									opacityTo:0.55,
									stops:[0, 100, 100, 100]
								}
							}
						};

						var chart2021 = new ApexCharts(document.querySelector("#chart2021"), options);
						chart2021.render();

						var url = 'overzichten/omzet/json/2021';

						$.getJSON(url, function(response)
						{
							chart2021.updateSeries([{
								name:'Omzet uitzenden',
								data:response[2021].omzetuitzenden
							},

								{
									name:'Loonkosten',
									data:response[2021].loonkosten
								},
								{
									name:'Winst',
									data:response[2021].winst
								},
								{
									name:'Winst Cummelatief',
									data:response[2021].winstcum,
									type:'area'
								},
								{
									name:'Omzet Verkoop',
									data:response[2021].omzet
								}
							])

						});


					</script>

				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<div id="chart2020"></div>
					<script>

						var options = {
							chart:{
								height:350,
								type:'line',
								zoom:{
									enabled:false
								}
							},
							dataLabels:{
								enabled:false
							},
							tooltip:{
								shared:true,
							},
							series:[],
							title:{
								text:'Omzet & Kosten 2020',
							},
							noData:{
								text:'Gegevens laden...'
							},
							stroke:{
								width:[3, 3, 3, 3, 3],
								curve:'straight'
							},

							fill:{
								opacity:[1, 1, 1, 0.25],
								gradient:{
									inverseColors:false,
									shade:'light',
									type:"vertical",
									opacityFrom:0.85,
									opacityTo:0.55,
									stops:[0, 100, 100, 100]
								}
							}
						};

						var chart2020 = new ApexCharts(document.querySelector("#chart2020"), options);
						chart2020.render();

						var url = 'overzichten/omzet/json/2020';

						$.getJSON(url, function(response)
						{
							chart2020.updateSeries([{
								name:'Omzet uitzenden',
								data:response[2020].omzetuitzenden
							},

								{
									name:'Loonkosten',
									data:response[2020].loonkosten
								},
								{
									name:'Winst',
									data:response[2020].winst
								},
								{
									name:'Winst Cummelatief',
									data:response[2020].winstcum,
									type:'area'
								},
								{
									name:'Omzet Verkoop',
									data:response[2020].omzet
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