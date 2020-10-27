{extends file='../../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-cash4{/block}
{block "header-title"}Overzicht - Factoring{/block}
{assign "datamask" "true"}
{assign "uploader" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/factoring/overzicht.js?{$time}"></script>
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!---------------------------------------------------------------------------------------------------------
			|| Upload
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-12">

					<div class="card">

						<div class="row">
							<div class="col-md-1">

								<button data-toggle="modal" data-target="#upload_modal" type="button" class="btn bg-primary btn-block btn-float" style="height: 100%">
									<i class="icon-file-plus icon-2x"></i>
									<span>Uploaden</span>
								</button>
							</div>

							<div class="col-md-10">


								<form method="get" action="">
									<div class="row">

										<!----- checkboxes type -------------------------->
										<div class="col-md-2 pb-2">

											<h6 class="mb-2 mt-2">Type</h6>

											<div class="form-check">
												<label class="form-check-label">
													<input name="aankoop" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.get.aankoop)} checked="checked"{/if}>
													Aankoopfacturen
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input name="eind" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.get.eind)} checked="checked"{/if}>
													Eindafrekeningen
												</label>
											</div>
										</div>

										<!----- checkboxes compleet -------------------------->
										<div class="col-md-2 pb-2">

											<h6 class="mb-2 mt-2">Compleet/Incompleet</h6>

											<div class="form-check">
												<label class="form-check-label">
													<input name="compleet" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.get.compleet)} checked="checked"{/if}>
													Compleet
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input name="incompleet" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.get.incompleet)} checked="checked"{/if}>
													Incompleet
												</label>
											</div>
										</div>


										<!----- Zoeken -------------------------->
										<div class="col-md-2">
											<h6 class="mb-1 mt-2">Factuurdetails</h6>

											<table>
												<tr>
													<td>
														<input {if isset($smarty.get.nr)} value="{$smarty.get.nr}" {/if} name="nr" type="text" class="form-control" placeholder="factuur nr" style="padding: 2px 5px; height: auto; width: 100%;">
													</td>
												</tr>
												<tr>
													<td>
														<input {if isset($smarty.get.zoek)} value="{$smarty.get.zoek}" {/if} name="zoek" type="text" class="form-control" placeholder="omschrijving" style="padding: 2px 5px; height: auto; width: 100%;">
													</td>
												</tr>
											</table>
										</div>

										<!----- Zoeken -------------------------->
										<div class="col-md-2">
											<h6 class="mb-1 mt-2">Nummer factoring</h6>

											<table>
												<tr>
													<td>
														<input {if isset($smarty.get.factuur)} value="{$smarty.get.factuur}" {/if} name="factuur" type="text" class="form-control" placeholder="factoring factuur" style="padding: 2px 5px; height: auto; width: 100%;">
													</td>
												</tr>
											</table>
										</div>

										<!----- datum -------------------------->
										<div class="col-md-2">
											<h6 class="mb-1 mt-2">Datum</h6>

											<table>
												<tr>
													<td class="pr-2">Van:</td>
													<td>
														<input {if isset($smarty.get.van)} value="{$smarty.get.van}" {/if} name="van" type="text" class="form-control" data-mask="99-99-9999" placeholder="dd-mm-jjjj" style="padding: 2px 5px; height: auto; width: 85px;">
													</td>
												</tr>
												<tr>
													<td class="pr-2">Tot:</td>
													<td>
														<input {if isset($smarty.get.tot)} value="{$smarty.get.tot}" {/if} name="tot" type="text" class="form-control" data-mask="99-99-9999" placeholder="dd-mm-jjjj" style="padding: 2px 5px; height: auto; width: 85px;">
													</td>
												</tr>
											</table>
										</div>

										<!----- datum -------------------------->
										<div class="col-md-2">
											<button type="submit" class="btn btn-success mt-4">
												<i class="icon-search4 mr-1"></i>Zoeken
											</button>
											<a href="overzichten/factoring/index?aankoop=1&eind=1&compleet=1&incompleet=1" class="btn btn-danger mt-4">
												<i class="icon-cross mr-1"></i>Reset
											</a>
										</div>

									</div>
								</form>
							</div>

						</div>
					</div>
				</div>

			</div>


			<!---------------------------------------------------------------------------------------------------------
			|| Overzicht
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-3">

					<!-- Basic card -->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Bestanden</span>
							<div class="header-elements">
								<span class="badge bg-primary badge-pill">{$aantal}</span>
							</div>
						</div>
						<div class="card-body p-1 overflow-auto" style="max-height: 580px;">

                            {if $bestanden !== NULL}
								<ul class="media-list">
                                    {foreach $bestanden as $b}
										<li id="factuur-{$b.factuur_id}" class="media mt-0 pt-1" {if $b.factuur_id == $factuur_id} style="background-color: #F1F2F3" {/if}>
											<a href="overzichten/factoring/index/{$b.factuur_id}?{$filter}" class="mr-1">
												<i style="font-size: 28px" class="icon-file-pdf mt-1 ml-2 mr-2 mb-2 {if $b.compleet == 1} text-green{/if}"></i>
											</a>
											<div class="media-body">
												<a href="overzichten/factoring/index/{$b.factuur_id}?{$filter}" class="media-title font-weight-semibold {if $b.compleet == 1} text-green{/if}">{$b.file_name_display}</a>
												<div class="font-size-sm text-muted">
                                                    <span class="factuur-datum">
														{if $b.factuur_datum !== NULL}
                                                            {$b.factuur_datum|date_format: '%d-%m-%Y'}
                                                        {/if}
                                                    </span>
													-
													€
													<span class="factuur-totaal">
                                                    {if $b.factuur_totaal !== NULL}
                                                        {$b.factuur_totaal|number_format:2:',':'.'}
                                                    {else}
	                                                    ----,--
                                                    {/if}
													</span>
												</div>
											</div>
										</li>
                                    {/foreach}
								</ul>
                            {else}
								<i>Geen bestanden gevonden</i>
                            {/if}


						</div>
					</div>

				</div>

				<!---------------------------------------------------------------------------------------------------------
				|| Details
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">

                        {if $factuur !== NULL}
							<div class="card-header header-elements-inline">
								<div class="card-title">
									<h3 class="text-primary">
										<i class="icon-checkmark-circle icon-lg text-green" style="{if $factuur.compleet == 0}display: none{/if}"></i>
										{$factuur.file_name_display}
									</h3>
								</div>
								<div class="header-elements">
									<div class="list-icons">
										<a href="overzichten/factoring/index/{$factuur.factuur_id}?delete&{$filter}" class="list-icons-item text-danger" style="margin-top: -30px;" onclick="return confirm('Bestand verwijderen?')">
											<i class="icon-trash icon-lg"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="card-body">

								<input type="hidden" id="factuur_id" value="{$factuur.factuur_id}"/>

								<table>

									<!----- Bestand -------------------------->
									<tr>
										<td class="pr-5">Bestand</td>
										<td colspan="2">
											<a href="overzichten/factoring/view/{$factuur.factuur_id}" target="_blank">
                                                {$factuur.file_name_display}
											</a>
										</td>
									</tr>

									<!----- Aankoopnummer -------------------------->
									<tr>
										<td class="pr-5 pt-2">Aankoopnummer</td>
										<td colspan="2">
                                            {$factuur.factuur_nr}
										</td>
									</tr>

									<!----- Type -------------------------->
									<tr>
										<td class="pr-5 pt-2">Type</td>
										<td colspan="2">
											<input type="hidden" value="{$factuur.factuur_type}" id="factuur_type" />
                                            {if $factuur.factuur_type == 'aankoop'}Aankoopfactuur{/if}
                                            {if $factuur.factuur_type == 'eind'}Eindafrekening{/if}
                                            {if $factuur.factuur_type === NULL}Onbekend{/if}
										</td>
									</tr>

									<!----- Factuurdatum -------------------------->
									<tr>
										<td class="pr-5 pt-2">Factuurdatum</td>
										<td style="width: 125px">
											<div class="factuur-datum input-value" {if $factuur.factuur_datum === NULL}style="display: none"{/if}>
                                                {$factuur.factuur_datum|date_format: '%d-%m-%Y'}
												<i class="edit icon-pencil5 ml-2" style="cursor: pointer; color: #999"></i>
											</div>
											<div class="input-group" {if $factuur.factuur_datum !== NULL}style="display: none"{/if}>
												<input name="factuur_datum" type="text" {if $factuur.factuur_datum !== NULL} value="{$factuur.factuur_datum|date_format: '%d-%m-%Y'}" {/if} class="form-control" placeholder="dd-mm-jjjj" data-mask="99-99-9999" style="width: 125px;">
											</div>
										</td>
										<td class="status" style="width: 125px;">
											<i></i>
										</td>
									</tr>

									<!----- Totaalbedrag -------------------------->
									<tr>
										<td class="pr-5 pt-2">Totaalbedrag</td>
										<td style="width: 125px">

											<div class="factuur-totaal input-value" {if $factuur.factuur_totaal === NULL}style="display: none"{/if}>
												€ {$factuur.factuur_totaal|number_format:2:',':'.'}
												<i class="edit icon-pencil5 ml-2" style="cursor: pointer; color: #999"></i>
											</div>
											<div class="input-group" {if $factuur.factuur_totaal !== NULL}style="display: none"{/if}>
												<input name="factuur_totaal" {if $factuur.factuur_totaal !== NULL} value="{$factuur.factuur_totaal|number_format:2:',':'.'}" {/if} type="text" class="form-control" style="width: 125px;">
											</div>
										</td>
										<td class="status" style="width: 125px;">
											<i></i>
										</td>
									</tr>

									<!----- Regeltotaal -------------------------->
									<tr>
										<td class="pr-5 pt-2">Regeltotaal</td>
										<td style="width: 125px" class="regel-totaal">
											€ <span>{$regeltotaal|number_format:2:',':'.'}</span>
											<i style="display: none" class="spinner icon-spinner3"></i>
										</td>
										<td class="status" style="width: 125px;">

										</td>
									</tr>
								</table>


								<!-------------- Factuurregels toevoegen--------------------------------------------------------------------->
								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factuurregels toevoegen</legend>
								</fieldset>

								<div class="alert alert-warning alert-styled-left alert-arrow-left alert-dismissible" style="display: none">
								</div>

								<form method="post" action="" class="form-regels">
									<table>
										<thead>
											<tr>
												<th class="pr-3">Factuur nr.</th>
												<th class="pr-3">Omschrijving</th>
												<th class="pr-3">Bedrag</th>
												<th class="pr-3">Kosten</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="pr-3">
													<input type="text" class="form-control" autocomplete="off" name="factuur_nr" style="width: 75px">
												</td>
												<td class="pr-3" style="width: 50%">
													<input type="text" class="form-control" name="omschrijving" style="width: 100%">
												</td>
												<td class="pr-3">
													<input type="text" class="form-control" name="bedrag" style="width: 105px">
												</td>
												<td class="pr-1">
													<input type="text" class="form-control" name="kosten" style="width: 105px">
												</td>
												<td>
													<button name="addRegel" class="btn btn-success" type="button">
														<i class="icon-check"></i>
													</button>
												</td>
											</tr>
										</tbody>
									</table>
								</form>

								<!-------------- Factuurregels--------------------------------------------------------------------->
								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factuurregels</legend>
								</fieldset>

								<table class="table-factuur-regels">
									<thead>
										<tr>
											<th></th>
											<th class="pr-4">Factuur nr.</th>
											<th class="pr-4">Omschrijving</th>
											<th class="pr-4">Bedrag</th>
											<th class="pr-4">Kosten</th>
										</tr>
									</thead>
									<tbody style="display: none">

									</tbody>
									<tfoot>
										<tr>
											<td colspan="5" class="pt-2">
												<i class="spinner icon-spinner3 mr-2"></i>Factuur regels laden.....
											</td>
										</tr>
									</tfoot>
								</table>

							</div>
							<!-- /card body-->


                        {else}
							<i class="p-3">Selecteer een bestand uit de lijst</i>
                        {/if}
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->


		</div>
		<!-- /content area -->
	</div>
	<!-- /main content -->


	<!---------------------------------------------------------------------------------------------------------
	|| Upload modal
	---------------------------------------------------------------------------------------------------------->
	<div id="upload_modal" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Upload bestanden</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body pt-4">

					<script>
                        {literal}
						$(document).ready(function()
						{
							$('#fileupload').fileinput('refresh', {
								uploadUrl:'upload/factoringsbestanden',
								showPreview: false,
								allowedFileExtensions: ['pdf','PDF']
							});
							$('#fileupload').on('fileuploaded', function()
							{
								window.location.reload();
							});
						});
                        {/literal}
					</script>

					<form action="#">
						<input name="file" type="file" id="fileupload" class="file-input" multiple>
					</form>


				</div>
				<div class="modal-footer">


				</div>
			</div>
		</div>
	</div>
{/block}