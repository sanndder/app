{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Dashboard{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-3">

					<!----------------- Gegevens --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Uw gegevens</span>
						</div>

						<div class="card-body">

							<table>
								<tr>
									<td class="pr-4">Personeelsnummer</td>
									<td class="font-weight-semibold">{$werknemer.werknemer_id}</td>
								</tr>
								<tr>
									<td>Achternaam</td>
									<td class="font-weight-semibold">{$werknemer.achternaam}</td>
								</tr>
								<tr>
									<td>Tussenvoegsel</td>
									<td class="font-weight-semibold">{$werknemer.tussenvoegsel}</td>
								</tr>
								<tr>
									<td>Voorletters</td>
									<td class="font-weight-semibold">{$werknemer.voorletters}</td>
								</tr>
								<tr>
									<td>Voornaam</td>
									<td class="font-weight-semibold">{$werknemer.voornaam}</td>
								</tr>
								<tr><td colspan="2" style="height: 15px;"></td></tr>
								<tr>
									<td>Straat</td>
									<td class="font-weight-semibold">{$werknemer.straat} {$werknemer.huisnummer} {$werknemer.huisnummer_toevoeging}</td>
								</tr>
								<tr>
									<td>Postcode & Plaats</td>
									<td class="font-weight-semibold">{$werknemer.postcode} {$werknemer.plaats}</td>
								</tr>
								<tr><td colspan="2" style="height: 15px;"></td></tr>
								<tr>
									<td>Telefoon</td>
									<td class="font-weight-semibold">{$werknemer.telefoon}</td>
								</tr>
								<tr>
									<td>Email</td>
									<td class="font-weight-semibold">{$werknemer.email}</td>
								</tr>
							</table>

						</div>
					</div>


					<!----------------- Reserveringen --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Reserveringen per {if isset($stand.datum)}{$stand.datum|date_format: '%d-%m-%Y'}{/if}</span>
						</div>

						<div class="card-body">

							<table>
								<tr>
									<td class="pr-5">Vakantiegeld</td>
									<td class="text-right">€ {if isset($stand.vakantiegeld)}{$stand.vakantiegeld|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Vakantieuren F12</td>
									<td class="text-right">€ {if isset($stand.vakantieuren_F12)}{$stand.vakantieuren_F12|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Feestdagen</td>
									<td class="text-right">€ {if isset($stand.feestdagen)}{$stand.feestdagen|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Kort verzuim</td>
									<td class="text-right">€ {if isset($stand.kort_verzuim)}{$stand.kort_verzuim|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
							</table>

						</div>
					</div>

					<!----------------- Reserveringen --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Documenten</span>
						</div>

						<div class="card-body">

							<table>
								<tr>
									<td class="pr-5">

										<div class="d-flex align-items-center">
											<div class="mr-3">
												<a href="{$base_url}/recources/docs/Personeelsgids.pdf" target="_blank" class="btn bg-teal-400 btn-icon">
													<i class="icon-file-empty"></i>
												</a>
											</div>
											<div>
												<a href="{$base_url}/recources/docs/Personeelsgids.pdf" target="_blank" class="text-default font-weight-semibold letter-icon-title">Personeelsgids</a>
											</div>
										</div>

									</td>
								</tr>
							</table>

						</div>
					</div>

				</div>


			    <!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-9">

					<!----------------- Gegevens --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Laatste loonstroken</span>
						</div>

						<div class="card-body">

							<table class="table table-striped text-nowrap datatable-basic no-footer" data-order="[[4,&quot;desc&quot; ]]">
								<thead>
									<tr role="row">
										<th style="width: 75px">Tijdvak</th>
										<th style="width: 75px">Jaar</th>
										<th style="width: 75px">Periode</th>
										<th>Bestand</th>
										<th>Van</th>
										<th>Tot</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    {if $loonstroken != NULL}
                                        {foreach $loonstroken as $loonstrook}
											<tr role="row" class="odd">
												<td>{$loonstrook.tijdvak}</td>
												<td>{$loonstrook.jaar}</td>
												<td>{$loonstrook.periode}</td>
												<td>
													<a target="_blank" href="werknemer/loonstroken/downloadloonstrook/{$loonstrook.werknemer_id}/{$loonstrook.loonstrook_id}">
														loonstrook_{$loonstrook.jaar}_{$loonstrook.periode}.pdf
													</a>
												</td>
												<td>{$loonstrook.date_start|date_format: '%d-%m-%Y'}</td>
												<td>{$loonstrook.date_end|date_format: '%d-%m-%Y'}</td>
												<td></td>
											</tr>
                                        {/foreach}
                                    {/if}
								</tbody>
							</table>


						</div>
					</div>

				</div><!-- /col -->
			</div><!-- /row -->
			<!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}