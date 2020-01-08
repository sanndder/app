{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}

{block "content"}
	<script src="recources/js/textFit.js"></script>
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
									01
								</a>
								<div class="dropdown-menu">
									<a href="javascript:void(0)" class="dropdown-item" data-id="52">52</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="51">51</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="50">50</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="49">49</a>
									<a href="javascript:void(0)" class="dropdown-item" data-id="48">48</a>
								</div>
							</li>
						</ul>
					</div>

					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<li class="nav-item-header font-weight-bolder">
							<div class="text-uppercase font-size-xs line-height-xs">Inleners</div>
						</li>
						<li class="nav-item">
							<a href="javascript:void(0)" class="nav-link vi-list-item">
								<span class="font-italic">Geen inleners gevonden</span>
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
					<h5 class="card-title"><i>Geen invoer mogelijk</i></h5>

					<div class="header-elements">

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

							<div class="p-4 font-italic">Geen data beschikbaar</div>

						</div>

						<div class="tab-pane fade" id="tab2">

							<div class="p-4 font-italic">Geen data beschikbaar</div>

						</div>
						<div class="tab-pane fade" id="tab3">

							<div class="p-4 font-italic">Geen data beschikbaar</div>

						</div>
					</div>

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}