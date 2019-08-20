{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}

{block "content"}
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
									<a href="javascript:void(0)" class="dropdown-item" data-value="w" data-vi-action="setTijdvak">Week</a>
									<a href="javascript:void(0)" class="dropdown-item" data-value="4w" data-vi-action="setTijdvak">4 Weken</a>
									<a href="javascript:void(0)" class="dropdown-item" data-value="m" data-vi-action="setTijdvak">Maand</a>
								</div>
							</li>
							<li class="list-inline-item dropdown" data-ajax-list="true"data-value="30">
								<a href="javascript:void(0)" class="btn btn-link text-default dropdown-toggle" data-toggle="dropdown">
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
							<a href="javascript:void(0)" class="nav-link">
								<span>1001Tafelkleden.com</span>
							</a>
							<a href="javascript:void(0)" class="nav-link">
								<span>4you Personeelsdiensten</span>
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
				<div class="card-body p-0">


						<div class="col-xxl-2 col-lg-3 p-0">

							<div class="ajax-wait mt-4 mb-4" style="text-align: center; display: none"><i
										class="icon-spinner2 spinner mr-1"></i></div>

							<ul class="vi-list mt-2 vi-list-werknemers list-1" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Bruijn de, Sierd (S) (14003)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
							</ul>

							<ul class="vi-list mt-2 vi-list-werknemers list-2" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Beers, Ivo (I.J.) (14005)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
								<li class="vi-list-item"><span>Wijnen, Marcel (I.L.M.) (15003)</li>
							</ul>

							<ul class="vi-list mt-2 vi-list-werknemers list-3" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Beers, Ivo (I.J.) (14005)</li>
								<li class="vi-list-item"><span>Bruijn de, Sierd (S) (14003)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
								<li class="vi-list-item"><span>Wijnen, Marcel (I.L.M.) (15003)</li>
								<li class="vi-list-item"><span>Zwiers, Karel (K.I.M.) (15010)</li>
							</ul>

						</div><!-- /col -->
						<div class="col-xxl-8 col-lg-7 p-0 vi-tabs" style="display: none">

							<ul class="nav nav-tabs nav-tabs-bottom ">
								<li class="nav-item"><a href="#bottom-tab1" class="nav-link active show"
								                        data-toggle="tab">Uren</a></li>
								<li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Kilometers</a>
								</li>
								<li class="nav-item"><a href="#bottom-tab3" class="nav-link" data-toggle="tab">Vergoedingen</a>
								</li>
								<li class="nav-item"><a href="#bottom-tab4" class="nav-link" data-toggle="tab">Reserveringen</a>
								</li>
								<li class="nav-item"><a href="#bottom-tab5" class="nav-link" data-toggle="tab">Inhoudingen</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane fade active show" id="bottom-tab1">

								</div>

								<div class="tab-pane fade" id="bottom-tab2">

								</div>

								<div class="tab-pane fade" id="bottom-tab3">

								</div>

								<div class="tab-pane fade" id="bottom-tab4">

								</div>

								<div class="tab-pane fade" id="bottom-tab5">

								</div>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}