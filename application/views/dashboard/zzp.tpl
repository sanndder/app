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
									<td class="pr-4">ID nummer</td>
									<td class="font-weight-semibold">{$zzp.zzp_id}</td>
								</tr>
								<tr>
									<td>Achternaam</td>
									<td class="font-weight-semibold">{$zzp.achternaam}</td>
								</tr>
								<tr>
									<td>Tussenvoegsel</td>
									<td class="font-weight-semibold">{$zzp.tussenvoegsel}</td>
								</tr>
								<tr>
									<td>Voorletters</td>
									<td class="font-weight-semibold">{$zzp.voorletters}</td>
								</tr>
								<tr>
									<td>Voornaam</td>
									<td class="font-weight-semibold">{$zzp.voornaam}</td>
								</tr>
								<tr><td colspan="2" style="height: 15px;"></td></tr>
								<tr>
									<td>Straat</td>
									<td class="font-weight-semibold">{$zzp.straat} {$zzp.huisnummer} {$zzp.huisnummer_toevoeging}</td>
								</tr>
								<tr>
									<td>Postcode & Plaats</td>
									<td class="font-weight-semibold">{$zzp.postcode} {$zzp.plaats}</td>
								</tr>
								<tr><td colspan="2" style="height: 15px;"></td></tr>
								<tr>
									<td>Telefoon</td>
									<td class="font-weight-semibold">{$zzp.telefoon}</td>
								</tr>
								<tr>
									<td>Email</td>
									<td class="font-weight-semibold">{$zzp.email}</td>
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
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Laatste facturen</span>
						</div>

						<div class="card-body">

							<table class="table table-striped text-nowrap datatable-basic no-footer" data-order="[[4,&quot;desc&quot; ]]">
								<thead>
									<tr role="row">
										<th style="width: 25px;">Jaar</th>
										<th style="width: 25px;">Periode</th>
										<th style="width: 100px" class="text-right">Factuur nr</th>
										<th style="width: 120px" class="text-right">Bedrag (€)</th>
										<th style="width: 120px" class="text-right">Vervaldatum</th>
										<th style="width: 25px"></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    {if $facturen != NULL}
                                        {foreach $facturen as $f}
	                                        <tr>
		                                        <td>{$f.jaar}</td>
		                                        <td>{$f.periode}</td>
		                                        <td class="text-right">
                                                    {$f.factuur_nr}
		                                        </td>
		                                        <td class="text-right">
			                                        <a target="_blank" href="crm/zzp/factuur/view/{$f.zzp_id}/{$f.factuur_id}">
				                                        € {$f.bedrag_incl|number_format:2:',':'.'}
			                                        </a>
		                                        </td>
		                                        <td class="text-right">
                                                    {$f.verval_datum|date_format: '%d-%m-%Y'}
		                                        </td>
		                                        <td>

		                                        </td>
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