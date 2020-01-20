{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/cao/cao.js?{$time}"></script>
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
				<div class="col-xl-10">

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
												<th>CAO</th>
												<th>Loontabel</th>
												<th>Functie</th>
												<th>Uurloon</th>
												<th>Stardatum</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
                                            {foreach $plaatsingen as $p}
												<tr>
													<td style="width: 25px;">{$p.inlener_id}</td>
													<td>
														<a href="{$base_url}/crm/inleners/dossier/overzicht/{$p.inlener_id}">
                                                            {$p.inlener}
														</a>
													</td>
													<td>{$p.cao}</td>
													<td>{$p.loontabel}</td>
													<td>{$p.functie}</td>
													<td>€ {$p.bruto_loon|number_format:2:',':'.'}</td>
													<td>{$p.start_plaatsing|date_format: '%d-%m-%Y'}</td>
													<td>
														<a class="text-danger" href="{$base_url}/crm/werknemers/dossier/plaatsingen/{$werknemer->id}/?delplaatsing={$p.plaatsing_id}" onclick="return confirm('Plaasting verwijderen?')">
															<i class="icon-trash mr-1"></i>verwijderen
														</a>
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