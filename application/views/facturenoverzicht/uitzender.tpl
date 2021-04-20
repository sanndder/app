{extends file='../layout.tpl'}
{block "title"}Facturen & Marge{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Facturen & Marge{/block}
{assign "ckeditor" "true"}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}

			<div class="card">

				<div class="card-header bg-light pb-0 pt-sm-0 header-elements-sm-inline" style="padding-left: 9px">
					<div class="header-elements">
						<ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
							{if $jaren !== NULL}
								{foreach $jaren as $j}
								<li class="nav-item">
									<a href="facturenoverzicht/uitzender/{$j}" class="nav-link {if $jaar == NULL || $jaar == $j}active{/if}">
                                        {$j}
									</a>
								</li>
	                            {/foreach}
                            {/if}
						</ul>
					</div>
				</div>


                {if $facturen === NULL}
					<div class="table-responsive">
						<div class="p-4 font-italic">Geen facturen gevonden</div>
					</div>
                {else}
					<div class="table-responsive">
						<table class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th style="width: 25px;">Jaar</th>
									<th style="width: 25px;">Periode</th>
									<th>Inlener</th>
									<th style="width: 120px" class="text-right">Factuur nr</th>
									<th style="width: 120px" class="text-right">Verkoop (€)</th>
									<th style="width: 120px" class="text-right">Kosten (€)</th>
									<th style="width: 120px" class="text-right">Factuur nr</th>
									<th style="width: 120px" class="text-right">Marge (€)</th>
									<th style="width: 140px">Voldaan op</th>
									<th style="width: 25px"></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
                                {assign 'periode' '-1'}
                                {assign 'jaar' '-1'}
                                {assign 'totaal_verkoop' '0'}
                                {assign 'totaal_kosten' '0'}
                                {assign 'totaal_marge' '0'}
                                {foreach $facturen as $f}
                                    {*regel voor overzicht*}
                                    {if ($periode != $f.verkoop.periode && $periode != -1)}
										<tr style="background-color: #E8E8E8;">
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1">Totaal</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1">{$periode}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1">
												<a target="_blank" href="facturenoverzicht/weekoverzicht/{$f.verkoop.tijdvak}/{$jaar}/{$periode}">
													<i class="icon-file-pdf mr-1"></i>overzicht_periode_{$periode}.pdf
												</a>
											</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder">€ {$totaal_verkoop|number_format:2:',':'.'}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder">€ {$totaal_kosten|number_format:2:',':'.'}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder">€ {$totaal_marge|number_format:2:',':'.'}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
										</tr>
                                        {$totaal_verkoop = 0}
                                        {$totaal_kosten = 0}
                                        {$totaal_marge = 0}
                                    {/if}
                                    {$periode = $f.verkoop.periode}
                                    {$jaar = $f.verkoop.jaar}
                                    {$totaal_verkoop = $totaal_verkoop + $f.verkoop.bedrag_incl}
                                    {$totaal_kosten = $totaal_kosten + $f.verkoop.kosten_incl}
                                    {if isset($f.marge.bedrag_incl)}
                                        {$totaal_marge = $totaal_marge + $f.marge.bedrag_incl}
                                    {/if}
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
                                            {if isset($f.marge.factuur_nr)}{$f.marge.factuur_nr}{/if}
										</td>
										<td class="text-right">
                                            {if isset($f.marge.factuur_nr)}
												<a target="_blank" href="facturatie/factuur/view/{$f.marge.factuur_id}">
													€ {$f.marge.bedrag_incl|number_format:2:',':'.'}
												</a>
                                            {/if}
										</td>
										<td>
                                            {if isset($f.marge.factuur_nr)}
                                                {if $f.marge.voldaan == 1}
													<span class="text-success font-weight-bold">
					                                <i class="icon-check mr-1"></i>
					                                {$f.marge.voldaan_op|date_format: '%d-%m-%Y'}
				                                </span>
                                                {else}
													-
                                                {/if}
                                            {/if}
										</td>
										<td>
											<ul class="list-inline mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown">
														<i class="icon-menu7"></i></a>

													<div class="dropdown-menu dropdown-menu-right">
                                                        {if isset($f.marge.factuur_nr)}
															<a target="_blank" href="facturatie/factuur/download/{$f.marge.factuur_id}" class="dropdown-item">
																<i class="icon-file-download"></i> Download
															</a>
                                                        {/if}
                                                        {if $f.verkoop.to_factoring_on == NULL && $f.verkoop.send_on == NULL && $f.verkoop.age < 61}
														<a href="facturenoverzicht/uitzender/{$jaar}?del={$f.verkoop.factuur_id}" class="dropdown-item">
															<i class="icon-cross2"></i> Verwijderen
														</a>
                                                        {/if}
													</div>
												</li>
											</ul>
										</td>
										<td></td>
									</tr>
                                    {*regel voor overzicht*}
                                    {if $f@last }
										<tr style="background-color: #E8E8E8;">
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1">Totaal</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1">{$periode}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1">
												<a target="_blank" href="facturenoverzicht/weekoverzicht/{$f.verkoop.tijdvak}/{$jaar}/{$periode}">
													<i class="icon-file-pdf mr-1"></i>overzicht_periode_{$periode}.pdf
												</a>
											</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder">€ {$totaal_verkoop|number_format:2:',':'.'}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder">€ {$totaal_kosten|number_format:2:',':'.'}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1 text-right font-weight-bolder">€ {$totaal_marge|number_format:2:',':'.'}</td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
											<td style="border-top: 1px solid #666; border-bottom: 1px solid #666" class="py-1"></td>
										</tr>
                                        {$totaal_verkoop = 0}
                                        {$totaal_kosten = 0}
                                        {$totaal_marge = 0}
                                    {/if}
                                {/foreach}
							</tbody>
						</table>
					</div>
                {/if}

			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}