{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "select2" "true"}

{block "content"}

	<script src="recources/js/inlener/verloninginstellingen.js?{$time}"></script>

    {include file='crm/inleners/dossier/_sidebar.tpl' active='verloninginstellingen'}
    {include file='crm/inleners/dossier/modals/urentype_toevoegen.tpl'}
    {include file='crm/inleners/dossier/modals/vergoeding_toevoegen.tpl'}


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

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Tabs
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<div class="card-header bg-light pb-0 pt-sm-0 header-elements-sm-inline">
							<div class="header-elements">
								<ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
									<li class="nav-item">
										<a href="#tab-factoren" class="nav-link {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-factoren'}active{/if}" data-toggle="tab">
											Factoren
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-cao" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-cao'}active{/if}" data-toggle="tab">
											CAO
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-urentypes" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-urentypes'}active{/if}" data-toggle="tab">
											Urentypes
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-vergoedingen" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-vergoedingen'}active{/if}" data-toggle="tab">
											Vergoedingen
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-staffelkorting" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-staffelkorting'}active{/if}" data-toggle="tab">
											Staffelkorting
										</a>
									</li>
								</ul>
							</div>
						</div>

						<!-- card  body-->
						<div class="card-body tab-content">

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Factoren
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-factoren'}show active{/if}" id="tab-factoren">

								<fieldset class="mb-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factoren uitzender</legend>

									<table>
										<tr>
											<td style="width: 300px;">{$uitzender.bedrijfsnaam}</td>
											<td style="width: 110px;">{$factoren_uitzender.factor_hoog|number_format:3:',':'.'}</td>
											<td style="width: 110px;">{$factoren_uitzender.factor_laag|number_format:3:',':'.'}</td>
											<td></td>
										</tr>
									</table>

								</fieldset>

								<form method="post" action="">
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factoren toevoegen</legend>

										<table>
											<tr>
												<td style="width: 300px;">Omschrijving</td>
												<td style="width: 110px;">Factor uren</td>
												<td style="width: 110px;">Factor overuren</td>
												<td></td>
											</tr>
											<tr>
												<td class="pr-2">
													<input name="omschrijving" value="" type="text" class="form-control"/>
												</td>
												<td class="pr-2">
													<input name="factor_hoog" value="" type="text" class="form-control text-right"/>
												</td>
												<td class="pr-2">
													<input name="factor_laag" value="" type="text" class="form-control text-right"/>
												</td>
												<td>
													<button type="submit" name="set" value="inleners_factoren" class="btn btn-outline-success btn-sm">
														<i class="icon-plus-circle2 mr-1"></i>Toevoegen
													</button>
												</td>
											</tr>
										</table>
									</fieldset>
								</form>


								<fieldset class="mb-0 mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factoren overzicht</legend>
								</fieldset>
								<table>
									<tr>
										<td style="width: 300px;">Omschrijving</td>
										<td style="width: 110px;">Factor uren</td>
										<td style="width: 110px;">Factor overuren</td>
										<td></td>
									</tr>
                                    {foreach $factoren as $factor}
										<form method="post" action="">
											<tr>
												<td class="pr-2">
													<input name="default_factor[{$factor.factor_id}]" value="{$factor.default_factor}" type="hidden"/>
													<input name="omschrijving[{$factor.factor_id}]" {if $factor.default_factor}readonly{/if} value="{$factor.omschrijving}" type="text" class="form-control"/>
												</td>
												<td class="pr-2">
													<input name="factor_hoog[{$factor.factor_id}]" value="{$factor.factor_hoog|number_format:3:',':'.'}" type="text" class="form-control text-right"/>
												</td>
												<td class="pr-2">
													<input name="factor_laag[{$factor.factor_id}]" value="{$factor.factor_laag|number_format:3:',':'.'}" type="text" class="form-control text-right"/>
												</td>
												<td>
													<button type="submit" name="set" value="inleners_factoren" class="btn btn-outline-success btn-sm">
														<i class="icon-checkmark5"></i>
													</button>
                                                    {if !$factor.default_factor}
														<button onclick="return confirm('Factoren verwijderen?')" type="submit" name="del" value="inleners_factoren" class="btn btn-outline-danger btn-sm">
															<i class="icon-trash"></i>
														</button>
                                                    {/if}
												</td>
											</tr>
										</form>
                                    {/foreach}
								</table>

							</div><!-- /einde tab -->

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| CAO
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-cao'}show active{/if}" id="tab-cao">

								<form method="post" action="">
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Inlener zonder CAO</legend>

										<div class="form-check">
											<label class="form-check-label">
												<input type="checkbox" class="form-input-styled-info">Inlener valt niet onder een CAO
											</label>
											<i class="icon-spinner2 spinner text-primary mr-1" style="display: none; margin-left: -27px"></i>
										</div>

									</fieldset>
								</form>


								<form method="post" action="">
									<fieldset class="mb-3 mt-4">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">CAO toevoegen</legend>

										<table>
											<tr>
												<td class="pr-2" style="width: 500px">
													<select required name="cao_id" class="form-control select-search">
														<option value="">Selecteer een CAO</option>
														{foreach $caos as $cao}
															<option value="{$cao.id}">{$cao.name} (start: {$cao.duration_start|date_format: '%d-%m-%Y'})</option>
														{/foreach}
													</select>
												</td>
												<td>
													<button type="submit" name="set" value="add_cao_to_inlener" class="btn btn-outline-success btn-sm">
														<i class="icon-plus-circle2 mr-1"></i>Toevoegen
													</button>
												</td>
											</tr>
										</table>
									</fieldset>
								</form>


								{*********************************** CAO details **********************************************************}
								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">CAO overzicht</legend>
								</fieldset>

                                {if isset($msg_copy)}{$msg_copy}{/if}

                                {foreach $caos_inlener as $cao}

	                                <fieldset class="mb-0 mt-0">
										<legend class="text-uppercase font-size-sm pl-2 mb-1 font-weight-bold" style="background-color: #EEE">
                                            {$cao.cao_name}
											<a href="{$base_url}/crm/inleners/dossier/verloninginstellingen/{$inlener->inlener_id}?tab=tab-cao&delcao={$cao.cao_id_intern}" class="float-right pr-3" style="color: red" data-popup="tooltip" data-placement="top" data-title="COA verwijderen">
												<i class="icon-trash"></i>
											</a>
										</legend>
									</fieldset>

	                                <div class="row pb-3">
		                                <div class="col-md-3">

			                                <table class="ml-2">
				                                <tr>
					                                <th style="width: 100px;">Ingang</th>
					                                <td>{$cao.duration_start|date_format: '%d-%m-%Y'}</td>
				                                </tr>
				                                <tr>
					                                <th>Einde</th>
					                                <td>{$cao.duration_end|date_format: '%d-%m-%Y'}</td>
				                                </tr>
				                                <tr>
					                                <th>Avv</th>
					                                <td>{if $cao.avv == 1}Ja{else}Nee{/if}</td>
				                                </tr>
				                                <tr>
					                                <td colspan="2" style="height: 20px;"></td>
				                                </tr>
				                                <tr>
					                                <td colspan="2">
						                                <a href="crm/inleners/dossier/verloninginstellingen/{$inlener->inlener_id}?tab=tab-cao&action=copyUrentypesFromCao&cao_code={$cao.code}">
							                                <i class="icon-copy3 mr-1"></i>urentypes overnemen van CAO
						                                </a>
					                                </td>
				                                </tr>
			                                </table>

		                                </div>

		                                {* CAO urentypes*}
		                                <div class="col-md-9">

			                                <table>
				                                <tr>
					                                <th>Urentype</th>
					                                <th>Percentage</th>
					                                <th></th>
				                                </tr>
                                                {foreach $cao.werksoort as $werksoort}
					                                <tr>
						                                <td class="pr-3">{$werksoort.name}</td>
						                                <td>{$werksoort.amount}%</td>
						                                <td class="3"></td>
					                                </tr>
                                                {/foreach}
			                                </table>

		                                </div>
	                                </div>

                                {/foreach}

							</div><!-- /einde tab -->

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Urentypes
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-urentypes'}show active{/if}" id="tab-urentypes">

								<div class="btn-group">
									<button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal_add_urentype">
										<i class="icon-plus-circle2"></i>
										<span class="d-none d-inline-block ml-2">Urentype toevoegen</span>
									</button>
								</div>

								<fieldset class="mb-0 mt-3">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary mb-1">Urentypes voor inlener</legend>
								</fieldset>

								<input type="hidden" class="inlener-id" value="{$inlener->inlener_id}" />

								<table class="table table-striped table-xs">
									<thead>
										<tr>
											<th style="width: 180px">Type</th>
											<th style="width: 120px">Percentage</th>
											<th style="width: 350px">Afwijkende naam</th>
											<th style="width: 135px">Standaard verkooptarief</th>
											<th>Doorbelasten naar uitzender</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
                                        {if is_array($matrix)}
                                            {foreach $matrix as $urentype}
												<tr data-id="{$urentype.inlener_urentype_id}">
													<td>{$urentype.naam}</td>
													<td>{$urentype.percentage|number_format:2:',':'.'}%</td>
													<td>
                                                        {* standaard uren niet aanpasbaar*}
                                                        {if $urentype.default_urentype != 1}
														<input name="" value="{$urentype.label}" type="text" class="form-control input-label" />
                                                        {/if}
													</td>
													<td>
														<input name="" value="{$urentype.standaard_verkooptarief|number_format:2:',':'.'}" type="text" class="form-control text-right input-verkooptarief" />
													</td>
													<td>
														{* standaard uren niet aanpasbaar*}
                                                        {if $urentype.default_urentype != 1}
														<div class="form-check form-check-inline">
															<label class="form-check-label">
																<span>
																	<input {if $urentype.doorbelasten_uitzender == 1}checked{/if} value="1" type="radio" class="form-input-styled" name="doorbelasten_uitzender-{$urentype.inlener_urentype_id}">
																</span>
																Ja
															</label>
														</div>

														<div class="form-check form-check-inline">
															<label class="form-check-label">
																<span>
																	<input {if $urentype.doorbelasten_uitzender == 0}checked{/if} value="0" type="radio" class="form-input-styled" name="doorbelasten_uitzender-{$urentype.inlener_urentype_id}">
																</span>
																Nee
															</label>
														</div>
														{/if}
													</td>
													<td>
														{if $urentype.default_urentype != 1}
														<a class="text-danger" href="{$base_url}/crm/inleners/dossier/verloninginstellingen/{$inlener->inlener_id}?tab=tab-urentypes&delurentype={$urentype.inlener_urentype_id}">
															<i class="icon-trash mr-1"></i>verwijderen
														</a>
                                                        {/if}
													</td>
												</tr>
                                            {/foreach}
                                        {/if}
									</tbody>
								</table>


								<fieldset class="mb-0 mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary mb-0">Urentypes per werknemer</legend>
								</fieldset>

                                {foreach $matrix as $urentype}

									<fieldset class="mb-0 mt-0">
										<legend class="text-uppercase font-size-sm pl-2 mb-1 font-weight-bold" style="background-color: #EEE">
		                                {$urentype.naam} {if $urentype.label != ''}- {$urentype.label}{/if}
										</legend>
									</fieldset>

									<table class="table table-xs mt-0 mb-3">
										<thead>
											<tr>
												<th style="width: 35px">Actief</th>
												<th style="width: 135px">Werknemer ID</th>
												<th style="width: 325px">Werknemer</th>
												<th style="width: 115px">Uurloon</th>
												<th style="width: 75px">Verkooptarief</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
                                            {if isset($urentype.werknemers) && is_array($urentype.werknemers)}
                                                {foreach $urentype.werknemers as $w}
													<tr  class="{if !$w.urentype_active} text-grey-200{/if}">
														<td>
                                                            {if $urentype.default_urentype != 1}
																<div class="form-check">
																	<label class="form-check-label">
																		<input data-id="{$w.id}"  type="checkbox" class="form-input-styled-info toggle-urentype-active" {if $w.urentype_active} checked{/if}>
																	</label>
																	<i class="icon-spinner2 spinner text-primary mr-1" style="display: none; margin-left: -27px">
																</div>
                                                            {/if}
														</td>
														<td>{$w.werknemer_id}</td>
														<td>{$w.werknemer_naam}</td>
														<td>
                                                            € {$w.bruto_loon|number_format:2:',':'.'}
														</td>
														<td>€ {$w.verkooptarief|number_format:2:',':'.'}
														</td>
														<td></td>
													</tr>
                                                {/foreach}
                                            {/if}
										</tbody>
									</table>
                                {/foreach}

							</div>
							<script>
								{literal}

								{/literal}
							</script>

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Vergoedingen
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-vergoedingen'}show active{/if}"" id="tab-vergoedingen" >

							<div class="btn-group">
								<button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal_add_vergoeding">
									<i class="icon-plus-circle2"></i>
									<span class="d-none d-inline-block ml-2">Vergoeding toevoegen</span>
								</button>
							</div>

							<fieldset class="mb-0 mt-3">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary mb-1">Vergoedingen voor inlener</legend>
							</fieldset>

							<table class="table table-striped table-xs">
								<thead>
									<tr>
										<th style="width: 180px">Vergoeding</th>
										<th style="width: 120px">Type</th>
										<th style="width: 140px">Bedrag per uur</th>
										<th style="width: 135px">Doorbelasten</th>
										<th style="width: 190px">Uitkeren aan werknemer</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    {if is_array($werknemervergoedingen)}
                                        {foreach $werknemervergoedingen as $vergoeding}
											<tr>
												<td>{$vergoeding.naam}</td>
												<td>{$vergoeding.vergoeding_type}</td>
												<td>
                                                    {if $vergoeding.vergoeding_type == 'vast'}€ {$vergoeding.bedrag_per_uur|number_format:2:',':'.'}{/if}
												</td>
												<td>
													{if $vergoeding.doorbelasten !== NULL }{$vergoeding.doorbelasten}{else}keuze bij invoer{/if}
												</td>
												<td>
                                                    {if $vergoeding.uitkeren_werknemer == 1 }Ja{else}Nee{/if}
												</td>
												<td>
                                                    {if $urentype.default_urentype != 1}
														<a class="text-danger" href="{$base_url}/crm/inleners/dossier/verloninginstellingen/{$inlener->inlener_id}?tab=tab-vergoedingen&delvergoeding={$vergoeding.inlener_vergoeding_id}">
															<i class="icon-trash mr-1"></i>verwijderen
														</a>
                                                    {/if}
												</td>
											</tr>
                                        {/foreach}
                                    {/if}
								</tbody>
							</table>

							{*
							<fieldset class="mb-0 mt-4">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary mb-0">Urentypes per werknemer</legend>
							</fieldset>
							*}

						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}