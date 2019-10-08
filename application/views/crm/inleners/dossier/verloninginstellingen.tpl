{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "select2" "true"}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='verloninginstellingen'}
    {include file='crm/inleners/dossier/modals/urentype_toevoegen.tpl'}


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
					|| Tabs
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<div class="card-header bg-light pb-0 pt-sm-0 header-elements-sm-inline">
							<div class="header-elements">
								<ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
									<li class="nav-item">
										<a href="#tab-factoren" class="nav-link {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-factoren'}active{/if}" data-toggle="tab">
											Factoren
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-urentypes" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-urentypes'}active{/if}" data-toggle="tab">
											Urentypes
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-vergoedingen" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-vergoedingen'}active{/if}" data-toggle="tab">
											Vergoedingen
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-staffelkorting" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-staffelkorting'}active{/if}" data-toggle="tab">
											Staffelkorting
										</a>
									</li>
								</ul>
							</div>
						</div>

						<!-- card  body-->
						<div class="card-body tab-content">

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Factoren
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-factoren'}show active{/if}" id="tab-factoren">

								<form method="post" action="">
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold">Factoren toevoegen</legend>

										<table>
											<tr>
												<td style="width: 300px;">Omschrijving</td>
												<td style="width: 110px;">Factor uren</td>
												<td style="width: 110px;">Factor overuren</td>
												<td></td>
											</tr>
											<tr>
												<td class="pr-2">
													<input name="omschrijving" value="" type="text" class="form-control"/>
												</td>
												<td class="pr-2">
													<input name="factor_hoog" value="" type="text" class="form-control text-right"/>
												</td>
												<td class="pr-2">
													<input name="factor_laag" value="" type="text" class="form-control text-right"/>
												</td>
												<td>
													<button type="submit" name="set" value="inleners_factoren" class="btn btn-outline-success btn-sm">
														<i class="icon-plus-circle2 mr-1"></i>Toevoegen
													</button>
												</td>
											</tr>
										</table>
									</fieldset>
								</form>


								<fieldset class="mb-3  mt-5">
									<legend class="text-uppercase font-size-sm font-weight-bold">Factoren overzicht</legend>
								</fieldset>
								<table>
									<tr>
										<td style="width: 300px;">Omschrijving</td>
										<td style="width: 110px;">Factor uren</td>
										<td style="width: 110px;">Factor overuren</td>
										<td></td>
									</tr>
                                    {foreach $factoren as $factor}
										<form method="post" action="">
											<tr>
												<td class="pr-2">
													<input name="default_factor[{$factor.factor_id}]" value="{$factor.default_factor}" type="hidden"/>
													<input name="omschrijving[{$factor.factor_id}]" {if $factor.default_factor}readonly{/if} value="{$factor.omschrijving}" type="text" class="form-control"/>
												</td>
												<td class="pr-2">
													<input name="factor_hoog[{$factor.factor_id}]" value="{$factor.factor_hoog|number_format:3:',':'.'}" type="text" class="form-control text-right"/>
												</td>
												<td class="pr-2">
													<input name="factor_laag[{$factor.factor_id}]" value="{$factor.factor_laag|number_format:3:',':'.'}" type="text" class="form-control text-right"/>
												</td>
												<td>
													<button type="submit" name="set" value="inleners_factoren" class="btn btn-outline-success btn-sm">
														<i class="icon-checkmark5"></i>
													</button>
                                                    {if !$factor.default_factor}
														<button onclick="return confirm('Factoren verwijderen?')" type="submit" name="del" value="inleners_factoren" class="btn btn-outline-danger btn-sm">
															<i class="icon-trash"></i>
														</button>
                                                    {/if}
												</td>
											</tr>
										</form>
                                    {/foreach}
								</table>

							</div><!-- /einde tab -->

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Urentypes
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-urentypes'}show active{/if}" id="tab-urentypes">

								<div class="btn-group">
									<button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal_add_urentype" >
										<i class="icon-plus-circle2"></i>
										<span class="d-none d-inline-block ml-2">Urentype toevoegen</span>
									</button>
								</div>

							</div>

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Vergoedingen
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade" id="card-tab3">

							</div>

						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}