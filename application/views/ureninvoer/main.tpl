{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}

{block "content"}
	<script src="recources/js/textFit.js"></script>
	<script src="recources/js/verloning_invoer/main.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/templates.js?{$time}"></script>
	<script>
        {literal}

        {/literal}
	</script>
	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

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

				<!-- Main navigation -->
				<div class="card-body p-0">
					<div class="card-header bg-transparent p-2">

						<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
							<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
								<div class="text-uppercase font-size-xs line-height-xs">Tijdvak</div>
							</li>
						</ul>

						<ul class="list-inline list-inline-condensed mb-0">
							<li class="list-inline-item dropdown pl-0" data-ajax-list="true" data-value="w">
								<a href="javascript:void(0)" class="btn btn-link text-left text-default dropdown-toggle pl-2" data-toggle="dropdown" style="width: 100px;">
									Week
								</a>
								<div class="dropdown-menu">
									<a href="javascript:void(0)" class="dropdown-item" data-value="w" data-vi-action="setTijdvak">
										Week
									</a>
									<a href="javascript:void(0)" class="dropdown-item" data-value="4w" data-vi-action="setTijdvak">
										4 Weken
									</a>
									<a href="javascript:void(0)" class="dropdown-item" data-value="m" data-vi-action="setTijdvak">
										Maand
									</a>
								</div>
							</li>
							<li class="list-inline-item dropdown" data-ajax-list="true" data-value="30">
								<a href="javascript:void(0)" class="btn btn-link text-default dropdown-toggle"
								   data-toggle="dropdown">
									31
								</a>
								<div class="dropdown-menu">
									<a href="javascript:void(0)" class="dropdown-item" data-id="30">30</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="29">29</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="28">28</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="27">27</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="26">26</a>
								</div>
							</li>
						</ul>
					</div>

					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<li class="nav-item-header font-weight-bolder">
							<div class="text-uppercase font-size-xs line-height-xs">Inleners</div>
						</li>
						<li class="nav-item">
							<a href="javascript:void(0)" class="nav-link vi-list-item vi-list-item-active">
								<span>1001Tafelkleden.com</span>
							</a>
							<a href="javascript:void(0)" class="nav-link">
								<span>4you Personeelsdiensten</span>
							</a>
							<a href="javascript:void(0)" class="nav-link">
								<span>AH Recruitment B.V.</span>
							</a>
							<a href="javascript:void(0)" class="nav-link">
								<span>Ridon stijgerbouw</span>
							</a>
							<a href="javascript:void(0)" class="nav-link">
								<span>Limburg Uitzenden</span>
							</a>
						</li>
						<!-- /main -->
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
					<h5 class="card-title">1001Tafelkleden.com</h5>

					<div class="header-elements">
						<button type="button" class="btn btn-light mr-1">
							<i class="far fa-file-pdf mr-1"></i> Factuur voorbeeld
						</button>
						<button type="button" class="btn btn-success">
							<i class="far fa-file-pdf mr-1"></i> Factuur genereren
						</button>
					</div>
				</div>

				<!-- tabs 1 -->
				<div class="nav-tabs-responsive bg-light border-top">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
						<li class="nav-item">
							<a href="#tab1" class="nav-link active" data-toggle="tab">
								<i class="icon-menu7 mr-1"></i> Overzicht
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab2" class="nav-link" data-toggle="tab">
								<i class="far fa-clock mr-1"></i> Ureninvoer
							</a>
						</li>
						<li class="nav-item">
							<a href="#tab3" class="nav-link" data-toggle="tab">
								<i class="icon-attachment mr-1"></i> Bijlages
							</a>
						</li>
					</ul>
				</div>

				<!-- card  body-->
				<div class="card-body p-0">

					<div class="tab-content">
						<div class="tab-pane fade active show" id="tab1">

							<table class="vi-table-werknemer-overzicht">
								<tr>
									<td class="pr-4" style="width: 400px">
										<h6 class="media-title font-weight-semibold" style="font-size: 14px">
											<a href="#">19886 -  Cruz Tavarez van Tigchelhoven, Vincent (V.D.)</a>
										</h6>
										<ul class="list-inline list-inline-dotted text-danger mb-2">
											<li class="list-inline-item">Contract niet ondertekend</li>
										</ul>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">UREN</th>
											</tr>
											<tr>
												<td>40</td>
												<td>Uren</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Overuren 150%</td>
											</tr>
											<tr>
												<td>40</td>
												<td>Toeslag 5%</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">Kilometers</th>
											</tr>
											<tr>
												<td>800 km</td>
												<td>€ 152,00</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">Vergoedingen</th>
											</tr>
											<tr>
												<td>Kilometergeld CAO</td>
												<td>€ 125,85</td>
											</tr>
											<tr>
												<td>Koffiegeld</td>
												<td>€ 15,00</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">Reserveringen</th>
											</tr>
											<tr>
												<td>Vakantiegeld</td>
												<td>€ 500,00</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class="pr-4">
										<h6 class="media-title font-weight-semibold" style="font-size: 14px">
											<a href="#">14458 -  Davids, Willem-Jan (W.J.G.)</a>
										</h6>
										<ul class="list-inline list-inline-dotted text-danger mb-2">
										</ul>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">UREN</th>
											</tr>
											<tr>
												<td>40</td>
												<td>Uren</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Overuren 150%</td>
											</tr>
											<tr>
												<td>40</td>
												<td>Toeslag 5%</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">Kilometers</th>
											</tr>
											<tr>
												<td>800 km</td>
												<td>€ 152,00</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">Vergoedingen</th>
											</tr>
											<tr>
												<td>Kilometergeld CAO</td>
												<td>€ 125,85</td>
											</tr>
											<tr>
												<td>Koffiegeld</td>
												<td>€ 15,00</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="vi-table-werknemer-detail">
											<tr>
												<th colspan="2">Reserveringen</th>
											</tr>
											<tr>
												<td>Vakantiegeld</td>
												<td>€ 500,00</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

						</div>

						<div class="tab-pane fade" id="tab2">

							<div class="card-header bg-white pb-0 pt-sm-0 pr-sm-0 pl-2 header-elements-inline justify-content-start">
								<h6 class="card-title mr-3" style="font-size:14px; margin-bottom: -10px;">
									<div class="fit-text vi-title-name">19886 - Baggermans, (J.H.P.)</div>
								</h6>
								<div class="header-elements">
									<ul class="nav nav-tabs nav-tabs-bottom nav-tabs-primary mt-2" style="margin-bottom: -1px">
										<li class="nav-item">
											<a href="#bottom-tab1" class="nav-link active show" data-toggle="tab">
												Uren
											</a>
										</li>
										<li class="nav-item">
											<a href="#bottom-tab2" class="nav-link" data-toggle="tab">
												Kilometers
											</a>
										</li>
										<li class="nav-item">
											<a href="#bottom-tab3" class="nav-link" data-toggle="tab">
												Vergoedingen
											</a>
										</li>
										<li class="nav-item">
											<a href="#bottom-tab4" class="nav-link" data-toggle="tab">
												Reserveringen
											</a>
										</li>
										<li class="nav-item">
											<a href="#bottom-tab5" class="nav-link" data-toggle="tab">
												Inhoudingen
											</a>
										</li>
									</ul>
								</div>
							</div>

							<div class="card-body pt-2 pl-2 media">

								<div style="width: 265px;" class="">
									<ul class="vi-list vi-list-werknemers" style="font-size: 12px">
										<li class="vi-list-item">
											<span>14002 - Cruz Tavarez van Tigchelhoven, (B.)</span></li>
										<li class="vi-list-item"><span>14005 - Beers, (I.J.)</li>
										<li class="vi-list-item"><span>15001 - Otten, (W.J.B.W)</li>
										<li class="vi-list-item"><span>15003 - Wijnen, (I.L.M.)</li>
									</ul>
								</div>

								<div class="media-body pl-1">
									<div class="tab-content">
										<div class="tab-pane fade active show" id="bottom-tab1">
											<table class="table-vi-uren">
												<thead>
													<tr>
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
													<tr>
														<td>33</td>
														<td>ma</td>
														<td>12-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
													<tr>
														<td>33</td>
														<td>di</td>
														<td>13-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
													<tr>
														<td>33</td>
														<td>wo</td>
														<td>14-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
													<tr>
														<td>33</td>
														<td>do</td>
														<td>15-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
													<tr>
														<td>33</td>
														<td>vr</td>
														<td>16-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
													<tr class="tr-weekend">
														<td>33</td>
														<td>za</td>
														<td>17-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
													<tr class="tr-weekend">
														<td>33</td>
														<td>zo</td>
														<td>18-08-2019</td>
														<td>
															<select class="form-control">
																<option>Uren</option>
																<option>Overuren 150%</option>
															</select>
														</td>
														<td>
															<input type="text" class="form-control text-right" placeholder="0:00" style="width: 40px">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
														<td>
															<input type="text" class="form-control">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="tab-pane fade" id="bottom-tab2">
											2
										</div>
										<div class="tab-pane fade" id="bottom-tab3">
											3
										</div>
									</div>

								</div>
							</div>


						</div>
						<div class="tab-pane fade" id="tab3">


						</div>
					</div>

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}