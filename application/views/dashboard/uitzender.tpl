{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Dashboard{/block}

{block "content"}
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-3">

					<!--------------------------------------------------------------------------- snelknoppen ------------------------------------------------->
					<div class="card">
						<div class="card-body">

							<div class="row">
								<div class="col">
									<button style="width: 140px" type="button" class="btn btn-teal btn-block btn-float" data-toggle="modal" data-target="#modal_werknemers_ziekmelding">
										<i class="icon-folder-plus2 icon-2x"></i>
										<span>Ziekmelding</span>
									</button>
								</div>

							</div>

						</div>
					</div>

					<!--------------------------------------------------------------------------- aantallen ------------------------------------------------->
					<div class="card">
						<div class="card-body">

                            {* inleners *}
							<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
								<a href="crm/inleners" class="text-default">
									<div class="rounded-circle bg-warning-400">
										<i class="icon-user-tie icon-xl text-white p-2"></i>
									</div>
								</a>
								<a href="crm/inleners" class="text-default">
									<div class="ml-3">
										<h5 class="font-weight-semibold mb-0">{$count_inleners}</h5>
										<span class="text-muted text-uppercase">Inleners</span>
									</div>
								</a>
							</div>

                            {* werknemers of zzp'ers *}
                            {if $werkgever_type == 'uitzenden'}
								<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
									<a href="crm/werknemers" class="text-default">
										<div class="rounded-circle bg-blue">
											<i class="icon-user icon-xl text-white p-2"></i>
										</div>
									</a>
									<a href="crm/werknemers" class="text-default">
										<div class="ml-3">
											<h5 class="font-weight-semibold mb-0">{$count_werknemers}</h5>
											<span class="text-muted text-uppercase">Werknemers</span>
										</div>
									</a>
								</div>
                            {/if}

                            {if $werkgever_type == 'bemiddeling'}
								<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
									<a href="crm/zzp" class="text-default">
										<div class="rounded-circle bg-blue">
											<i class="icon-user icon-xl text-white p-2"></i>
										</div>
									</a>
									<a href="crm/zzp" class="text-default">
										<div class="ml-3">
											<h5 class="font-weight-semibold mb-0">{$count_zzp}</h5>
											<span class="text-muted text-uppercase">ZZp'ers</span>
										</div>
									</a>
								</div>
                            {/if}
						</div>
					</div>

					<!----------------- Documenten --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Documenten FlexxOffice</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>

						<div class="card-body">

							<ul class="media-list">

								<li class="media">
									<div class="mr-2 align-self-center">
										<img src="recources/img/icons/pdf.svg" style="height: 25px">
									</div>

									<div class="media-body">
										<div class="font-weight-semibold">
											<a href="{$base_url}/documenten/pdf/av" target="_blank">
												Algemene voorwaarden
											</a>
										</div>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="{$base_url}/documenten/pdf/av/download" class="list-icons-item" target="_blank">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>

                                {if $werkgever_type == 'uitzenden'}
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/g-rekening.pdf" target="_blank">
													verklaring g-rekening
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/g-rekening.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/kvkuitzenden.pdf" target="_blank">
													Uittreksel KvK
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/kvkuitzenden.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/nbbu.pdf" target="_blank">
													NBBU lidmaatschap
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/nbbu.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/nen.pdf" target="_blank">
													NEN certtificaat
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/nen.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/betalinguitzenden.pdf" target="_blank">
													verklaring betalingsgedrag
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/betalinguitzenden.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
                                {/if}
                                {if $werkgever_type == 'bemiddeling'}
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf" target="_blank">
													Uittreksel KvK
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf" target="_blank">
													verklaring betalingsgedrag
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
                                {/if}
							</ul>
						</div>
					</div>


				</div>
				<!--------------------------------------------------------------------------- /left ------------------------------------------------->


				<!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Marge & Uren</legend>

							<div id="chart"></div>
							<script>

								var options = {
									chart:{
										height:350,
										zoom:{
											enabled:false
										}
									},
                                    colors: ['#00ABE9', '#002B61'],
									series: [{
										name: "Marge",
										data: {$data_marge},
										type: 'column',
									},
										{
											name: "Uren",
											data: {$data_uren},
											type: 'column',
										}],
									dataLabels:{
										enabled:false
									},
									tooltip:{
										shared:true,
										x:{
											show:true,
										}
									},
									xaxis: {
										categories: {$x_as},
										labels: {
											formatter: function(value) {
												return 'week ' + value;
											}
										}
									},
									yaxis: [{
										title: {
											text: 'Marge (€)',
										},
										decimalsInFloat: 0
									}, {
										opposite:true,
										title:{
											text:'Gewerkte uren'
										},
										forceNiceScale: true,
										decimalsInFloat: 0
									}],

									noData:{
										text:'Gegevens laden...'
									},
									stroke:{
										width:[2],
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

								var chart = new ApexCharts(document.querySelector("#chart"), options);
								chart.render();

							</script>


						</div>
					</div>


					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Top 5 marge inleners</legend>
							</fieldset>

							<table style="width: 100%;">
                                {foreach $top5 as $inlener}
									<tr>
										<td style="width: 20%" class="pl-2 py-1">{$inlener.inlener}</td>
										<td>
											<div style="width:{$inlener.percentage}%; height: 15px; float: left; background-color:#4CAF50"></div>
											<div style="margin-left:6px; float: left;">€ {$inlener.marge|number_format:2:',':'.'} ({$inlener.percentage|number_format:2:',':'.'}%)</div>
										</td>
									</tr>
                                {/foreach}
							</table>

						</div><!-- /card body -->
					</div><!-- /basic card -->

				</div>
				<!-- /col -->
			</div><!-- /row -->
		      <!--------------------------------------------------------------------------- /right ------------------------------------------------->


			<div id="modal_werknemers_ziekmelding" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Selecteer een werknemer om ziek te melden <span class="var-action"></span></h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form method="post" action="">

							<div class="modal-body pt-4">


								{if $werknemers != NULL}
									<select class="form-control list-werknemers">
										<option></option>
		                                {foreach $werknemers as $w}
			                                <option value="{$w@key}">{$w@key} - {$w}</option>
		                                {/foreach}
									</select>
                                {/if}

							</div>

							<div class="modal-footer">
								<a href="" onclick="return verderZiekmelding()" data-link="crm/werknemers/dossier/ziekmelding/" class="btn bg-primary btn-verder">
									<i class="icon-arrow-right5 mr-1"></i>Verder
								</a>
								<button type="button" class="btn btn-link" data-dismiss="modal">Annuleren</button>
							</div>

						</form>

						{literal}
						<script>
							function verderZiekmelding()
							{
								$btn = $('.btn-verder');
								id = $('.list-werknemers').val();

								if( id == '' )
									return false;

								$btn.attr('href', $btn.data('link') +  id );
								return true;
							}

						</script>
						{/literal}
					</div>
				</div>
			</div>


		</div>
		<!-- /content area -->
	</div>
	<!-- /main content -->


{/block}