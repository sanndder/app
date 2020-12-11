{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-calculator2{/block}
{block "header-title"}Proforma{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">
			<div class="row">
				<div class="col-md-9">
                    {if isset($msg)}{$msg}{/if}
				</div>
			</div>

			<form method="post" action="">

				<div class="row">

					<!-----------------------------------------------------------------------------------------------------------------------------------------------------
					// instellingen
					------------------------------------------------------------------------------------------------------------------------------------------------------>
					<div class="col-lg-4 col-xl-3">
						<div class="card">

							<div class="card-header bg-blue font-weight-semibold header-elements-sm-inline" style="text-transform: capitalize; font-size: 16px; padding: 10px 12px">
								Instellingen
							</div>

							<div class="card-body">

								<table>
									<tr>
										<td>Tijdvak</td>
										<td>
											<select name="tijdvak" class="form-control form-control-sm" readonly="">
												<option value="w">week</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Uren werkweek</td>
										<td>
											<select name="uren_werkweek" class="form-control form-control-sm">
												<option value="40" {if isset($smarty.post.uren_werkweek) && $smarty.post.uren_werkweek == '40'} selected{/if}>40</option>
												<option value="38" {if isset($smarty.post.uren_werkweek) && $smarty.post.uren_werkweek == '38'} selected{/if}>38</option>
												<option value="36" {if isset($smarty.post.uren_werkweek) && $smarty.post.uren_werkweek == '36'} selected{/if}>36</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="pt-3">Kilometers</td>
										<td class="pt-3">
											<input name="kilometers" value="{if isset($smarty.post.kilometers)}{$smarty.post.kilometers}{else}0{/if}" type="text" class="form-control form-control-sm"/>
										</td>
									</tr>
									<tr>
										<td class="pt-3 pr-3">Vakantiegeld direct uitkeren</td>
										<td class="pt-3">
											<div class="form-check">
												<label class="form-check-label">
													<input name="vakantiegeld_direct" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.post.vakantiegeld_direct) && $smarty.post.vakantiegeld_direct == '1'}checked="checked"{/if}>
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td class="pt-3 pr-3">Bovenwettelijke vakantieuren <br/> direct uitkeren</td>
										<td class="pt-3" style="vertical-align: text-top">
											<div class="form-check">
												<label class="form-check-label">
													<input name="vakantieuren_direct" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.post.vakantieuren_direct) && $smarty.post.vakantieuren_direct == '1'}checked="checked"{/if}>
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td class="pt-3 pr-3">Omslagfactor</td>
										<td class="pt-3">
											<input name="omslagfactor" value="{if isset($smarty.post.omslagfactor)}{$smarty.post.omslagfactor}{else}1.720{/if}" type="text" class="form-control form-control-sm"/>
										</td>
									</tr>
								</table>

							</div><!-- /card-body -->
						</div><!-- /card -->

					</div><!-- /col -->

					<!-----------------------------------------------------------------------------------------------------------------------------------------------------
					// Invoer
					------------------------------------------------------------------------------------------------------------------------------------------------------>
					<div class="col-lg-4 col-xl-3">
						<div class="card">

							<div class="card-header bg-blue font-weight-semibold header-elements-sm-inline" style="text-transform: capitalize; font-size: 16px; padding: 10px 12px">
								Invoer
							</div>

							<div class="card-body">

								<fieldset class="">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bruto
										<i class="icon-arrow-right5 mr-1"></i>Netto
									</legend>
								</fieldset>

								<table>
									<tr>
										<td class="pt-1 pr-2">
											<label class="form-check-label">
												<span>
													<input value="bruto_uur" type="radio" class="form-input-styled" name="invoertype" {if isset($smarty.post.invoertype) && $smarty.post.invoertype == 'bruto_uur'} checked{/if}>
												</span>
											</label>
										</td>
										<td class="pr-4">Bruto uurloon</td>
										<td class="">
											<input style="width: 100px;text-align: right" name="bruto_uur" value="{if isset($smarty.post.bruto_uur)}{$smarty.post.bruto_uur}{/if}" type="text" class="input-invoer form-control form-control-sm"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2 pr-2">
											<label class="form-check-label pt-1">
												<span>
													<input value="bruto_totaal" type="radio" class="form-input-styled" name="invoertype" {if isset($smarty.post.invoertype) && $smarty.post.invoertype == 'bruto_totaal'} checked{/if}>
												</span>
											</label>
										</td>
										<td class="pr-4 pt-2">Bruto per week</td>
										<td class="pt-2">
											<input style="width: 100px;text-align: right" name="bruto_totaal" value="{if isset($smarty.post.bruto_totaal)}{$smarty.post.bruto_totaal}{/if}" type="text" class="input-invoer form-control form-control-sm"/>
										</td>
									</tr>
								</table>


								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold">Netto
										<i class="icon-arrow-right5 mr-1"></i>Bruto
									</legend>
								</fieldset>

								<table>
									<tr>
										<td class="pt-1 pr-2">
											<label class="form-check-label">
												<span>
													<input value="netto_uur" type="radio" class="form-input-styled" name="invoertype" {if isset($smarty.post.invoertype) && $smarty.post.invoertype == 'netto_uur'} checked{/if}>
												</span>
											</label>
										</td>
										<td class="pr-4">Netto uurloon</td>
										<td class="">
											<input style="width: 100px;text-align: right" name="netto_uur" value="{if isset($smarty.post.netto_uur)}{$smarty.post.netto_uur}{/if}" type="text" class="input-invoer form-control form-control-sm"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2 pr-2">
											<label class="form-check-label pt-1">
												<span>
													<input value="netto_totaal" type="radio" class="form-input-styled" name="invoertype" {if isset($smarty.post.invoertype) && $smarty.post.invoertype == 'netto_totaal'} checked{/if}>
												</span>
											</label>
										</td>
										<td class="pr-4 pt-2">Netto per week</td>
										<td class="pt-2">
											<input style="width: 100px;text-align: right" name="netto_totaal" value="{if isset($smarty.post.netto_totaal)}{$smarty.post.netto_totaal}{/if}" type="text" class="input-invoer form-control form-control-sm"/>
										</td>
									</tr>
								</table>

								<button name="bereken" type="submit" class="btn btn-outline-success mt-4" style="width: 100%;">
									<i class="icon-calculator mr-1"></i> bereken resultaat
								</button>

							</div><!-- /card-body -->
						</div><!-- /card -->

					</div><!-- /col -->


					<!-----------------------------------------------------------------------------------------------------------------------------------------------------
					// Output
					------------------------------------------------------------------------------------------------------------------------------------------------------>
					<div class="col-lg-4 col-xl-3">
						<div class="card">

							<div class="card-header bg-success font-weight-semibold header-elements-sm-inline" style="text-transform: capitalize; font-size: 16px; padding: 10px 12px">
								Resultaat
							</div>

							<div class="card-body">

								<table>
									<tr>
										<td class="pr-4">Totaal bruto loon</td>
										<td class="text-right">{if isset($result.bruto_totaal)}€ {$result.bruto_totaal|number_format:2:',':'.'}{/if}</td>
									</tr>
									<tr>
										<td class="pr-4">Bruto uurloon</td>
										<td class="text-right">{if isset($result.bruto_uur)}€ {$result.bruto_uur|number_format:2:',':'.'}{/if}</td>
									</tr>
									<tr>
										<td colspan="2" style="height: 25px"></td>
									</tr>
									<tr>
										<td class="pr-4">Totaal netto loon</td>
										<td class="text-right">{if isset($result.netto_totaal)}€ {$result.netto_totaal|number_format:2:',':'.'}{/if}</td>
									</tr>
									<tr>
										<td class="pr-4">Netto uurloon</td>
										<td class="text-right">{if isset($result.netto_uur)}€ {$result.netto_uur|number_format:2:',':'.'}{/if}</td>
									</tr>
									<tr>
										<td colspan="2" style="height: 25px"></td>
									</tr>
									<tr>
										<td class="pr-4">Kostprijs per uur</td>
										<td class="text-right">{if isset($result.kostprijs_uur)}€ {$result.kostprijs_uur|number_format:2:',':'.'}{/if}</td>
									</tr>
									<tr>
										<td class="pr-4">Kostprijs per uur incl. km</td>
										<td class="text-right">{if isset($result.kostprijs_uur_km)}€ {$result.kostprijs_uur_km|number_format:2:',':'.'}{/if}</td>
									</tr>
								</table>


							</div><!-- /card-body -->
						</div><!-- /card -->

					</div><!-- /col -->

				</div>
			</form>


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
	<script>
        {literal}
		$('.input-invoer').on('focus', function()
		{
			$tr = $(this).closest('tr');
			$('.uniform-choice span').removeClass('checked');
			$tr.find('.uniform-choice span').addClass('checked');
			$tr.find('.form-input-styled').prop('checked', true);
		});

        {/literal}
	</script>
{/block}