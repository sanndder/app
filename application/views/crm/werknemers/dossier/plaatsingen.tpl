{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/cao/cao.js?{$time}"></script>
	<script src="recources/js/inlener/verloninginstellingen.js?{$time}"></script>
	<script src="recources/js/werknemer/plaatsing.js?{$time}"></script>
    {include file='crm/werknemers/dossier/_sidebar.tpl' active='plaatsingen'}


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
				<div class="col-xl-12 col-xxl-12">

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

                            {if $user_type == 'werkgever'}
								<!--------------------------------------------- Uitzender ------------------------------------------------->
								<form method="post" action="">
									<fieldset class="mb-4">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Uitzender</legend>

										<table>
											<tr>
												<td class="pr-2" style="width: 500px">
													<select required name="uitzender_id" class="form-control select-search" {if count($plaatsingen) > 0 } disabled {/if}>
														<option value="">Selecteer een uitzender</option>
                                                        {if $uitzenders !== NULL}
                                                            {foreach $uitzenders as $u}
																<option {if $werknemer_uitzender.uitzender_id == $u@key} selected{/if} value="{$u@key}">{$u@key} - {$u}</option>
                                                            {/foreach}
                                                        {/if}
													</select>
												</td>
												<td>
													<button type="submit" name="set" value="set_uitzender" class="btn btn-outline-success btn-sm" {if count($plaatsingen) > 0 } disabled {/if}>
														<i class="icon-check mr-1"></i>Wijzigen
													</button>
												</td>
											</tr>
										</table>

									</fieldset>
								</form>
                            {/if}

                            {if count($inleners) == 0 }
								<i>Geen inleners gevonden voor uitzender</i>
                            {elseif $werknemer->uitzenderID() == NULL }
								<i>Plaats werknemer bij een uitzender</i>
                            {else}

								<!--------------------------------------------- Nieuwe Plaatsing ------------------------------------------------->
								<form id="plaatsing" method="post" action="">

									<input type="hidden" name="werknemer_id" value="{$werknemer->werknemer_id}"/>

									<fieldset>
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Plaatsingen toevoegen</legend>
									</fieldset>

									<div class="alert alert-plaatsing alert-warning alert-styled-left alert-arrow-left" style="display: none"></div>

                                    {* inlener selecteren *}
									<div class="row">
										<div class="col-xl-2 pt-1">Inlener</div>
										<div class="col-xl-6">

											<select required name="inlener_id" class="form-control select-search">
												<option value="">Selecteer een inlener</option>
                                                {if $inleners !== NULL}
                                                    {foreach $inleners as $i}
														<option value="{$i@key}">{$i@key} - {$i.bedrijfsnaam}</option>
                                                    {/foreach}
                                                {/if}
											</select>
										</div>
									</div>

                                    {* CAO selecteren *}
									<div class="row mt-3" style="display: none">
										<div class="col-xl-2 pt-1">CAO</div>
										<div class="col-xl-6">

											<span class="no-cao" style="display: none"><i class="icon-exclamation"></i> <i>Koppel de inlener eerst aan een CAO</i></span>

											<div class="cao-wrapper">
												<select required name="cao_id" class="form-control select-no-search">
													<option value="">Selecteer een CAO</option>
												</select>
											</div>
										</div>
									</div>

                                    {* Loontabel selecteren *}
									<div class="row mt-3" style="display: none">
										<div class="col-xl-2 pt-1">Loontabel</div>
										<div class="col-xl-6">

											<select required name="tabel_id" class="form-control select-no-search">
												<option value="">Selecteer een loontabel</option>
											</select>

										</div>
									</div>

                                    {* Fucntie selecteren *}
									<div class="row mt-3" style="display: none">
										<div class="col-xl-2 pt-1">Functie</div>
										<div class="col-xl-6">

											<select required name="functie_id" class="form-control select-no-search">
												<option value="">Selecteer een functie</option>
											</select>

										</div>
									</div>

                                    {* Schaal selecteren *}
									<div class="row mt-3" style="display: none">
										<div class="col-xl-2 pt-1">Schaal</div>
										<div class="col-xl-6">

											<select required name="schaal_id" class="form-control select-no-search">
												<option value="">Selecteer een schaal</option>
											</select>

										</div>
									</div>

                                    {* Ervaring selecteren *}
									<div class="row mt-3" style="display: none">
										<div class="col-xl-2 pt-1">Ervaring (jaren)</div>
										<div class="col-xl-6">

											<select required name="periodiek_id" class="form-control select-search">
												<option value="">Selecteer aantal jaren ervaring</option>
											</select>

										</div>
									</div>

                                    {* opslaan *}
									<div class="row-uurloon" style="display: none">

                                        {* uurloon aanpassen *}
										<div class="row mt-3">
											<div class="col-xl-2 pt-1">Uurloon</div>
											<div class="col-xl-6">

												<input class="form-control" type="hidden" name="user_type" value="{$user_type}">
												<input class="form-control" type="text" name="uurloon" value="">

											</div>
										</div>

                                        {* start plaasting *}
										<div class="row mt-3">
											<div class="col-xl-2 pt-1">Start plaatsing</div>
											<div class="col-xl-6">

												<input class="form-control pickadate-start-plaatsing" type="text" name="start_plaatsing">

											</div>
										</div>


										<div class="row mt-3">
											<div class="col-xl-2 pt-1">
												<button type="button" class="btn btn-success" name="add_plaatsing" onclick="plaatsing.add()">
													<i class="icon-plus-circle2 mr-2"></i>Plaatsing toevoegen
												</button>
											</div>
										</div>
									</div>

								</form>
								<!--------------------------------------------- Plaatsing overzicht ------------------------------------------------->
                                {if count($plaatsingen) > 0 }
									<fieldset class="mt-4">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Plaatsingen</legend>
									</fieldset>
									<table class="table">
										<thead>
											<tr>
												<th colspan="2">Inlener</th>
												<th class="p-0"></th>
												<th class="pl-1">Factor</th>
												<th>Stardatum</th>
												<th>Uurloon</th>
												<th>CAO</th>
												<th class="d-none d-xl-table-cell">Loontabel</th>
												<th>Functie</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
                                            {foreach $plaatsingen as $p}
												<tr data-id="{$p.plaatsing_id}" style="background-color: #F3F3F3">
													<td style="white-space: nowrap; width: 10px; padding-right: 5px;">
														<a href="{$base_url}/crm/inleners/dossier/overzicht/{$p.inlener_id}">{$p.inlener_id}</a>
													</td>
													<td style="padding-left: 10px;">
														<a href="{$base_url}/crm/inleners/dossier/overzicht/{$p.inlener_id}">
                                                            {$p.inlener}
														</a>
													</td>
													<td class="p-0">
														<div class="status">
															<i class="spinner icon-spinner2" style="display: none"></i>
															<i class="icon-check text-green" style="display: none"></i>
															<i class="icon-warning2 text-warning" style="display: none"></i>
														</div>
													</td>
													<td class="pl-1">
														<select class="form-control change-factor" style="padding: 2px; height: 30px;" {if $user_type != 'werkgever'} disabled{/if}>
                                                            {if isset($p.factoren) && $p.factoren != NULL}
	                                                            {if $p.factor_id == NULL}
		                                                            <option>Selecteer factoren</option>
                                                                {/if}
                                                                {foreach $p.factoren as $f}
																	<option {if $p.factor_id == $f.factor_id} selected{/if} value="{$f.factor_id}">{$f.omschrijving} - {$f.factor_hoog}/{$f.factor_laag}</option>
                                                                {/foreach}
                                                            {/if}
														</select>
													</td>
													<td>{$p.start_plaatsing|date_format: '%d-%m-%Y'}</td>
													<td class="d-flex">
														<div class="mt-2 mr-1">
														€
														</div>
														<input type="text" class="form-control text-right change-uurloon" style="width: 80px" value="{$p.bruto_loon|number_format:2:',':'.'}"  {if $user_type != 'werkgever'} readonly{/if} />
													</td>
													<td>{$p.cao}</td>
													<td class="d-none d-xl-table-cell">{$p.loontabel}</td>
													<td>{$p.functie}</td>
													<td>
														<a class="text-danger" href="{$base_url}/crm/werknemers/dossier/plaatsingen/{$werknemer->id}/?delplaatsing={$p.plaatsing_id}" onclick="return confirm('Plaasting verwijderen?')">
															<i class="icon-trash mr-1"></i>verwijderen
														</a>
													</td>
												</tr>
												<tr>
													<td colspan="5" style="border-top:0; padding-bottom: 25px;">

														{if isset($p.urentypes) && is_array($p.urentypes) && count($p.urentypes) > 0}
														<table>
															<thead>
																<tr>
																	<th class="p-1 pb-2">Actief</th>
																	<th class="p-1 pb-2">Urentype</th>
																	<th class="p-1 pb-2 pl-4"></th>
																	<th class="p-1 pb-2">Verkooptarief</th>
																</tr>
															</thead>
															{foreach $p.urentypes as $u}
															<tr data-id="{$u.id}" class="{if !$u.urentype_active} text-grey-200{/if}">
																<td class="p-1 pt-2">
                                                                    {if $u.default_urentype != 1}
																	<div class="form-check">
																		<label class="form-check-label">
																			<input data-id="{$u.id}" type="checkbox" class="form-input-styled-info toggle-urentype-active" {if $u.urentype_active} checked{/if}>
																		</label>
																		<i class="icon-spinner2 spinner text-primary mr-1" style="display: none; margin-left: -27px">
																	</div>
																	{/if}
																</td>
																<td style="width: 160px" class="p-1">{$u.label}</td>
																<td class="p-1 pl-4 text-right td-status-tarief" style="width: 55px;">
																	<div class="status">
																		<i class="spinner icon-spinner2" style="display: none"></i>
																		<i class="icon-check text-green" style="display: none"></i>
																		<i class="icon-warning2 text-warning" style="display: none"></i>
																	</div>
																</td>
																<td class="p-1">
																	<input style="width: 75px" name="" value="{$u.verkooptarief|number_format:2:',':'.'}" type="text" class="form-control text-right update-verkooptarief" />
																</td>
															</tr>
                                                            {/foreach}
														</table>
                                                        {/if}

													</td>
													<td colspan="3" style="vertical-align: top; border-top:0; padding-bottom: 25px; padding-top: 35px">

														{if $p.document_id === NULL}
															<a class="btn btn-primary btn-sm" href="crm/werknemers/dossier/plaatsingen/{$p.werknemer_id}/?uitzendbevestiging={$p.plaatsing_id}">
																<i class="icon-file-plus mr-1"></i> Uitzendbevestiging maken
															</a>
														{else}
															<a target="_blank" href="documenten/pdf/view/{$p.document_id}" class="font-weight-semibold mt-3">
																<img src="recources/img/icons/pdf.svg" style="height: 25px"> Uitzendbevestiging bekijken
															</a>
														{/if}

													</td>
												</tr>
                                            {/foreach}
										</tbody>
									</table>
                                {/if}
                            {/if}

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}