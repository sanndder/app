{extends file='../../layout.tpl'}
{block "title"}Margebetlaingen{/block}
{block "header-icon"}icon-coin-euro{/block}
{block "header-title"}Overzicht - margebetalingen{/block}
{assign "datatable" "true"}

{block "content"}

	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<form method="post" action="">


				<!---------------------------------------------------------------------------------------------------------
				|| Zijmenu
				---------------------------------------------------------------------------------------------------------->
				<div class="row">
					<div class="col-md-3">

						<div class="card card-body" style="position: sticky; top: 65px">

							<table style="font-size: 18px">
								<tr>
									<td>Totaal SEPA</td>
									<td class="pl-5 text-right">€</td>
									<td class="sepa-totaal">0,00</td>
								</tr>
							</table>

							<fieldset>
								<button type="submit" name="sepa" class="btn btn-primary mt-4">
									<i class="icon-file-xml mr-1"></i>
									SEPA genereren
								</button>
								<button type="submit" name="voldaan" class="btn btn-outline-primary mt-4">
									<i class="icon-check2 mr-1"></i>
									Markeren als voldaan
								</button>
							</fieldset>
						</div>


					</div><!-- /col -->


					<div class="col-md-9">

                        {if isset($msg)}{$msg}{/if}

						<!-------------------------------------------------------------------------------------------------------------------------------------------------
						|| Tabs
						-------------------------------------------------------------------------------------------------------------------------------------------------->
						<!-- Basic card -->
						<div class="card">

							<div class="card-header bg-light pb-0 pt-sm-0 header-elements-sm-inline" style="padding-left: 9px">
								<div class="header-elements">
									<ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
										<li class="nav-item">
											<a href="#tab-week" class="nav-link {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-week'}active{/if}" data-toggle="tab">
												Week
											</a>
										</li>
										<li class="nav-item">
											<a href="#tab-4weken" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-4weken'}active{/if}" data-toggle="tab">
												4 weken
											</a>
										</li>
										<li class="nav-item">
											<a href="#tab-maand" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-maand'}active{/if}" data-toggle="tab">
												maand
											</a>
										</li>
										<li class="nav-item">
											<a href="#tab-sepa" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-sepa'}active{/if}" data-toggle="tab">
												Sepa bestanden
											</a>
										</li>
									</ul>
								</div>
							</div>

							<!-- card  body-->
							<div class="card-body tab-content">

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| week facturen
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<div class="tab-pane fade {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-week'}show active{/if}" id="tab-week">

                                    {if isset($facturen.w)}
                                        {foreach $facturen.w as $jaar}
                                            {foreach $jaar as $periode}
												<div class="div-periode mb-1">
													<div class="row bg-primary pt-2 font-weight-bolder" style="font-size: 16px">
														<div class="col-md-12">
															<input type="checkbox" class="mr-2 checkbox-periode" id="{$jaar@key}-{$periode@key}" style="top:1px; position: relative"/>
															<label for="{$jaar@key}-{$periode@key}" class="font-weight-bolder">{$periode@key|string_format:"%02d"} - {$jaar@key}</label>

															<a data-toggle="collapse" class="text-white pull-right" href="#accordion-item-{$jaar@key}-{$periode@key}">
																<i class="icon-arrow-down12"></i>
															</a>
														</div><!-- /col -->
													</div>
													<!-- /row -->

													<div id="accordion-item-{$jaar@key}-{$periode@key}" class="collapse {if $jaar@key <= $hide_jaar && $periode@key < $hide_week}show{/if}">
                                                        {foreach $periode as $uitzender}
															<div class="row mb-2 py-2 div-uitzender" style="background-color: #F1F2F3">
																<div class="col-md-4" style="padding-left: 40px">
																	<input data-periode="{$periode@key}" type="checkbox" class="mr-2 checkbox-uitzender" id="{$uitzender@key}-{$periode@key}" style="top:2px; position: relative"/>
																	<label for="{$uitzender@key}-{$periode@key}" class="font-weight-bolder">{$uitzender@key} - {$uitzender.uitzender}</label>
																</div><!-- /col -->
																<div class="col-md-8">

																	<table class="table-uitzender" data-uitzender="{$uitzender@key}">
                                                                        {foreach $uitzender.facturen as $f}
																			<tr>
																				<td style="width: 20px">
																					<input name="factuur[{$f.factuur_id}]" data-bedrag="{$f.bedrag_incl}" type="checkbox" class="mr-2 checkbox-factuur" id="{$f.factuur_nr}" style="top:2px; position: relative"/>
																				</td>
																				<td style="width: 50px" class="text-right">{$f.factuur_nr}</td>
																				<td style="width: 80px" class="text-right">{$f.bedrag_incl|number_format:2:',':'.'}</td>
																				<td style="width: 30px"></td>
																				<td style="width: 400px; font-size: 11px" class="">{$f.inlener}</td>
																			</tr>
                                                                        {/foreach}
																		<tfoot>
																			<tr style="border-top: 1px solid #DEDEDE" class="font-weight-bold">
																				<td></td>
																				<td class="text-right">totaal</td>
																				<td class="text-right td-totaal">0,00</td>
																				<td></td>
																				<td></td>
																			</tr>
																		</tfoot>
																	</table>

																</div>
															</div>
															<!-- /row -->
                                                        {/foreach}
													</div>
												</div>
                                            {/foreach}
                                        {/foreach}
                                    {/if}

								</div><!-- /col -->

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| 4 weken facturen
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-4weken'}show active{/if}" id="tab-4weken">

                                    {if isset($facturen.4w)}
                                        {foreach $facturen.4w as $jaar}
                                            {foreach $jaar as $periode}
												<div class="div-periode">
													<div class="row bg-primary pt-2 font-weight-bolder" style="font-size: 16px">
														<div class="col-md-12">
															<input type="checkbox" class="mr-2 checkbox-periode" id="{$jaar@key}-{$periode@key}" style="top:1px; position: relative"/>
															<label for="{$jaar@key}-{$periode@key}" class="font-weight-bolder">{$periode@key|string_format:"%02d"} - {$jaar@key}</label>
														</div><!-- /col -->
													</div>
													<!-- /row -->

                                                    {foreach $periode as $uitzender}
														<div class="row mb-2 py-2 div-uitzender" style="background-color: #F1F2F3">
															<div class="col-md-4" style="padding-left: 40px">
																<input data-periode="{$periode@key}" type="checkbox" class="mr-2 checkbox-uitzender" id="{$uitzender@key}-{$periode@key}" style="top:2px; position: relative"/>
																<label for="{$uitzender@key}-{$periode@key}" class="font-weight-bolder">{$uitzender@key} - {$uitzender.uitzender}</label>
															</div><!-- /col -->
															<div class="col-md-7">

																<table class="table-uitzender" data-uitzender="{$uitzender@key}">
                                                                    {foreach $uitzender.facturen as $f}
																		<tr>
																			<td style="width: 20px">
																				<input name="factuur[{$f.factuur_id}]" data-bedrag="{$f.bedrag_incl}" type="checkbox" class="mr-2 checkbox-factuur" id="{$f.factuur_nr}" style="top:2px; position: relative"/>
																			</td>
																			<td style="width: 50px" class="text-right">{$f.factuur_nr}</td>
																			<td style="width: 130px" class="text-right">{$f.bedrag_incl|number_format:2:',':'.'}</td>
																		</tr>
                                                                    {/foreach}
																	<tfoot>
																		<tr style="border-top: 1px solid #DEDEDE" class="font-weight-bold">
																			<td></td>
																			<td class="text-right">totaal</td>
																			<td class="text-right td-totaal">0,00</td>
																		</tr>
																	</tfoot>
																</table>

															</div>
														</div>
														<!-- /row -->

                                                    {/foreach}
												</div>
                                            {/foreach}
                                        {/foreach}
                                    {/if}

								</div><!-- /col -->

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| maand facturen
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-maand'}show active{/if}" id="tab-maand">

                                    {if isset($facturen.m)}
                                        {foreach $facturen.m as $jaar}
                                            {foreach $jaar as $periode}
												<div class="div-periode">
													<div class="row bg-primary pt-2 font-weight-bolder" style="font-size: 16px">
														<div class="col-md-12">
															<input type="checkbox" class="mr-2 checkbox-periode" id="{$jaar@key}-{$periode@key}" style="top:1px; position: relative"/>
															<label for="{$jaar@key}-{$periode@key}" class="font-weight-bolder">{$periode@key|string_format:"%02d"} - {$jaar@key}</label>
														</div><!-- /col -->
													</div>
													<!-- /row -->

                                                    {foreach $periode as $uitzender}
														<div class="row mb-2 py-2 div-uitzender" style="background-color: #F1F2F3">
															<div class="col-md-4" style="padding-left: 40px">
																<input data-periode="{$periode@key}" type="checkbox" class="mr-2 checkbox-uitzender" id="{$uitzender@key}-{$periode@key}" style="top:2px; position: relative"/>
																<label for="{$uitzender@key}-{$periode@key}" class="font-weight-bolder">{$uitzender@key} - {$uitzender.uitzender}</label>
															</div><!-- /col -->
															<div class="col-md-7">

																<table class="table-uitzender" data-uitzender="{$uitzender@key}">
                                                                    {foreach $uitzender.facturen as $f}
																		<tr>
																			<td style="width: 20px">
																				<input name="factuur[{$f.factuur_id}]" data-bedrag="{$f.bedrag_incl}" type="checkbox" class="mr-2 checkbox-factuur" id="{$f.factuur_nr}" style="top:2px; position: relative"/>
																			</td>
																			<td style="width: 50px" class="text-right">{$f.factuur_nr}</td>
																			<td style="width: 130px" class="text-right">{$f.bedrag_incl|number_format:2:',':'.'}</td>
																		</tr>
                                                                    {/foreach}
																	<tfoot>
																		<tr style="border-top: 1px solid #DEDEDE" class="font-weight-bold">
																			<td></td>
																			<td class="text-right">totaal</td>
																			<td class="text-right td-totaal">0,00</td>
																		</tr>
																	</tfoot>
																</table>

															</div>
														</div>
														<!-- /row -->

                                                    {/foreach}
												</div>
                                            {/foreach}
                                        {/foreach}
                                    {/if}

								</div><!-- /col -->

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| sepa bestanden
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-sepa'}show active{/if}" id="tab-sepa">

									<table class="table">
										<thead>
											<th style="width: 40px">ID</th>
											<th>Bestand</th>
											<th style="width: 140px">Betalingen</th>
											<th style="width: 140px">Bedrag</th>
											<th>Aangemaakt</th>
											<th>Door</th>
											<th></th>
										</thead>
										<tbody>
											{foreach $sepas as $sepa}
												<tr>
													<td>{$sepa.file_id}</td>
													<td>
														<a target="_blank" href="overzichten/margebetalingen/downloadsepa/{$sepa.file_id}">sepa_marge_{$sepa.timestamp|date_format: '%Y_%m_%d'}.xml</a>
													</td>
													<td>{$sepa.sepa_entries}</td>
													<td>€ {$sepa.sepa_totaal|number_format:2:',':'.'}</td>
													<td>{$sepa.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
													<td>{$sepa.user}</td>
												</tr>
											{/foreach}
										</tbody>

									</table>

								</div><!-- /col -->

							</div>
						</div>
					</div><!-- /row -->
			</form>

			<script>
                {literal}

				$('.checkbox-factuur').on('click', function()
				{
					toggleFactuur($(this));
					totaalTelling();
				});

				$('.checkbox-uitzender').on('click', function()
				{
					toggleFacturenUitzender($(this));
					totaalTelling();
				});

				$('.checkbox-periode').on('click', function()
				{
					toggleFacturenPeriode($(this));
					totaalTelling();
				});

				//alles optellen
				function totaalTelling()
				{
					let totaal = 0;

					$('.table-uitzender').each(function(index)
					{
						$table = $(this);
						totaal += parseFloat($table.find('.td-totaal').html());
					})

					totaal = totaal * -1;
					$('.sepa-totaal').html(totaal.toLocaleString('nl-NL'));
				}

				//facturen periode aan/uit vinken
				function toggleFacturenPeriode($checkbox)
				{
					checked = $checkbox.prop('checked');

					$divperiode = $checkbox.closest('.div-periode');

					$divperiode.find('.div-uitzender').each(function(index)
					{
						$divuitzender = $(this);
						$checkboxuitzender = $divuitzender.find('.checkbox-uitzender');
						$checkboxuitzender.prop('checked', checked);

						toggleFacturenUitzender($checkboxuitzender);
					});

				}

				//facturen uitzender aan/uit vinken
				function toggleFacturenUitzender($checkbox)
				{
					checked = $checkbox.prop('checked');
					$div = $checkbox.closest('.div-uitzender');
					$table = $div.find('table');

					$table.find('.checkbox-factuur').each(function(index)
					{
						$(this).prop('checked', checked);
					});

					//totaal uitzender optellen
					tabelTotaal($table);
				}

				//factuur aan/uit vinken
				function toggleFactuur($checkbox)
				{
					checked = $checkbox.prop('checked');

					$tr = $checkbox.closest('tr');

					//totaal uitzender optellen
					tabelTotaal($checkbox.closest('table'));
				}

				//totaal van de tabel berekenen
				function tabelTotaal($table)
				{
					let tabelTotaal = 0;
					$table.find('.checkbox-factuur').each(function(index)
					{
						if( $(this).prop('checked') )
						{
							tabelTotaal += parseFloat($(this).data('bedrag'));
							$(this).closest('tr').addClass('font-weight-bold');
						}
						else
							$(this).closest('tr').removeClass('font-weight-bold');

					});

					$table.find('.td-totaal').html(tabelTotaal.toFixed(2));
				}

                {/literal}
			</script>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}