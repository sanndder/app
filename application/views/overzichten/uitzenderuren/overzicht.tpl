{extends file='../../layout.tpl'}
{block "title"}Uitzenders uren{/block}
{block "header-icon"}icon-stats-bars{/block}
{block "header-title"}Overzicht - Uitzenders uren{/block}

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
			{if $uitzender_uren != NULL}
				{foreach $uitzender_uren as $u}

					<div class="row mb-3">
						<div class="col-xl-2 offset-lg-2">

							<div class="card h-100">
								<div class="card-body">
									<span class="text-primary font-weight-bold">{$uitzenders[$u@key].bedrijfsnaam}</span>
								</div>
							</div>
						</div><!-- /col -->


						<div class="col-xl-6">

							<div class="card h-100">
								<div class="card-body">
									<div id="chart-{$u@key}"></div>
									<script>

										var options = {
											chart:{
												height:350,
												type: 'bar',
												zoom:{
													enabled:false
												}},
												series: [
													{
														name: "Uren",
														data: {$u.string},
													}],
											dataLabels:{
												enabled:false
											},
											xaxis: {
												categories: {$x_as},
												labels: {
													formatter: function(value) {
														return value;
													}
												}
											},
											yaxis: [ {
												title:{
													text:'Gewerkte uren'
												},
												forceNiceScale: true,
												decimalsInFloat: 0
											}],
											title:{
												text:'Gewerkte uren',
											},
											noData:{
												text:'Gegevens laden...'
											},
										};

										var chart = new ApexCharts(document.querySelector("#chart-{$u@key}"), options);
										chart.render();

									</script>
								</div>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->

                {/foreach}
			{/if}

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}