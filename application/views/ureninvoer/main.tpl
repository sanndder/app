{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}
{assign "uploader" "true"}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/textFit.js"></script>
	<script src="recources/js/config.js?{$time}"></script>
	<script>
		let werkgever = '{$werkgever_type}';
	</script>
	<script src="template/global_assets/js/plugins/extensions/jquery_ui/full.min.js"></script>
	<script src="recources/plugins/jquery.ba-throttle-debounce.js"></script>
	<script src="recources/js/api/bing.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/templates.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoer.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoeruren.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoerkm.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoervergoedingen.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoerreserveringen.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoeret.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoerbijlages.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoeraangenomenwerk.js?{$time}"></script>
	<link href="recources/css/verloning_input.css?{$time}" rel="stylesheet" type="text/css">
	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-wide sidebar-expand-md align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="javascript:void(0)" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Instellingen menu</span>
			<a href="javascript:void(0)" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->

		<!-- Sidebar content -->
		<div class="sidebar-content">
			<div class="card card-sidebar-mobile">

				<!------------------------------------------------------------------- linker menu --------------------------------------------------------------------------------------------->
				<div class="card-body p-0">

                    {*************** tijdvak keuze ****************************}
					<div class="card-header bg-transparent p-2">

						<table>
							<tr>
								<td>
									<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
										<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
											<div class="text-uppercase font-size-xs line-height-xs">Jaar</div>
										</li>
									</ul>
								</td>
								<td>
									<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
										<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
											<div class="text-uppercase font-size-xs line-height-xs vi-tijdvak-titel">Week</div>
										</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td class="pr-3">
									<ul class="list-inline list-inline-condensed mb-0">
										<li class="list-inline-item dropdown" data-ajax-list="true" data-value="30">
											<a href="javascript:void(0)" class="btn btn-link text-default dropdown-toggle vi-jaar" data-toggle="dropdown">2021</a>
											<div class="dropdown-menu">
												<a href="javascript:void(0)" class="dropdown-item" data-id="2021">2021</a>
											</div>
										</li>
									</ul>
								</td>
								<td>
									<ul class="list-inline list-inline-condensed vi-list-periodes mb-0">
										<li class="list-inline-item dropdown pl-0" data-ajax-list="true" data-value="w">
											<div class="dropdown-menu">
											</div>
											<a data-value="{$default_periode}" href="javascript:void(0)" class="btn btn-link text-left text-default dropdown-toggle pl-2 vi-periode" data-toggle="dropdown" style="width: 100px;">
                                                {$default_periode}
											</a>
										</li>
									</ul>
								</td>
							</tr>
						</table>

					</div>


                    {*************** Uitzender keuze ****************************}
                    {if $user_type == 'werkgever'}
						<div class="card-header bg-transparent p-2">
							<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
								<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
									<div class="text-uppercase font-size-xs line-height-xs">Uitzender</div>
								</li>
							</ul>

							<select class="form-control select-search uitzender-id" data-vi-action="setUitzender">
								<option>Selecteer een uitzender</option>
                                {foreach $uitzenders as $u}
									<option value="{$u@key}">{$u@key} - {$u}</option>
                                {/foreach}
							</select>

						</div>
                    {/if}

                    {*************** inlener keuze ****************************}
                    {if $user_type == 'werkgever' || $user_type == 'uitzender'}
						<ul class="nav nav-sidebar vi-list-inleners" data-nav-type="accordion">
						</ul>
                    {/if}
                    {if $user_type == 'inlener'}
						<input type="hidden" class="inlener-id" value="{$inlener_id}">
                    {/if}
                    {if $user_type == 'werknemer'}
						<input type="hidden" class="uitzender-id" value="{$uitzender_id}">
						<ul class="nav nav-sidebar vi-list-inleners" data-nav-type="accordion">
							<li class="nav-item">
                                {if $inleners !== NULL}
                                    {foreach $inleners as $i}
										<span data-id="{$i@key}" data-inlener="Inlener E" class="nav-link" data-vi-action="setInlener"><span>{$i}</span></span>
                                    {/foreach}
                                {/if}
							</li>
						</ul>
                    {/if}
					<div class="card-header bg-transparent" style="border-top: 1px solid rgba(0, 0, 0, 0.125); font-size: 11px;">
						<span class="vi-settings-window" style="cursor: pointer">
							<i class="icon-cog mr-1" style="font-size: 11px"></i> Invoer instellingen
						</span>
					</div>
				</div>
				<!-- /main navigation -->

			</div>
		</div>
		<!-- /sidebar content -->

	</div>
	<!-- /main sidebar  -->

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">

				<!-- card  body-->
				<div class="card-header header-elements-inline">
					<h5 class="card-title vi-card-titel"><i>Geen invoer mogelijk</i></h5>

                    {if $user_type != 'werknemer'}
						<div class="header-elements vi-factuur-buttons" style="display: none !important">

							<a target="_blank" href="ureninvoer/ureninvoer/factuur" class="btn btn-light mr-1 voorbeeld">
								<i class="far fa-file-pdf mr-1"></i> Factuur voorbeeld
							</a>

							<button type="button" class="btn btn-success" data-vi-action="factuurGenereren">
								<i class="far fa-file-pdf mr-1"></i> Factuur genereren
							</button>

						</div>
                    {/if}
				</div>

				<!------ tabs -------------------------------------------------------------------------------------------------------------------------------->
				<div class="nav-tabs-responsive bg-light border-top">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0 vi-tabs">
						<li class="nav-item">
							<a href="#tab-overzicht" class="tab-main nav-link active" data-toggle="tab">
								<i class="icon-menu7 mr-1"></i> Overzicht
							</a>
						</li>
						<li class="nav-item vi-tab-aangenomenwerk" style="display:none;">
							<a href="#tab-aangenomenwerk" class="tab-main nav-link" data-toggle="tab">
								<i class="icon-hammer-wrench mr-1"></i> Aangenomen werk
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab-ureninvoer" class="tab-main nav-link" data-toggle="tab">
								<i class="far fa-clock mr-1"></i> Ureninvoer
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab-bijlages" class="tab-main nav-link" data-toggle="tab">
								<i class="icon-attachment mr-1"></i> Bijlages
							</a>
						</li>
					</ul>
				</div>

				<!----- tab content -------------------------------------------------------------------------------------------------------------------------------->
				<div class="card-body p-0">

					<div class="tab-content vi-tab-content">

                        {****** overzicht ******************************************************}
						<div class="tab-pane tab-pane-main fade active show" id="tab-overzicht">

							<div class="p-4 font-italic vi-overzicht-placeholder">Selecteer een inlener</div>

							<table class="vi-table-werknemer-overzicht mt-2">

							</table>
						</div>

                        {****** ureninvoer *****************************************************}
						<div class="tab-pane tab-pane-main fade p-3" id="tab-aangenomenwerk">

							<div class="row">
								<div class="col-md-12 col-xxl-6 vi-aangenomenwerk-body pt-2">

                                    {*
									<table>
										<tr>
											<td class="text-primary text-uppercase font-size-sm font-weight-bold pr-2">Project:</td>
											<td>
												<input type="text" class="form-control vi-input-project" style="width: 250px;">
											</td>
											<td class="pl-5">
												<div class="form-check">
													<label class="form-check-label">
														<input name="actief" value="1" type="checkbox" class="form-input-styled-info vi-input-no-project">
														Geen project meegeven
													</label>
												</div>
											</td>
										</tr>
									</table>*}
                                    {*
																		<fieldset class="mb-3">
																			<legend class="text-primary text-uppercase font-size-sm font-weight-bold">Dienstverband</legend>
																			<table class="vi-aangenomenwerk-regels">
																				<thead>
																					<tr>
																						<th>Omschrijving</th>
																						<th>Bedrag (€)</th>
																					</tr>
																				</thead>
																				<tr>
																					<td class="td-omschrijving">
																						<input name="omschrijving" type="text" class="form-control" value="">
																					</td>
																					<td class="td-bedrag">
																						<input name="bedrag" type="text" class="form-control" value="">
																					</td>
																				</tr>
																			</table>
																		</fieldset>*}

								</div>
							</div>

						</div>

                        {****** ureninvoer *****************************************************}
						<div class="tab-pane tab-pane-main fade" id="tab-ureninvoer">

							<div class="card-header bg-white pb-0 pt-sm-0 pr-sm-0 pl-2 header-elements-inline justify-content-start">
								<h6 class="card-title mr-3" style="font-size:14px; margin-bottom: -10px;">
									<div class="fit-text vi-title-name">Selecteer een {$_werknemer}</div>
								</h6>
								<div class="header-elements">
									<ul class="nav nav-tabs nav-tabs-bottom nav-tabs-primary mt-2" style="margin-bottom: -1px">
										<li class="nav-item">
											<a href="#sub-uren" class="tab-sub nav-link active show" data-toggle="tab">
												Uren
											</a>
										</li>
										<li class="nav-item">
											<a href="#sub-kilometers" class="tab-sub nav-link" data-toggle="tab">
												Kilometers
											</a>
										</li>
                                        {if $user_type == 'uitzender' || $user_type == 'werkgever'}
											<li class="nav-item">
												<a href="#sub-vergoedingen" class="tab-sub nav-link" data-toggle="tab">
													Vergoedingen
												</a>
											</li>
											<li class="nav-item nav-et">
												<a href="#sub-et" class="tab-sub nav-link" data-toggle="tab">
													ET-regeling
												</a>
											</li>
											<li class="nav-item">
												<a href="#sub-reserveringen" class="tab-sub nav-link" data-toggle="tab">
													Reserveringen
												</a>
											</li>
                                        {/if}
                                        {*
										<li class="nav-item">
											<a href="#sub-inhoudingen" class="tab-sub nav-link" data-toggle="tab">
												Inhoudingen
											</a>
										</li>
										*}
									</ul>
								</div>
							</div>

                            {****** Tabs *******}
							<div class="card-body pt-2 pl-2 media">

								<div style="width: 265px;" class="">
									<ul class="vi-list vi-list-werknemers" style="font-size: 12px">
									</ul>
								</div>

								<div class="media-body pl-1">
									<div class="tab-content">

                                        {****** Invoer: Uren *****************************************************}
										<div class="tab-pane tab-pane-sub fade active show" id="sub-uren">

											<div class="navbar navbar-expand-lg navbar-light navbar-component rounded navbar-vi-acties" id="navbar-filter">
												<span class="navbar-text mr-2">Acties:</span>

												<ul class="navbar-nav flex-wrap">
													<li class="nav-item">
														<a href="javascript:void(0)" class="navbar-nav-link vi-action-uren-fill" data-vi-fill-uren="40">
															<i class="icon-sort-time-desc mr-1"></i>
															8 uur per dag
														</a>
													</li>
													<li class="nav-item">
														<a href="javascript:void(0)" class="navbar-nav-link vi-action-del-uren">
															<i class="icon-trash mr-1"></i>
															Ureninvoer wissen
														</a>
													</li>
												</ul>

											</div>

											<table class="table-vi-uren vi-input mt-3" style="display: none">
												<thead>
													<tr>
														<th></th>
														<th>Week</th>
														<th>Dag</th>
														<th>Datum</th>
														<th>Urentype</th>
														<th>Uren</th>
														<th>Project</th>
														<th>Locatie</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
										</div>

                                        {****** Invoer: kilometers *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-kilometers">

											<div class="vi-km-buttons mt-2 mb-2">
                                                {*
												<button type="button" class="btn alpha-primary text-primary-800 btn-icon ml-2 btn-sm" data-vi-action="copyGewerkteDagen">
													<i class="icon-calendar2 mr-1"></i>
													Gewerkte dagen kopiëren
												</button>
												*}

												<div class="navbar navbar-expand-lg navbar-light navbar-component rounded navbar-vi-acties" id="navbar-filter">
													<span class="navbar-text mr-2">Acties:</span>

													<ul class="navbar-nav flex-wrap">
														<li class="nav-item">
															<a href="javascript:void(0)" class="navbar-nav-link" data-vi-action="km-clear">
																<i class="icon-trash mr-1"></i>
																Kilometers wissen
															</a>
														</li>
													</ul>

												</div>

											</div>

											<table class="table-vi-km vi-input mt-3" style="display: none">

												<thead>
													<tr>
														<th style="width: 35px">Kilometers</th>
														<th>Opmerking</th>
														<th>Doorbelasten</th>
														<th class="th-project">Project</th>
														<th>Uitkeren werknemer</th>
														<th></th>
														<th style="width: 150px;"></th>
													</tr>
												</thead>

												<tbody class="table-vi-km-body">

												</tbody>

											</table>

										</div>

                                        {****** Invoer: vergoedingen *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-vergoedingen">

											<table class="mt-2 vi-input vi-table-vergoedingen vi-vergoedingen-vast">
												<thead>
													<tr>
														<th colspan="3">Vaste vergoedingen per uur</th>
														<th>Doorbelasten</th>
														<th class="th-project">Project</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>

											<table class="mt-2 vi-input vi-table-vergoedingen vi-vergoedingen-dag">
												<thead>
													<tr>
														<th colspan="3">Vaste vergoedingen per dag</th>
														<th>Doorbelasten</th>
														<th class="th-project">Project</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>

											<table class="mt-4 vi-input vi-table-vergoedingen vi-vergoedingen-variabel">
												<thead>
													<tr>
														<th colspan="3">Variabele vergoedingen</th>
														<th>Doorbelasten</th>
														<th class="th-project">Project</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>

										</div>

                                        {****** Invoer: ET-regeling *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-et">

											<table class="mt-2 vi-input vi-table-et">
												<thead>
												</thead>
												<tbody>
													<tr>
														<td class="font-size-base" style="padding-right: 25px;">Vergoeding huisvesting</td>
														<td class="d-flex flex-row-reverse">
															<input style="width: 80px" type="text" class="form-control text-right" name="vergoeding-huisvesting"/>
															<div style="font-size: 15px" class="pr-1 pt-1">€</div>
														</td>
													</tr>
													<tr>
														<td class="font-size-base" style="padding-right: 25px;">Vergoeding verschil levensstandaard</td>
														<td class="d-flex">
															<select class="form-control" name="vergoeding-levensstandaard">
																<option></option>
                                                                {foreach $cola as $c}
																	<option value="{$c.bedrag}">{$c.land} - € {$c.bedrag|number_format:2:',':'.'}</option>
                                                                {/foreach}
															</select>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="height: 15px"></td>
													</tr>
													<tr>
														<td class="font-size-base" style="padding-right: 25px;">Maximaal uit te ruilen</td>
														<td class="text-right" style="font-size: 15px">
															€ <span class="vi-et-max"></span>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="height: 15px"></td>
													</tr>
													<tr {if $user_type != 'werkgever'}style="display: none" {/if}>
														<td class="font-size-base" style="padding-right: 25px;">Totaal vergoeding</td>
														<td class="text-right" style="font-size: 15px">
															€ <span class="vi-et-totaal"></span>
														</td>
													</tr>
													<tr>
														<td class="font-size-base" style="padding-right: 25px;">81% uitruil</td>
														<td class="text-right" style="font-size: 15px">
															€ <span class="vi-et-uitruil"></span>
														</td>
													</tr>
												</tbody>
											</table>

										</div>

                                        {****** Invoer: reserveringen *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-reserveringen">

											<h6 class="text-primary">Rekenhulp</h6>
											<table class="mt-2 vi-input vi-table-rekenhulp">
												<thead>
													<tr>
														<th class="pr-2">Uurloon</th>
														<th class="pr-2">x</th>
														<th class="pr-2">Uren</th>
														<th class="pr-2">=</th>
														<th class="pr-2">Bedrag</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<select class="form-control input-uurloon" style="width: 80px">

															</select>
														</td>
														<td>x</td>
														<td>
															<input type="text" class="form-control text-right pr-1 input-uren" style="width: 65px">
														</td>
														<td>=</td>
														<td>
															<input type="text" class="form-control text-right pr-1 output-bedrag" readonly style="width: 65px">
														</td>
													</tr>
												</tbody>
											</table>

											<h6 class="text-primary mt-4">Opvragen reserveringen</h6>
											<table class="mt-2 vi-input vi-table-reserveringen">
												<thead>
													<tr>
														<td></td>
														<th class="pr-3">Huidige stand</th>
														<th class="pr-3">Opgevraagd</th>
														<th class="pr-3" style="display: none">Nieuwe stand</th>
													</tr>
												</thead>
												<tbody>
													<tr class="r-vakantiegeld" data-type="vakantiegeld">
														<td>Vakantiegeld</td>
														<td class="td-stand text-right pr-3">€ <span>0,00</span></td>
														<td class="pr-3 text-right">
															<input type="text" class="td-vraag form-control text-right" style="width: 60px; display: inline-block" value="0,00">
														</td>
														<td class="td-nieuw text-right pr-3" style="display: none">€
															<span>0,00</span></td>
													</tr>
													<tr class="r-vakantieuren_F12" data-type="vakantieuren_F12">
														<td>Vakantieuren Fase A</td>
														<td class="td-stand text-right pr-3">€ <span>0,00</span></td>
														<td class="pr-3 text-right">
															<input type="text" class="td-vraag form-control text-right" style="width: 60px; display: inline-block" value="0,00">
														</td>
														<td class="td-nieuw text-right pr-3" style="display: none">€
															<span>0,00</span></td>
													</tr>
													<tr class="r-kort_verzuim" data-type="kort_verzuim">
														<td>Kort Verzuim</td>
														<td class="td-stand text-right pr-3">€ <span>0,00</span></td>
														<td class="pr-3 text-right">
															<input type="text" class="td-vraag form-control text-right" style="width: 60px; display: inline-block" value="0,00">
														</td>
														<td class="td-nieuw text-right pr-3" style="display: none">€
															<span>0,00</span></td>
													</tr>
													<tr class="r-feestdagen" data-type="feestdagen">
														<td>Feestdagen</td>
														<td class="td-stand text-right pr-3">€ <span>0,00</span></td>
														<td class="pr-3 text-right">
															<input type="text" class="td-vraag form-control text-right" style="width: 60px; display: inline-block" value="0,00">
														</td>
														<td class="td-nieuw text-right pr-3" style="display: none">€
															<span>0,00</span></td>
													</tr>
												</tbody>
											</table>

										</div>

                                        {****** Invoer: inhoudingen *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-inhoudingen">
											inhoudingen
										</div>
									</div>

								</div>
							</div>

						</div>

                        {****** bijlages *******************************************************}
						<div class="tab-pane tab-pane-main fade p-3" id="tab-bijlages">

							<div class="p-2 font-italic vi-bijlages-placeholder">Selecteer een inlener</div>

							<div class="vi-bijlages-input" style="display: none">

								<div class="row">
									<div class="col-md-6">

										<fieldset class="mt-0">
											<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Bestand(en) uploaden</legend>
										</fieldset>

										<div id="upload-error" class="m-0 mb-2"></div>

										<form action="#">
											<input name="file" type="file" id="upload-bijlages" class="file-input" multiple>
										</form>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">

										<fieldset class="mt-5">
											<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Bijlages en Werkbonnen</legend>
										</fieldset>

										<div class="table-vi-bijlages-container" style="display: none">
											<table class="table-vi-bijlages">
												<thead>
													<tr>
														<th style="width: 35px">
															<i class="icon-radio-unchecked text-grey-200" style="padding-left: 14px" data-vi-action="checkAllBijlages"></i>
														</th>
														<th style="width: 35px"></th>
														<th>Bestand</th>
														<th class="th-project">Project</th>
														<th>Grootte</th>
														<th>Geupload op</th>
														<th>Geupload door</th>
														<th></th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>


											<div class="mb-2 mt-3">
												<i class="icon-arrow-up32 mr-2" style="margin-left: 16px"></i> Met geselecteerde:
												<button class="btn btn-light btn-sm ml-2" data-vi-action="delSelectedBijlages">
													<i class="icon-trash mr-2"></i>Bestanden verwijderen
												</button>
											</div>
										</div>

										<div class="table-vi-bijlages-empty">
											<i class="icon-exclamation mr-1"></i> Geen bijlages gevonden
										</div>
									</div>

								</div><!-- /col -->
							</div><!-- /row -->

						</div>
					</div>

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->


		<!-- instellingen scherm -->
		<div id="modal_settings" class="modal fade" tabindex="-1">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Instellingen voor ureninvoer</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">

						<form>

							<div style="border: 1px solid #CCC;" class="px-3 py-2">
								<div class="row">
									<div class="col-md-12 font-weight-bold">
										Bij wisselen van werknemer:
									</div>
								</div>

								<div class="row mt-2">
									<div class="col-md-12  form-inline">
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-input-styled-primary" value="reset" name="werknemer_tab_wissel">
												Terug naar uren tabblad
											</label>
										</div>
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-input-styled-primary" value="remain" name="werknemer_tab_wissel">
												In hetzelfde tabblad blijven
											</label>
										</div>
									</div>
								</div>
							</div>

						</form>

					</div>
					<div class="modal-footer mt-3">
						<button class="btn btn-sm btn-success vi-btn-settings-save">
							<i class="icon-check mr-1"></i>
							Opslaan en herladen
						</button>
						<button class="btn btn-sm btn-light" data-dismiss="modal">
							<i class="icon-cross mr-1"></i>
							Annuleren
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /main content -->


{/block}