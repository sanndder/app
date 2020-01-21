{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/textFit.js"></script>
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/templates.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/main.js?{$time}"></script>
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
											<a data-value="2" href="javascript:void(0)" class="btn btn-link text-left text-default dropdown-toggle pl-2 vi-periode" data-toggle="dropdown" style="width: 100px;">
												02
											</a>
											<div class="dropdown-menu">
												<a href="javascript:void(0)" class="dropdown-item" data-value="3" data-vi-action="setPeriode">
													03
												</a>
												<a href="javascript:void(0)" class="dropdown-item" data-value="4" data-vi-action="setPeriode">
													04
												</a>
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
					<ul class="nav nav-sidebar vi-list-inleners" data-nav-type="accordion">

					</ul>
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

					<div class="header-elements">

					</div>
				</div>

				<!------ tabs -------------------------------------------------------------------------------------------------------------------------------->
				<div class="nav-tabs-responsive bg-light border-top">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0 vi-tabs">
						<li class="nav-item">
							<a href="#tab-overzicht" class="nav-link active" data-toggle="tab">
								<i class="icon-menu7 mr-1"></i> Overzicht
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab-ureninvoer" class="nav-link" data-toggle="tab">
								<i class="far fa-clock mr-1"></i> Ureninvoer
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab-bijlages" class="nav-link" data-toggle="tab">
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

							<table class="vi-table-werknemer-overzicht">

							</table>
						</div>

                        {****** ureninvoer *****************************************************}
						<div class="tab-pane tab-pane-main fade" id="tab-ureninvoer">

							<div class="card-header bg-white pb-0 pt-sm-0 pr-sm-0 pl-2 header-elements-inline justify-content-start">
								<h6 class="card-title mr-3" style="font-size:14px; margin-bottom: -10px;">
									<div class="fit-text vi-title-name">Selecteer een werknemer</div>
								</h6>
								<div class="header-elements">
									<ul class="nav nav-tabs nav-tabs-bottom nav-tabs-primary mt-2" style="margin-bottom: -1px">
										<li class="nav-item">
											<a href="#sub-uren" class="nav-link active show" data-toggle="tab">
												Uren
											</a>
										</li>
										<li class="nav-item">
											<a href="#sub-kilometers" class="nav-link" data-toggle="tab">
												Kilometers
											</a>
										</li>
										<li class="nav-item">
											<a href="#sub-vergoedingen" class="nav-link" data-toggle="tab">
												Vergoedingen
											</a>
										</li>
										<li class="nav-item">
											<a href="#sub-reserveringen" class="nav-link" data-toggle="tab">
												Reserveringen
											</a>
										</li>
										<li class="nav-item">
											<a href="#sub-inhoudingen" class="nav-link" data-toggle="tab">
												Inhoudingen
											</a>
										</li>
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

											<table class="table-vi-uren" style="display: none">
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
											kilometers
										</div>

                                        {****** Invoer: vergoedingen *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-vergoedingen">
											vergoedingen
										</div>

                                        {****** Invoer: reserveringen *****************************************************}
										<div class="tab-pane tab-pane-sub fade" id="sub-reserveringen">
											reserveringen
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
						<div class="tab-pane tab-pane-main  fade" id="tab-bijlages">

							<div class="p-4 font-italic">Bijlages</div>

						</div>
					</div>

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}