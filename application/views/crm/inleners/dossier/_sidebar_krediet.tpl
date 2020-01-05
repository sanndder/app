	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Menu</span>
			<a href="#" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->


		<!-- Sidebar content -->
		<div class="sidebar-content">
			<div class="card card-sidebar-mobile">

				{* knoppen boven het menu, speciale annuleer button voor nieuwe aanmeldingen*}
				<div class="card-header bg-transparent p-0">
					<div class="d-flex justify-content-between sidebar-buttons">

						{* vorige/vorige alleen waneer alles compleet*}
						<a href="crm/inleners" class="btn border-0 w-100 text-warning">
							<i class="icon-cross mr-1"></i>
							Annuleren
						</a>

					</div>
				</div>

				<!-- Main navigation -->
				<div class="card-body p-0">

					<ul class="nav nav-sidebar" data-nav-type="accordion">


						<!-- li Krediet -->
						<li class="nav-item">
							<a href="crm/inleners/dossier/kredietoverzicht/k{$bedrijfsgegevens.id}" class="nav-link {if $active == 'kredietoverzicht'}active{/if}">
								<i class="icon-stats-dots mr-2"></i>Kredietaanvraag
							</a>
						</li>


					</ul>
				</div>
				<!-- /main navigation -->

			</div>
		</div>
		<!-- /sidebar content -->

	</div>
	<!-- /main sidebar  -->
