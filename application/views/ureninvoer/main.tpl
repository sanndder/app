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
	<script src="recources/js/api/bing.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/templates.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoer.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoeruren.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoerkm.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoervergoedingen.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoeret.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/invoerbijlages.js?{$time}"></script>
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
											<a href="javascript:void(0)" class="btn btn-link text-default dropdown-toggle vi-jaar" data-toggle="dropdown">2020</a>
											<div class="dropdown-menu">
												<a href="javascript:void(0)" class="dropdown-item" data-id="2020">2020</a>
											</div>
										</li>
									</ul>
								</td>
								<td>
									<ul class="list-inline list-inline-condensed vi-list-periodes mb-0">
										<li class="list-inline-item dropdown pl-0" data-ajax-list="true" data-value="w">
											<a data-value="5" href="javascript:void(0)" class="btn btn-link text-left text-default dropdown-toggle pl-2 vi-periode" data-toggle="dropdown" style="width: 100px;">

											</a>
											<div class="dropdown-menu">

											</div>
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
                    {else}
						<input type="hidden" class="inlener-id" value="{$inlener_id}">
                    {/if}
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

					<div class="header-elements vi-factuur-buttons" style="display: none !important">

						<a target="_blank" href="ureninvoer/ureninvoer/factuur" class="btn btn-light mr-1 voorbeeld">
							<i class="far fa-file-pdf mr-1"></i> Factuur voorbeeld
						</a>

						<button type="button" class="btn btn-success" data-vi-action="factuurGenereren">
							<i class="far fa-file-pdf mr-1"></i> Factuur genereren
						</button>

					</div>
				</div>

				<!------ tabs -------------------------------------------------------------------------------------------------------------------------------->
				<div class="nav-tabs-responsive bg-light border-top">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0 vi-tabs">
						<li class="nav-item">
							<a href="#tab-overzicht" class="tab-main nav-link active" data-toggle="tab">
								<i class="icon-menu7 mr-1"></i> Overzicht
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
												<button type="button" class="btn alpha-warning text-warning-800 btn-icon ml-2 btn-sm" data-vi-action="clear">
													<i class="icon-trash mr-1"></i>
													Invoer wissen
												</button>
											</div>

											<table class="table-vi-km vi-input mt-3" style="display: none">

												<thead>
													<tr>
														<th style="width: 35px">Kilometers</th>
														<th>Opmerking</th>
														<th>Doorbelasten</th>
														<th>Project</th>
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
														<th colspan="3">Vaste vergoedingen</th>
														<th>Doorbelasten</th>
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
															<input style="width: 80px" type="text" class="form-control text-right" name="vergoeding-huisvesting" />
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
												</tbody>
											</table>

										</div>

                                        {****** Invoer: reserveringen *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-reserveringen">
											<i>Geen reserveringen in systeem</i>
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
													<th>Project</th>
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

								</div><!-- /col -->
							</div><!-- /row -->

						</div>
					</div>

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}