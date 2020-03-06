{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$zzp->naam}{/block}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/zzp/plaatsing.js?{$time}"></script>
    {include file='crm/zzp/dossier/_sidebar.tpl' active='plaatsingen'}


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
													<select required name="uitzender_id" class="form-control select-search" {if isset($plaatsingen) && count($plaatsingen) > 0 } disabled {/if}>
														<option value="">Selecteer een uitzender</option>
                                                        {if $uitzenders !== NULL}
                                                            {foreach $uitzenders as $u}
																<option {if $zzp_uitzender.uitzender_id == $u@key} selected{/if} value="{$u@key}">{$u@key} - {$u}</option>
                                                            {/foreach}
                                                        {/if}
													</select>
												</td>
												<td>
													<button type="submit" name="set" value="set_uitzender" class="btn btn-outline-success btn-sm" {if  isset($plaatsingen) && count($plaatsingen) > 0 } disabled {/if}>
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
                            {elseif $zzp->uitzenderID() == NULL }
								<i>Plaats werknemer bij een uitzender</i>
                            {else}

								<!--------------------------------------------- Nieuwe Plaatsing ------------------------------------------------->
								<form id="plaatsing" method="post" action="">

									<fieldset>
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Plaatsingen toevoegen</legend>
									</fieldset>

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

                                    {* start plaasting *}
									<div class="row mt-3">
										<div class="col-xl-2 pt-1">Start plaatsing</div>
										<div class="col-xl-6">

											<input autocomplete="off" required style="width: 125px;" class="form-control pickadate-start-plaatsing" type="text" name="start_plaatsing">

										</div>
									</div>

                                    {* button *}
									<div class="row mt-3">
										<div class="col-xl-2 pt-1">
											<button type="submit" class="btn btn-success" name="set" value="set_plaatsing">
												<i class="icon-plus-circle2 mr-2"></i>Plaatsing toevoegen
											</button>
										</div>
									</div>

								</form>
								<!--------------------------------------------- Plaatsing overzicht ------------------------------------------------->
                                {if isset($plaatsingen) && count($plaatsingen) > 0 }

	                                <input type="hidden" name="zzp"_id" value="{$zzp->zzp_id}"/>

	                                <fieldset class="mt-4">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Plaatsingen</legend>
									</fieldset>
									<table class="table">
										<thead>
											<tr>
												<th colspan="2">Inlener</th>
												<th>Stardatum</th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
                                            {foreach $plaatsingen as $p}
												<tr style="background-color: #F3F3F3">
													<td style="width: 25px;">{$p.inlener_id}</td>
													<td>
														<a href="{$base_url}/crm/inleners/dossier/overzicht/{$p.inlener_id}">
                                                            {$p.inlener}
														</a>
													</td>
													<td>{$p.start_plaatsing|date_format: '%d-%m-%Y'}</td>
													<td>
														<a class="text-danger" href="{$base_url}/crm/zzp/dossier/plaatsingen/{$zzp->id}/?delplaatsing={$p.plaatsing_id}" onclick="return confirm('Plaasting verwijderen?')">
															<i class="icon-trash mr-1"></i>verwijderen
														</a>
													</td>
													<td></td>
												</tr>
												<tr>
													<td colspan="4">

														<!--------------------------------------------- urentypes ------------------------------------------------->
                                                        {if isset($p.urentypes) && $p.urentypes != NULL}
															<table>
																<thead>
																	<tr>
																		<th class="p-1 pb-2">Actief</th>
																		<th class="p-1 pb-2">Urentype</th>
																		<th class="p-1 pb-2 pl-4"></th>
																		<th class="p-1 pb-2 pl-3">Verkooptarief</th>
																		<th class="p-1 pb-2 pl-3">Tarief ZZP'er</th>
																		<th class="p-1 pb-2 pl-3">Marge</th>
																	</tr>
																</thead>
																<tbody>
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
																			<td class="p-1 pl-4 text-right" style="width: 55px;">
																				<div class="status">
																					<i class="spinner icon-spinner2" style="display: none"></i>
																					<i class="icon-check text-green" style="display: none"></i>
																					<i class="icon-warning2 text-warning" style="display: none"></i>
																				</div>
																			</td>
																			<td class="p-1 pl-3">
																				<input style="width: 75px" name="" value="{$u.verkooptarief|number_format:2:',':'.'}" type="text" class="form-control text-right update-verkooptarief"/>
																			</td>
																			<td class="p-1 pl-3">
																				<input style="width: 75px" name="" value="{$u.uurtarief|number_format:2:',':'.'}" type="text" class="form-control text-right update-uurtarief"/>
																			</td>
																			<td class="p-1 pl-3">
																				<input style="width: 75px" name="" value="{$u.marge|number_format:2:',':'.'}" type="text" class="form-control text-right update-marge"/>
																			</td>
																		</tr>

                                                                    {/foreach}
																</tbody>
															</table>
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