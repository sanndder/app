{extends file='../../layout.tpl'}
{block "title"}Banktransacties{/block}
{block "header-icon"}icon-list2{/block}
{block "header-title"}Overzicht - Banktransacties{/block}

{assign "datamask" "true"}
{assign "uploader" "true"}
{assign "debounce" "true"}

{block "content"}
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
				<div class="col-md-12 col-xxl-8">

					<div class="card">

						<div class="row">

							<div class="col-md-12 media">

								<div style="height: 100%; width: 120px">
									<button data-toggle="modal" data-target="#upload_modal" type="button" class="btn bg-primary btn-block btn-float" style="height: 100%">
										<i class="icon-file-plus icon-2x"></i>
										<span>Uploaden</span>
									</button>
								</div>

								<div class="media-body ml-3">
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
												<select name="grekening" class="form-control p-0 mt-1" style="height: 25px">
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

			</div>


			<!---------------------------------------------------------------------------------------------------------
			|| transactie overzicht
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-12 col-xxl-8">
					<div class="card card-body">

						<table class="table-transactiegegevens">
							<thead>
								<tr>
									<th style="width: 120px">datum</th>
									<th>omschrijving</th>
									<th>bedrag (€)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="ta-datum">28-01-2021</td>
									<td>
										<div class="ta-relatie">UAB NIUM EU * Factris</div>
										<div class="ta-omschrijving">FACTRIS-2612 doorstorting itab 1248 g-deel</div>
									</td>
									<td class="ta-bedrag">500</td>
								</tr>
								<tr>
									<td class="ta-datum">28-01-2021</td>
									<td>
										<div class="ta-relatie">UAB NIUM EU * Factris</div>
										<div class="ta-omschrijving">FACTRIS-2612 doorstorting itab 1248 g-deel</div>
									</td>
									<td class="ta-bedrag">500</td>
								</tr>
								<tr>
									<td class="ta-datum">28-01-2021</td>
									<td>
										<div class="ta-relatie">UAB NIUM EU * Factris</div>
										<div class="ta-omschrijving">FACTRIS-2612 doorstorting itab 1248 g-deel</div>
									</td>
									<td class="ta-bedrag">500</td>
									<td>

									</td>
								</tr>
							</tbody>
						</table>

					</div>

				</div>

				<!---------------------------------------------------------------------------------------------------------
				|| transactie details
				---------------------------------------------------------------------------------------------------------->
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