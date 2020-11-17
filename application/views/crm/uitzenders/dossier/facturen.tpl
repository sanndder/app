{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{if $uitzender->archief == 1}
	<span style="color:red">(archief)</span>
{/if}{/block}

{block "content"}

    {include file='crm/uitzenders/dossier/_sidebar.tpl' active='facturen'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xl-12">

                    {if isset($msg)}{$msg}{/if}

					<!-- Default tabs -->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Facturen</span>
							<div class="header-elements">

							</div>
						</div>

						<div class="bg-light">
                            {if $jaren != NULL}
								<ul class="nav nav-tabs nav-tabs-bottom mb-0">
                                    {foreach $jaren as $j }
										<li class="nav-item">
											<a href="#card-toolbar-tab-{$j}" class="nav-link active show" data-toggle="tab">
                                                {$j}
											</a>
										</li>
                                    {/foreach}
								</ul>
                            {/if}
						</div>

						<div class="card-body tab-content p-0">
							<div class="tab-pane fade show active" id="card-toolbar-tab-">

								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th style="width: 25px;">Jaar</th>
											<th style="width: 25px;">Periode</th>
											<th>Inlener</th>
											<th style="width: 100px" class="text-right">Factuur nr</th>
											<th style="width: 120px" class="text-right">Verkoop (€)</th>
											<th style="width: 120px" class="text-right">Kosten (€)</th>
											<th style="width: 120px" class="text-right">Factuur nr</th>
											<th style="width: 100px" class="text-right">Marge (€)</th>
											<th style="width: 25px"></th>
											<th></th>
										</tr>
									</thead>
                                    {if $facturen != NULL}
										<tbody>
                                            {foreach $facturen as $f}
												<tr>
													<td>{$f.verkoop.jaar}</td>
													<td>{$f.verkoop.periode}</td>
													<td style="width: 1px; white-space: nowrap;">
                                                        {$f.verkoop.bedrijfsnaam}
														{if $f.verkoop.project != NULL}
															- {$f.verkoop.project}
														{/if}
													</td>
													<td class="text-right">
                                                        {$f.verkoop.factuur_nr}
													</td>
													<td class="text-right">
														<a target="_blank" href="facturatie/factuur/view/{$f.verkoop.factuur_id}">
															€ {$f.verkoop.bedrag_incl|number_format:2:',':'.'}
														</a>
													</td>
													<td class="text-right">
														<a target="_blank" href="facturatie/factuur/viewkosten/{$f.verkoop.factuur_id}">
															€ {$f.verkoop.kosten_incl|number_format:2:',':'.'}
														</a>
													</td>
													<td class="text-right">
														{if isset($f.marge)}{$f.marge.factuur_nr}{/if}
													</td>
													<td class="text-right">
                                                        {if isset($f.marge)}
															<a target="_blank" href="facturatie/factuur/view/{$f.marge.factuur_id}">
	                                                            € {$f.marge.bedrag_incl|number_format:2:',':'.'}
															</a>
                                                        {/if}
													</td>
													<td>
														<ul class="list-inline mb-0 mt-2 mt-sm-0">
															<li class="list-inline-item dropdown">
																<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown">
																	<i class="icon-menu7"></i></a>

																<div class="dropdown-menu dropdown-menu-right">
																	<a href="crm/uitzenders/dossier/facturen/{$uitzender->uitzender_id}?email={$f.verkoop.factuur_id}" class="dropdown-item">
																		<i class="icon-envelop2"></i> Emailen
																	</a>
																	<a href="javascript:void()" class="dropdown-item">
																		<i class="icon-file-download"></i> Download
																	</a>
																	<a href="crm/uitzenders/dossier/facturen/{$uitzender->uitzender_id}?del={$f.verkoop.factuur_id}" class="dropdown-item">
																		<i class="icon-cross2"></i> Verwijderen
																	</a>
																</div>
															</li>
														</ul>
													</td>
													<td></td>
												</tr>
                                            {/foreach}
										</tbody>
                                    {/if}
								</table>


							</div>
						</div>
					</div>
					<!-- /default tabs -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}