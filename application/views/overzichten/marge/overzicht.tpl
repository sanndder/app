{extends file='../../layout.tpl'}
{block "title"}Omzet & Kosten{/block}
{block "header-icon"}icon-chart{/block}
{block "header-title"}Overzicht - Marge & Uren{/block}

{block "content"}
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="template/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script>
		var BootstrapMultiselect = function()
		{

			// Default file input style
			var _componentMultiselect = function()
			{
				// Basic initialization
				$('.multiselect').multiselect({
					nonSelectedText:'Selecteer minstens één inlener',
					allSelectedText:'Alle inleners',
					selectAllText:'Alles selecteren',
					nSelectedText:'inleners',
					includeSelectAllOption:true
				});

			};

			return {
				init:function()
				{
					_componentMultiselect();
				}
			}
		}();

		document.addEventListener('DOMContentLoaded', function()
		{
			BootstrapMultiselect.init();
		});

	</script>
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}


			<div class="row">

				<!---------------------------------------------------------------------------------------------------------
				|| Instellingen
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<div class="card">
						<div class="card-body">

							<form method="get">

								<!---- Type ---->
								{*
								<div class="form-group">
									<label class="font-size-lg text-primary font-weight-bold">Weergave instellingen</label>
									<div class="form-check">
										<label class="form-check-label">
											<input name="toon_marge" value="1" type="checkbox" class="form-input-styled-primary" checked>
										</label>
										Marge tonen in grafiek
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<input name="toon_uren" value="1" type="checkbox" class="form-input-styled-primary" checked>
										</label>
										Uren tonen in grafiek
									</div>
								</div>
								*}

								<!---- Type ---->
								{*
								<div class="form-group">
									<label class="font-size-lg text-primary font-weight-bold">Specificatie tonen voor</label>
									<div class="form-check">
										<label class="form-check-label">
											<span class="checked">
												<input value="inlener" type="radio" class="form-input-styled-primary" name="set_split" {if !isset($smarty.get)}{/if}checked="">
											</span>
											Inleners
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<span>
												<input value="werknemer" type="radio" class="form-input-styled-primary" name="set_split">
											</span>
											Werknemers
										</label>
									</div>
								</div>*}

								<!---- Jaar ---->
								<div class="form-group mt-1">
									<label class="font-size-lg text-primary font-weight-bold">Jaar</label>
									<select name="set_jaar" class="form-control">
                                        {if isset($info_jaren) && count($info_jaren) > 0}
                                            {foreach $info_jaren as $j}
												<option value="{$j}">{$j}</option>
                                            {/foreach}
                                        {/if}
									</select>
								</div>


								<!---- Inleners ---->
								<div class="form-group mt-4">
									<label class="font-size-lg text-primary font-weight-bold">Inleners</label>

									<select name="set_inleners[]" class="multiselect multiselect-marge-inleners form-control" multiple style="border: 0; padding-left: 0; padding-top: 5px">
                                        {if isset($info_inleners) && count($info_inleners) > 0}
                                            {foreach $info_inleners as $i}
												<option {if !isset($smarty.get.set_inleners) || in_array($i@key, $smarty.get.set_inleners)  } selected{/if} class="pl-1" value="{$i@key}">{$i}</option>
                                            {/foreach}
                                        {/if}
									</select>

								</div>

								<button type="submit" class="btn btn-sm btn-primary">
									<i class="icon-chart mr-1"></i> Gegevens tonen
								</button>

							</form>

						</div>
					</div>

				</div><!-- /col -->


				<!---------------------------------------------------------------------------------------------------------
				|| Grafiek
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-9">

					<div class="card">
						<div class="card-body">

							<div id="chart"></div>
							<script>

								var options = {
									chart:{
										height:350,
										zoom:{
											enabled:false
										}
									},
									series: [{
										name: "Marge",
										data: {$data_marge},
										type: 'line',
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

									title:{
										text:'Marge & Uren {$set_jaar}',
									},
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

					<!---------------------------------------------------------------------------------------------------------
					|| Data inleners
					---------------------------------------------------------------------------------------------------------->
					<div class="card"  style="overflow-x: auto ">
						<div class="card-body">

							<table style="width: 100%;">
								<tr>
									<td colspan="2" class="bg-primary pl-2 py-1" style="font-size: 1.1em">Top 5 marge - Inleners</td>
								</tr>
								<tr><td colspan="2" style="height: 10px;"></td></tr>
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

						</div>
					</div>


					<!---------------------------------------------------------------------------------------------------------
					|| Data inleners
					---------------------------------------------------------------------------------------------------------->
					<div class="card"  style="overflow-x: auto ">
						<div class="card-body">


							<table style="width: 100%;">
								<tr>
									<td colspan="2" class="bg-primary pl-2 py-1" style="font-size: 1.1em">Data per week - Inleners</td>
								</tr>
								{foreach $data_marge_inleners as $inlener}
									<tr>
										<td class="pl-2 pt-2 text-primary font-weight-bolder" style="vertical-align: text-top; border-bottom: 2px solid #2196f3">{$inlener.inlener}</td>
										<td class="pb-3 pt-2" style=" border-bottom: 2px solid #2196f3"">

											<table style="width: 100%" class="marge-data-per-week">
												<tr>
                                                    <td class="td-week pl-1 pr-2">week</td>
													{for $w=1 to 18}<td style="width: 5.5%;" class="text-center td-week">{$w}</td>{/for}
												</tr>
												<tr>
													<td class="pl-1 pr-2">marge</td>
                                                    {for $w=1 to 18}
														<td style="width: 5.5%" class="text-center td-data">
															{if $inlener.weken.$w > 0}
																{$inlener.weken.$w|number_format:2:',':'.'}
															{else}
																-
															{/if}
														</td>
                                                    {/for}
												</tr>
												<tr>
													<td class="pl-1 pr-2">uren</td>
                                                    {for $w=1 to 18}
														<td style="width: 5.5%" class="text-center td-data">
                                                            {if $data_uren_inleners[$inlener@key].weken.$w > 0}
                                                                {$data_uren_inleners[$inlener@key].weken.$w}
                                                            {else}
																-
                                                            {/if}
														</td>
                                                    {/for}
												</tr>
											</table>

											<table style="width: 100%; margin-top: 15px;" class="marge-data-per-week">
												<tr>
													<td class="td-week pl-1 pr-2">week</td>
                                                    {for $w=19 to 36}
														<td style="width: 5.5%" class="text-center td-week">{$w}</td>
                                                    {/for}
												</tr>
												<tr>
													<td class="pl-1 pr-2">marge</td>
                                                    {for $w=19 to 36}
														<td style="width: 5.5%" class="text-center td-data">
                                                            {if $inlener.weken.$w > 0}
                                                                {$inlener.weken.$w|number_format:2:',':'.'}
                                                            {else}
																-
                                                            {/if}
														</td>
                                                    {/for}
												</tr>
												<tr>
													<td class="pl-1 pr-2">uren</td>
                                                    {for $w=19 to 36}
														<td style="width: 5.5%" class="text-center td-data">
                                                            {if $data_uren_inleners[$inlener@key].weken.$w > 0}
                                                                {$data_uren_inleners[$inlener@key].weken.$w}
                                                            {else}
																-
                                                            {/if}
														</td>
                                                    {/for}
												</tr>
											</table>

											<table style="width: 100%; margin-top: 15px;" class="marge-data-per-week">
												<tr>
													<td class="td-week pl-1 pr-2">week</td>
                                                    {for $w=37 to 54}
	                                                    <td style="width: 5.5%" class="text-center td-week">{if $w != 54}{$w}{else}<span style="font-weight: bold">totaal</span>{/if}</td>
                                                    {/for}
												</tr>
												<tr>
													<td class="pl-1 pr-2">marge</td>
                                                    {for $w=37 to 54}
														<td style="width: 5.5%" class="text-center td-data">
                                                            {if isset($inlener.weken.$w)}
																{if $inlener.weken.$w > 0}
	                                                                {$inlener.weken.$w|number_format:2:',':'.'}
	                                                            {else}
																	-
	                                                            {/if}
                                                            {else}
	                                                            {if $w == 54}<span style="font-weight: bold">{$data_marge_inleners[$inlener@key].totaal|number_format:2:',':'.'}</span>{/if}
                                                            {/if}
														</td>
                                                    {/for}
												</tr>
												<tr>
													<td class="pl-1 pr-2">uren</td>
                                                    {for $w=37 to 54}
														<td style="width: 5.5%" class="text-center td-data">
                                                            {if isset($inlener.weken.$w)}
	                                                            {if $data_uren_inleners[$inlener@key].weken.$w > 0}
	                                                                {$data_uren_inleners[$inlener@key].weken.$w}
	                                                            {else}
																	-
	                                                            {/if}
	                                                        {else}
	                                                            {if $w == 54}<span style="font-weight: bold">{$data_uren_inleners[$inlener@key].totaal}</span>{/if}
                                                            {/if}
														</td>
                                                    {/for}
												</tr>
											</table>

										</td>
									</tr>
								{/foreach}
							</table>

						</div>
					</div>


					<!---------------------------------------------------------------------------------------------------------
					|| Data werknemers
					---------------------------------------------------------------------------------------------------------->
					<div class="card"  style="overflow-x: auto ">
						<div class="card-body">


							<table style="width: 100%;">
								<tr>
									<td colspan="2" class="bg-primary pl-2 py-1" style="font-size: 1.1em">Data per week - Werknemers</td>
								</tr>
                                {foreach $data_marge_werknemers as $werknemer}
									<tr>
										<td class="pl-2 pt-2 text-primary font-weight-bolder" style="vertical-align: text-top; border-bottom: 2px solid #2196f3">{$werknemer.werknemer}</td>
										<td class="pb-3 pt-2" style=" border-bottom: 2px solid #2196f3"">

										<table style="width: 100%" class="marge-data-per-week">
											<tr>
												<td class="td-week pl-1 pr-2">week</td>
                                                {for $w=1 to 18}<td style="width: 5.5%;" class="text-center td-week">{$w}</td>{/for}
											</tr>
											<tr>
												<td class="pl-1 pr-2">marge</td>
                                                {for $w=1 to 18}
													<td style="width: 5.5%" class="text-center td-data">
                                                        {if $werknemer.weken.$w > 0}
                                                            {$werknemer.weken.$w|number_format:2:',':'.'}
                                                        {else}
															-
                                                        {/if}
													</td>
                                                {/for}
											</tr>
											<tr>
												<td class="pl-1 pr-2">uren</td>
                                                {for $w=1 to 18}
													<td style="width: 5.5%" class="text-center td-data">
                                                        {if $data_uren_werknemers[$werknemer@key].weken.$w > 0}
                                                            {$data_uren_werknemers[$werknemer@key].weken.$w}
                                                        {else}
															-
                                                        {/if}
													</td>
                                                {/for}
											</tr>
										</table>

										<table style="width: 100%; margin-top: 15px;" class="marge-data-per-week">
											<tr>
												<td class="td-week pl-1 pr-2">week</td>
                                                {for $w=19 to 36}
													<td style="width: 5.5%" class="text-center td-week">{$w}</td>
                                                {/for}
											</tr>
											<tr>
												<td class="pl-1 pr-2">marge</td>
                                                {for $w=19 to 36}
													<td style="width: 5.5%" class="text-center td-data">
                                                        {if $werknemer.weken.$w > 0}
                                                            {$werknemer.weken.$w|number_format:2:',':'.'}
                                                        {else}
															-
                                                        {/if}
													</td>
                                                {/for}
											</tr>
											<tr>
												<td class="pl-1 pr-2">uren</td>
                                                {for $w=19 to 36}
													<td style="width: 5.5%" class="text-center td-data">
                                                        {if $data_uren_werknemers[$werknemer@key].weken.$w > 0}
                                                            {$data_uren_werknemers[$werknemer@key].weken.$w}
                                                        {else}
															-
                                                        {/if}
													</td>
                                                {/for}
											</tr>
										</table>

										<table style="width: 100%; margin-top: 15px;" class="marge-data-per-week">
											<tr>
												<td class="td-week pl-1 pr-2">week</td>
                                                {for $w=37 to 54}
													<td style="width: 5.5%" class="text-center td-week">{if $w != 54}{$w}{else}<span style="font-weight: bold">totaal</span>{/if}</td>
                                                {/for}
											</tr>
											<tr>
												<td class="pl-1 pr-2">marge</td>
                                                {for $w=37 to 54}
													<td style="width: 5.5%" class="text-center td-data">
                                                        {if isset($werknemer.weken.$w)}
                                                            {if $werknemer.weken.$w > 0}
                                                                {$werknemer.weken.$w|number_format:2:',':'.'}
                                                            {else}
																-
                                                            {/if}
                                                        {else}
                                                            {if $w == 54}<span style="font-weight: bold">{$data_marge_werknemers[$werknemer@key].totaal|number_format:2:',':'.'}</span>{/if}
                                                        {/if}
													</td>
                                                {/for}
											</tr>
											<tr>
												<td class="pl-1 pr-2">uren</td>
                                                {for $w=37 to 54}
													<td style="width: 5.5%" class="text-center td-data">
                                                        {if isset($werknemer.weken.$w)}
                                                            {if $data_uren_werknemers[$werknemer@key].weken.$w > 0}
                                                                {$data_uren_werknemers[$werknemer@key].weken.$w}
                                                            {else}
																-
                                                            {/if}
                                                        {else}
                                                            {if $w == 54}<span style="font-weight: bold">{$data_uren_werknemers[$werknemer@key].totaal}</span>{/if}
                                                        {/if}
													</td>
                                                {/for}
											</tr>
										</table>

										</td>
									</tr>
                                {/foreach}
							</table>

						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}