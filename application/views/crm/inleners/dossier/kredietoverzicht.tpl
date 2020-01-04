{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-stats-dots{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

	{include file='crm/inleners/dossier/_sidebar.tpl' active='kredietoverzicht'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">

				<div class="col-12">

					<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
					|| Top side
					--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-body pt-0 pl-2 pb-0">

							<div class="row">

								<!-------------------------------------------- Kredietinstellingen------------------------------------------------------->
								<div class="col-xl-4 bg-info-600 pb-4 mb-0 pl-3">
									<h5 class="mt-0 pt-3">Kredietinstellingen</h5>

									<table>
										<tr>
											<td class="pr-1">
												<a href="javascript:void(0)" class="text-default" data-popup="tooltip" data-placement="top" data-original-title="" title="Kredietlimiet aanpassen">
													<i class="icon-cog text-white mt-1 font-size-sm"></i>
												</a>
											</td>
											<td class="pr-5 pt-1">Kredietlimiet</td>
											<td class="font-size-sm  text-uppercase">
												{if $kredietgegevens.kredietlimiet === NULL}<i>onbekend</i>{else}{$kredietgegevens.kredietlimiet|number_format:2:',':'.'}{/if}
											</td>
										</tr>
										<tr>
											<td></td>
											<td class="pt-1">Kredietgebruik</td>
											<td class="font-size-sm {if $kredietgegevens.kredietgebruik > $kredietgegevens.kredietlimiet}text-danger{/if} text-uppercase">
												{if $kredietgegevens.kredietgebruik === NULL}
													<i>onbekend</i>
												{else}
													{$kredietgegevens.kredietgebruik|number_format:2:',':'.'}
												{/if}
											</td>
										</tr>

										<!-------------------------------------------- Rapport credit safe------------------------------------------------------->
										<tr>
											<td class="pr-1">
												<a href="javascript:void(0)" class="text-default" data-popup="tooltip" data-placement="top" data-original-title="" title="Nieuwste gegevens ophalen">
													<i class="icon-loop3 text-white mt-2 font-size-sm"></i>
												</a>
											</td>
											<td colspan="2" class="pt-3">
												<h6 class="mt-0">Rapport Creditsafe</h6>
											</td>
										</tr>
										{if $rapport === NULL}
											<tr>
												<td class="pt-1" colspan="3"><i>Geen rapportgegevens gevonden</i></td>
											</tr>
										{else}
											<tr>
												<td></td>
												<td class="pt-1 pr-5">Kredietlimiet Creditsafe</td>
												<td class="font-size-sm">
													€ {$rapport.companySummary.creditRating.creditLimit.value|number_format:0:',':'.'}
												</td>
											</tr>
											<tr>
												<td></td>
												<td class="pt-1">Rapport datum</td>
												<td class="font-size-sm">
                                                    {$rapport_datum|date_format: '%d-%m-%Y'}
												</td>
											</tr>

										{/if}

									</table>


								</div><!-- /col -->

								<!--------------------------------------------  kredietaanvragen ------------------------------------------------------->
								<div class="col-xl-8 col-md-12 pl-4">
									<h5 class="mt-0 pt-3">Kredietaanvragen</h5>

									<form method="post" action="">
										<table class="table table-xs table-striped mb-3">
											<thead>
												<tr>
													<th>KvK nr</th>
													<th>Limiet aangevraagd</th>
													<th>Limiet toekennen</th>
													<th>Aanvraagdatum</th>
												</tr>
											</thead>
											<tbody>
												{if $kredietaanvragen !== NULL}
													{foreach $kredietaanvragen as $k}
														<tr {if $k.krediet_afgewezen == 1}class="text-muted" {/if}>
															<td>{$k.kvknr}</td>
															<td>€ {$k.kredietlimiet_gewenst|number_format:0:',':'.'}</td>
															<td>
	                                                            {if $k.krediet_afgewezen == 1}
		                                                            € 0
	                                                            {else}
																	<input style="max-width: 100px" type="text" class="form-control" value="{$k.kredietlimiet_toegekend|number_format:0:',':'.'}" />
																{/if}
															</td>
															<td>{$k.timestamp|date_format: '%d-%m-%Y'}</td>
															<td>
	                                                            {if $k.krediet_afgewezen == 0}
																	<button type="submit" class="btn btn-sm btn-outline-success" name="accept" value="{$k.id}">
																		<i class="icon-check mr-1"></i>goedkeuren
																	</button>
		                                                            <a class="btn btn-sm btn-outline-danger font-weight-bold" href="{$base_url}/crm/inleners/dossier/kredietoverzicht/{$inlener->inlener_id}/?deny={$k.id}">
			                                                            <i class="icon-cross"></i>afkeuren
		                                                            </a>
	                                                            {/if}
															</td>
														</tr>
	                                                {/foreach}
	                                            {/if}
											</tbody>
										</table>
									</form>


								</div><!-- /col -->
							</div><!-- /row -->



						</div><!-- /card body-->
					</div><!-- /card: Bedrijfsgegevens  -->


				</div><!-- / Left side -->
			</div><!-- /einde main row -->

		</div><!-- /content area -->
	</div>	<!-- /main content -->


{/block}