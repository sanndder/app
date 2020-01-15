{extends file='../../layout.tpl'}
{block "title"}Inleners{/block}
{block "header-icon"}icon-folder-search{/block}
{block "header-title"}Kredietaanvraag{/block}
{assign "datatable" "true"}

{block "content"}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-md-12">

					<!-- Basic card -->
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Aanvraag kredietlimiet nieuwe klant</h5>
						</div>
						<div class="card-body pb-4">

							<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
							|| success
							--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            {if isset($success)}

	                            <p class="mb-4">Uw aanvraag is opgeslagen. Wij controleren deze zo spoedig mogelijk.</p>

	                            <a href="{$base_url}/crm/inleners/kredietlimiet" class="btn btn-sm btn-primary">
		                            <i class="icon-plus-circle2 mr-1"></i> Nog een kredietaanvraag
	                            </a>
	                            <a href="{$base_url}/crm/inleners" class="btn btn-sm btn-outline-primary">
		                            <i class="icon-arrow-left7 mr-1"></i> Terug naar inleners
	                            </a>

                            {else}
								<p class="mb-4">U hoef alleen het KvK nummer van de klant in te voeren. Wij halen automatisch de bijbehorende gegevens op.</p>
								<div class="row">
									<div class="alert alert-warning alert-styled-left alert-dismissible col-xxl-4 col-lg-6" style="display: none">
										<span class="font-weight-semibold"></span>
									</div>
								</div>


								<form method="post" action="">

									<div class="input-group row mt-1">
										<label class="col-xxl-1 col-lg-2 pt-1 font-weight-bold">KvK nummer:</label>
										<input class="form-control col-xxl-3 col-lg-4 input-kvk-credit-check" type="text" name="kvknr" placeholder="Vul een geldig KvK nummer in" value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.kvknr}{/if}"/>
									</div>

									<fieldset class="mt-4">
										<legend class="text-uppercase font-size-sm font-weight-bold">
											<i class="icon-search4 mr-1"></i> Gevonden bedrijfsinformatie
										</legend>

										<div class="row">
											<div class="col-xxl-5 col-lg-6 col-md-12">
                                                {if isset($msg)}{$msg}{/if}

												<div class="info">
													<span class="status-wachten font-italic" {if isset($bedrijfsgegevens)} style="display:none;"{/if}> Wachten op invoer KvK nummer.......</span>
													<span class="status-zoeken font-italic" style="display: none"> <i class="icon-spinner spinner mr-1"></i>Informatie ophalen</span>
													<span class="status-leeg font-italic" style="display: none"> <i class="icon-exclamation mr-1"></i>Geen bedrijfsinformatie gevonden!</span>
												</div>

												<div class="table-result" {if !isset($bedrijfsgegevens)}style="display: none"{/if}>

													<table class="table" style="width: 100%">
														<tr>
															<td style="width: 160px">Bedrijfsnaam</td>
															<td class="td-name font-weight-bold">
																<input type="text" class="form-control" name="bedrijfsnaam" readonly="readonly" value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.bedrijfsnaam}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Straat</td>
															<td class="td-street font-weight-bold">
																<input type="text" class="form-control" name="straat" readonly="readonly" value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.straat}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Huisnummer</td>
															<td class="td-houseNo font-weight-bold">
																<input type="text" class="form-control" name="huisnummer" readonly="readonly" value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.huisnummer}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Postcode</td>
															<td class="td-postCode font-weight-bold">
																<input type="text" class="form-control" name="postcode" readonly="readonly" value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.postcode}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Plaats</td>
															<td class="td-city font-weight-bold">
																<input type="text" class="form-control" name="plaats" readonly="readonly" value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.plaats}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Telefoon</td>
															<td class="td-telefoon font-weight-bold">
																<input type="text" class="form-control" name="telefoon" required value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.telefoon}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Email</td>
															<td class="td-email font-weight-bold">
																<input type="text" class="form-control" name="email" required value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.email}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>BTW nummer</td>
															<td class="td-btw font-weight-bold">
																<input type="text" class="form-control" name="btwnr" required value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.btwnr}{/if}"/>
															</td>
														</tr>
														<tr>
															<td>Gewenst Kredietlimiet</td>
															<td class="td-limiet font-weight-bold">
																<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text"><i class="mi-euro-symbol"></i></span>
														</span>
																	<input type="number" class="form-control" name="kredietlimiet" required value="{if isset($bedrijfsgegevens)}{$bedrijfsgegevens.kredietlimiet}{/if}"/>
																</div>
															</td>
														</tr>
													</table>

													<button type="submit" name="set" class="btn btn-success btn-sm mt-3">
														<i class="icon-check mr-1"></i>Kredietlimiet aanvragen
													</button>

												</div>
											</div><!-- /col -->
										</div><!-- /row -->


									</fieldset>

								</form>
                            {/if}
						</div>
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div>
	</div>
	<!-- /content area -->
{/block}