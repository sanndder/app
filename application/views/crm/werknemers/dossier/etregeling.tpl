{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "select2" "true"}
{assign "uploader" "true"}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='etregeling'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-10">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-xl-10">

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Adresgegevens
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<fieldset class="mb-2">
								<legend class="text-uppercase font-size-sm font-weight-bold mb-1">Inschrijving niet-ingezetene uploaden</legend>


								<div class="row mt-2">
									<div class="col-xl-6 col-lg-12">
                                        {if !isset($bsn) || $bsn === NULL }
											<script>
                                                {literal}
												$(document).ready(function(){
													$('#fileupload').fileinput('refresh', {uploadUrl:'upload/uploadbsnet/{/literal}{$werknemer->werknemer_id}{literal}'});
													$('#fileupload').on('fileuploaded', function(){
														window.location.reload();
													});
												});
                                                {/literal}
											</script>
											<form action="#">
												<input name="file" type="file" id="fileupload" class="file-input">
											</form>
                                        {else}
											<table>
												<tr>
													<td class="font-weight-bold pr-4">Bestand:</td>
													<td class="pr-4">
														<a href="{$base_url}/crm/werknemers/dossier/etregeling/{$werknemer->werknemer_id}/?delbsn">
															<i class="icon-file-empty mr-1"></i>inschrijving.pdf
														</a>
													</td>
													<td>
														<a href="{$base_url}/crm/werknemers/dossier/etregeling/{$werknemer->werknemer_id}/?delbsn" class="text-danger">
															<i class="icon-cross"></i>verwijderen
														</a>
													</td>
												</tr>
												<tr>
													<td class="font-weight-bold pr-4 pt-3">Vervaldatum:</td>
													<td class="pt-3">
														<input name="vervaldatum" value="" type="text" class="form-control "/>
													</td>
												</tr>
											</table>
                                        {/if}

									</div>
								</div>

							</fieldset>

						</div><!-- /card body-->
					</div><!-- /basic card -->

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
				|| Adresgegevens
				-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">
								<fieldset class="mb-2">
									<legend class="text-uppercase font-size-sm font-weight-bold mb-1">Instellingen</legend>

									<table>
										<tr>
											<td class="pr-5 pt-2">Uitruilen huisvesting</td>
											<td class="pt-2">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" class="form-input-styled-info" checked>
													</label>
												</div>
											</td>
										</tr>
										<tr>
											<td class="pr-5 pt-2">Uitruilen levensonderhoud</td>
											<td class="pt-2">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" class="form-input-styled-info" checked>
													</label>
												</div>
											</td>
										</tr>
										<tr>
											<td class="pr-5 pt-2">Uitruilen reiskosten</td>
											<td class="pt-2">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" class="form-input-styled-info" checked>
													</label>
												</div>
											</td>
										</tr>
									</table>

								</fieldset>
							</form>
						</div><!-- /card body-->
					</div><!-- /basic card -->

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Adresgegevens
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">


							<div class="row">

                                {* Adres Nederland *}
								<div class="col-md-5">
									<form method="post" action="">
										<fieldset class="mb-2">
											<legend class="text-uppercase font-size-sm font-weight-bold mb-1">Adres verblijf Nederland</legend>

											<div class="table-responsive">
												<table class="table">
													<tr>
														<td>Land</td>
														<td>
															<input value="Nederland" type="text" class="form-control" disabled/>
														</td>
													</tr>
													<tr>
														<td>Straat</td>
														<td>
															<input name="straat" value="" type="text" class="form-control"/>
														</td>
													</tr>
													<tr>
														<td>Huisnummer</td>
														<td>
															<input style="width: 100px;" name="huisnummer" value="" type="text" class="form-control"/>
														</td>
													</tr>
													<tr>
														<td>Postcode</td>
														<td>
															<input style="width: 100px;" name="postcode" value="" type="text" class="form-control"/>
														</td>
													</tr>
													<tr>
														<td>Plaats</td>
														<td>
															<input name="plaats" value="" type="text" class="form-control"/>
														</td>
													</tr>
												</table>
											</div>

											<button type="submit" name="set" value="werknemers_factoren" class="btn btn-success btn-sm mt-3">
												<i class="icon-checkmark2 mr-1"></i>Adres opslaan
											</button>
										</fieldset>
									</form>
								</div><!-- /col -->


                                {* Adres Buitenland *}
								<div class="col-md-5 offset-lg-1">
									<form method="post" action="">
										<fieldset class="mb-3">
											<legend class="text-uppercase font-size-sm font-weight-bold mb-1">Adres land van herkomst</legend>

											<div class="table-responsive">
												<table class="table">
													<tr>
														<td>Land</td>
														<td>
															<select class="form-control select-search">
																<option>Selecteer een land</option>
                                                                {foreach $landen as $land}
																	<option value="{$land@key}">{$land}</option>
                                                                {/foreach}
															</select>
														</td>
													</tr>
													<tr>
														<td>Straat</td>
														<td>
															<input name="straat" value="" type="text" class="form-control"/>
														</td>
													</tr>
													<tr>
														<td>Huisnummer</td>
														<td>
															<input style="width: 100px;" name="huisnummer" value="" type="text" class="form-control"/>
														</td>
													</tr>
													<tr>
														<td>Postcode</td>
														<td>
															<input name="postcode" value="" type="text" class="form-control"/>
														</td>
													</tr>
													<tr>
														<td>Plaats</td>
														<td>
															<input name="plaats" value="" type="text" class="form-control"/>
														</td>
													</tr>
												</table>
											</div>

											<button type="submit" name="set" value="werknemers_factoren" class="btn btn-success btn-sm mt-3">
												<i class="icon-checkmark2 mr-1"></i>Adres opslaan
											</button>
										</fieldset>
									</form>
								</div><!-- /col -->

							</div><!-- /row -->


						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}