{extends file='../../layout.tpl'}
{block "title"}Banktransacties{/block}
{block "header-icon"}icon-list2{/block}
{block "header-title"}Overzicht - Banktransacties{/block}


{assign "datamask" "true"}
{assign "uploader" "true"}

{block "content"}
	<script src="recources/plugins/jquery.ba-throttle-debounce.js"></script>
	<script src="recources/js/bankbestanden/transacties.js?{$time}"></script>
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!---------------------------------------------------------------------------------------------------------
			|| filter balk
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


								<form method="get" action="" class="filter">
									<div class="row">

										<!----- checkboxes type -------------------------->
										<div class="col-md-2 pb-3">

											<h6 class="mb-2 mt-2">Type</h6>

											<div class="form-check">
												<label class="form-check-label">
													<input name="bij" value="1" type="checkbox" class="form-input-styled" checked="checked">
													Bij
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input name="af" value="1" type="checkbox" class="form-input-styled" checked="checked">
													Af
												</label>
											</div>
										</div>

										<!----- checkboxes compleet -------------------------->
										<div class="col-md-2 pb-2">

											<h6 class="mb-2 mt-2">Verwerkt</h6>

											<div class="form-check">
												<label class="form-check-label">
													<input name="verwerkt" value="1" type="checkbox" class="form-input-styled" checked="checked">
													Verwerkt
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input name="onverwerkt" value="1" type="checkbox" class="form-input-styled" checked="checked">
													Niet verwerkt
												</label>
											</div>
										</div>

										<!----- datum -------------------------->
										<div class="col-md-2">
											<h6 class="mb-1 mt-2">Bedrag</h6>

											<table>
												<tr>
													<td class="pr-2">Van:</td>
													<td>
														<input name="min" type="text" class="form-control" style="padding: 2px 5px; height: auto; width: 85px;">
													</td>
												</tr>
												<tr>
													<td class="pr-2">Tot:</td>
													<td>
														<input value="" name="max" type="text" class="form-control" style="padding: 2px 5px; height: auto; width: 85px;">
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

										<!----- Zoeken -------------------------->
										<div class="col-md-2">
											<h6 class="mb-1 mt-2">Zoeken</h6>
											<input data-lpignore="true" name="zoek" type="text" class="form-control" placeholder="zoeken..." style="padding: 2px 5px; height: auto; width: 100%;">
											<select name="grekening" class="form-control p-0 mt-1" style="height: 30px">
												<option value="0">Lopende rekening</option>
												<option value="1">G-rekening</option>
												<option value="2">Alle rekeningen</option>
											</select>
										</div>


										<!----- datum -------------------------->
										<div class="col-md-2">
											<a href="overzichten/banktransacties/index" class="btn btn-danger mt-4">
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

			<div class="row">
				<!---------------------------------------------------------------------------------------------------------
				|| transactie overzicht
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-4">
					<div class="card overflow-auto" style="max-height: 550px">
						<table class="table table-transacties" style="font-size: 12px">
							<thead>
								<tr style="background-color: #E1E2E3;">
									<th class="p-1 pl-2" style="width: 15px"></th>
									<th class="p-1 pl-2" style="width: 110px">Datum</th>
									<th class="p-1">Omschrijving</th>
									<th class="p-1 pr-2 text-right" style="width: 120px">Bedrag</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
							<tfoot>
								<tr>
									<td colspan="4">
										<i class="spinner icon-spinner3"></i><i>Transacties laden...</i>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>

				<!---------------------------------------------------------------------------------------------------------
				|| transactie details
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-8">
					<div class="card">
						<div class="card-body">

							<div style="display: none" class="load">
								<i class="spinner icon-spinner3 mr-1"></i><i>Transactie gegevens ophalen....</i>
							</div>
							<div style="display: none" class="error text-danger">
								<i class="icon-warning2 mr-1"></i><i>Transactie niet gevonden!</i>
							</div>

							<div class="details" style="display: none">

								<!----- knoppen -------------------------->
								<div class="row">
									<div class="col-md-12">

										<button type="button" class="btn btn-sm btn-outline-primary btn-verwerkt">
											<i class="icon-check mr-1"></i>Markeren als verwerkt
										</button>
										<button type="button" class="btn btn-sm btn-outline-warning btn-onverwerkt">
											<i class="icon-cross mr-1"></i>Markeren als niet verwerkt
										</button>


									</div><!-- /col -->
								</div><!-- /row -->

								<div class="row">

									<!----- details -------------------------->
									<div class="col-md-4">

										<input type="hidden" id="transactie_id"/>

										<table style="width: 100%;" class="table-transactiegegevens mt-3">
											<tr>
												<td colspan="2" class="h5 pb-2 text-primary">
													Transactiegegevens
													<i class="ml-1 icon-checkmark-circle text-success"></i>
												</td>
											</tr>
											<tr>
												<td>ID</td>
												<td class="font-weight-bolder pt-1 td-id"></td>
											</tr>
											<tr>
												<td>Relatie</td>
												<td class="font-weight-bolder pt-1 td-relatie"></td>
											</tr>
											<tr>
												<td>IBAN</td>
												<td class="font-weight-bolder pt-1 td-iban"></td>
											</tr>
											<tr>
												<td>Datum</td>
												<td class="font-weight-bolder pt-1 td-datum"></td>
											</tr>
											<tr>
												<td>Bedrag (€)</td>
												<td class="font-weight-bolder pt-1 td-bedrag"></td>
											</tr>
											<tr>
												<td class="pr-3">Omschrijving</td>
												<td class="font-weight-bolder pt-1 td-omschrijving"></td>
											</tr>
											<tr>
												<td colspan="2" style="height: 20px"></td>
											</tr>
											<tr>
												<td class="pr-3 pt-1">Categorie</td>
												<td class="font-weight-bolder pt-1">
													<select class="form-control" name="categorie_id" style="height: 35px; padding: 1px 9px">
														<option value="-1"></option>
                                                        {foreach $categorien as $c}
															<option class="px-2" value="{$c@key}">{$c.categorie}</option>
                                                        {/foreach}
													</select>
												</td>
												<td class="status-categorie">
													<i></i><span></span>
												</td>
											</tr>
											<tr>
												<td colspan="2" class="h5 pt-4 pb-2 text-primary">
													Opmerking
												</td>
											</tr>
											<tr>
												<td colspan="2" class="pt-1">
													<textarea name="opmerking" class="form-control p-1" style="height: 60px;" data-lpignore="true"></textarea>
												</td>
											</tr>
										</table>

									</div><!-- /col -->

									<div class="col-md-7 offset-md-1">

										<!----- koppelen factuur -------------------------------------->
										<table class="table-koppeling-factuur mt-3" style="display: none">
											<tr>
												<td colspan="3" class="h5 pb-2 text-primary">
													Koppeling factuur
												</td>
											</tr>
											<tr>
												<td class="pr-3 pt-1">Type</td>
												<td class="font-weight-bolder pt-1">
													<span class="span-relatie-type-select">
														<select class="form-control" name="type" style="height: 35px; padding: 1px 9px">
															<option value="-1"></option>
															<option value="inlener">Inlener</option>
															<option value="uitzender">Uitzender</option>
														</select>
													</span>
													<span class="span-relatie-type-text"></span>
													<input type="hidden" id="relatie-type" value="">
												</td>
												<td class="td-status-relatie-type">
												</td>
											</tr>
											<tr>
												<td class="pr-3 pt-1">Bedrijfsnaam</td>
												<td class="font-weight-bolder pt-1" style="width: 400px;">
													<span class="span-relatie-select">
														<select class="form-control select-search" name="bedrijfsnaam" style="height: 25px; width: 105px; padding: 0">
															<option value="-1">Selecteer een inlener/uitzender</option>
														</select>
													</span>
													<span class="span-relatie-text"></span>
													<input type="hidden" id="relatie-id" value="">
												</td>
												<td class="td-status-bedrijfsnaam">
													<i></i><span></span>
												</td>
											</tr>
											<tr>
												<td colspan="3" style="height: 20px"></td>
											</tr>
											<tr>
												<td class="pr-3 pt-1">Filter op relatie</td>
												<td class="font-weight-bolder pt-1" style="width: 400px;">
													<input type="checkbox" checked class="" name="search-relatie">
												</td>
												<td></td>
											</tr>
											<tr>
												<td class="pr-3 pt-1">Factuur nr(s)</td>
												<td class="font-weight-bolder pt-1" style="width: 400px;">
													<input type="text" class="form-control" name="search-factuur-nr">
												</td>
												<td></td>
											</tr>
											<tr>
												<td class="pr-3 pt-1">Bedrag van/tot</td>
												<td class="font-weight-bolder pt-1" style="width: 400px;">
													<input type="text" class="form-control text-right pull-left" name="search-bedrag-van" style="width: 80px; display: inline-block">
													<input type="text" class="form-control text-right pull-left" name="search-bedrag-tot" style="width: 80px; display: inline-block"">
												</td>
												<td></td>
											</tr>
											<tfoot>
												<tr>
													<td colspan="3">

														<table class="search-result-facturen mt-3">
															<thead>
																<tr class="tr-search-facturen">
																	<td colspan="4"><i class="icon-spinner3 spinner"></i> <i>Facturen zoeken....</i></td>
																</tr>
																<tr class="tr-search-not-found">
																	<td colspan="4"><i>Geen facturen gevonden</i></td>
																</tr>
																<tr>
																	<th>
																		<input type="checkbox" name="toggle-all-facturen" />
																	</th>
																	<th class="pr-2">Nr</th>
																	<th class="pr-2">Type</th>
																	<th class="text-right pr-2">Totaal</th>
																	<th class="text-right pr-2">Open</th>
																	<th class="text-right pr-2">Verwerken</th>
																	<th class="pr-2">Credit</th>
																	<th class="pr-2">Relatie</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>

															</tbody>
															<tfoot>
																<tr>
																	<td>
																		<i class="mi-subdirectory-arrow-left" style=" transform: rotate(90deg) "></i>
																	</td>
																	<td colspan="3" class="pt-1">
																		<span style="cursor: pointer" class="text-primary koppel-selected-facturen">koppelen</span>
																	</td>
																	<td class="text-right pr-2 font-weight-bold">Totaal</td>
																	<td class="text-right pr-2 font-weight-bold search-facturen-totaal"></td>
																	<td colspan="3"></td>
																</tr>
															</tfoot>
														</table>

													</td>
												</tr>
											</tfoot>
										</table>




										<!----- koppelen factoring -------------------------------------->
										<table class="table-koppeling-factoring mt-3" style="display: none">
											<tr>
												<td colspan="3" class="h5 pb-2 text-primary">
													Koppeling factoring
												</td>
											</tr>
											<tr>
												<td class="pr-3">Factuur ID</td>
												<td class="td-factuur-id"></td>
											</tr>
											<tr>
												<td class="pr-3">Bedrag</td>
												<td class="td-bedrag"></td>
											</tr>
											<tr>
												<td class="pr-3">PDF</td>
												<td class="td-factuur-pdf">
													<a target="_blank" href=""></a>
												</td>
											</tr>
										</table>

									</div><!-- /col -->

								</div><!-- /row -->

							</div><!-- /details -->

						</div>
					</div>
				</div>
			</div>
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
								uploadUrl:'upload/bankbestanden',
								showPreview:false,
								elErrorContainer:"#errorBlock",
								allowedFileExtensions:['xml', 'XML']
							});
							$('#fileupload').on('fileuploaded', function()
							{
								window.location.reload();
							});
						});
                        {/literal}
					</script>
					<div id="errorBlock"></div>

					<form action="#">
						<input name="file" type="file" id="fileupload" class="file-input">
					</form>


				</div>
				<div class="modal-footer">


				</div>
			</div>
		</div>
	</div>
{/block}