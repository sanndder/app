{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='facturen'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xl-12">

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
											<a href="crm/inleners/dossier/facturen/{$inlener->inlener_id}/{$j}" class="nav-link {if $jaar == NULL || $jaar == $j}active{/if}">
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
											<th style="width: 100px" class="text-right">Factuur nr</th>
											<th style="width: 130px" class="text-right">Bedrag (€)</th>
											<th style="width: 130px" class="text-right">Vrije deel (€)</th>
											<th style="width: 130px" class="text-right">G-rekening (€)</th>
											<th style="width: 140px" class="text-right">Vervallen (dagen)</th>
											<th style="width: 180px" class="text-right">Voldaan</th>
											<th style="width: 25px"></th>
											<th></th>
										</tr>
									</thead>
                                    {if $facturen != NULL}
										<tbody>
                                            {foreach $facturen as $f}
												<tr>
													<td>{$f.jaar}</td>
													<td>{$f.periode}</td>
													<td class="text-right">
                                                        {$f.factuur_nr}
													</td>
													<td class="text-right">
														<a target="_blank" href="facturatie/factuur/view/{$f.factuur_id}">
															€ {$f.bedrag_incl|number_format:2:',':'.'}
														</a>
													</td>
													<td class="text-right">
															€ {($f.bedrag_incl-$f.bedrag_grekening)|number_format:2:',':'.'}
													</td>
													<td class="text-right">
															€ {$f.bedrag_grekening|number_format:2:',':'.'}
													</td>
													<td class="text-right">
                                                        {if $f.voldaan != 1 &&  $f.bedrag_incl != 0}{$f.verval_dagen}{/if}
													</td>
													<td class="text-right">
                                                        {if $f.voldaan == 1 || $f.bedrag_incl == 0}
															<span class="text-success font-weight-bold">
							                                <i class="icon-check mr-1"></i>
																{if $f.bedrag_incl == 0}
                                                                    {$f.factuur_datum|date_format: '%d-%m-%Y'}
																{else}
                                                                    {$f.voldaan_op|date_format: '%d-%m-%Y'}
																{/if}

						                                 </span>
                                                        {else}
															-
                                                        {/if}
													</td>
													<td>

													</td>
													<td>
														<a href="facturatie/factuur/details/{$f.factuur_id}" target="_blank">
															<i class="icon-file-text2 mr-1"></i> details
														</a>
													</td>
												</tr>
                                            {/foreach}
										</tbody>
                                    {/if}
								</table>


							</div>
						</div>
					</div>

				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}