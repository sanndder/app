{extends file='../layout.tpl'}
{block "title"}Overzicht facturen{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Debiteurbeheer - Voorfinanciering{/block}
{assign "datamask" "true"}

{block "content"}
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xxl-10 col-lg-12">

					<div class="card">
						<div class="card-body">

							<div class="row">

								<!------ header totaal ----------------->
								<div class="col-md-6">

									<div class="d-flex align-items-center justify-content-center mb-2">
									<span href="#" class="btn bg-transparent border-primary text-primary rounded-pill border-2 btn-icon mr-3">
										<i class="icon-coin-euro icon-2x p-1"></i>
									</span>
										<div class="text-primary">
											<div class="font-weight-semibold" style="font-size: 20px">€ {$openstaand|number_format:2:',':'.'}</div>
											<span class="text-muted">voorfinanciering</span>
										</div>
									</div>

								</div>

								<!------ header terug ----------------->
								<div class="col-md-6">

									<div class="d-flex align-items-center justify-content-center mb-2">
									<span class="btn bg-transparent border-danger text-danger rounded-pill border-2 btn-icon mr-3">
										<i class="icon-undo icon-2x p-1"></i>
									</span>
										<div class="text-danger">
											<div class="font-weight-semibold" style="font-size: 20px">€ {$terug|number_format:2:',':'.'}</div>
											<span class="text-muted">terug te betalen</span>
										</div>
									</div>

								</div>
							</div>

						</div><!-- einde card-body -->
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="table-active table-border-double">
										<td colspan="11">Voldaan - Terug te boeken</td>
									</tr>
									{if isset($facturen[1])}
										<tr>
											<th style="width: 15px"></th>
											<th>Factuur nr</th>
											<th>Jaar</th>
											<th>Periode</th>
											<th>Vervaldatum</th>
											<th>Inlener</th>
											<th class="text-right">Voorfinanciering (€)</th>
											<th class="text-right">Factuurbedrag (€)</th>
											<th class="text-right">Openstaand (€)</th>
											<th></th>
											<th></th>
										</tr>
                                        {foreach $facturen[1] as $f}
											<tr>
												<td></td>
												<td>{$f.factuur_nr}</td>
												<td>{$f.jaar}</td>
												<td>{$f.periode}</td>
												<td>{$f.verval_datum|date_format: '%d-%m-%Y'}</td>
												<td>{$f.inlener}</td>
												<td class="text-right">{$f.bedrag_voorfinanciering|number_format:2:',':'.'}</td>
												<td class="text-right">{$f.bedrag_incl|number_format:2:',':'.'}</td>
												<td class="text-right">{$f.bedrag_openstaand|number_format:2:',':'.'}</td>
												<td>
													<a href="facturatie/factuur/details/{$f.factuur_id}" target="_blank">
														<i class="icon-file-text2 mr-1"></i> details
													</a>
												</td>
												<td>
													<span style="cursor: pointer" data-clipboard-text="terug {$f.factuur_nr} {$f.inlener}" class="copy-text text-primary">
														<i class="icon-copy4 mr-1"></i>Omschrijving
													</span>
												</td>
											</tr>
                                        {/foreach}
									{else}
										<tr>
											<td colspan="6">Geen resultaten</td>
										</tr>
									{/if}
									<tr class="table-active table-border-double">
										<td colspan="11">Openstaand - Voorfinanciering</td>
									</tr>
                                    {if isset($facturen[0])}
	                                    <tr>
		                                    <th style="width: 15px"></th>
		                                    <th>Factuur nr</th>
		                                    <th>Jaar</th>
		                                    <th>Periode</th>
		                                    <th>Vervaldatum</th>
		                                    <th>Inlener</th>
		                                    <th class="text-right">Voorfinanciering (€)</th>
		                                    <th class="text-right">Factuurbedrag (€)</th>
		                                    <th class="text-right">Openstaand (€)</th>
		                                    <th></th>
		                                    <th></th>
	                                    </tr>
                                        {foreach $facturen[0] as $f}
		                                    <tr>
			                                    <td></td>
			                                    <td>{$f.factuur_nr}</td>
			                                    <td>{$f.jaar}</td>
			                                    <td>{$f.periode}</td>
			                                    <td>{$f.verval_datum|date_format: '%d-%m-%Y'}</td>
			                                    <td>{$f.inlener}</td>
			                                    <td class="text-right">{$f.bedrag_voorfinanciering|number_format:2:',':'.'}</td>
			                                    <td class="text-right">{$f.bedrag_incl|number_format:2:',':'.'}</td>
			                                    <td class="text-right">{$f.bedrag_openstaand|number_format:2:',':'.'}</td>
			                                    <td>
				                                    <a href="facturatie/factuur/details/{$f.factuur_id}" target="_blank">
					                                    <i class="icon-file-text2 mr-1"></i> details
				                                    </a>
			                                    </td>
			                                    <td></td>
		                                    </tr>
                                        {/foreach}
                                    {else}
										<tr>
											<td colspan="6">Geen resultaten</td>
										</tr>
                                    {/if}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}