	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Instellingen menu</span>
			<a href="#" class="sidebar-mobile-expand">
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
					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<li class="nav-item-header font-weight-bolder">
							<div class="text-uppercase font-size-xs line-height-xs">Algemene instellingen</div>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/users" class="nav-link {if $active == 'users'}active{/if}">
								<span>Gebruikers</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/minimumloon" class="nav-link {if $active == 'minimumloon'}active{/if}">
								<span>Minimumloon</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/feestdagen" class="nav-link {if $active == 'feestdagen'}active{/if}">
								<span>Feestdagen</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/urentypes" class="nav-link {if $active == 'urentypes'}active{/if}">
								<span>Urentypes</span>
							</a>
						</li>
						<li class="nav-item-header font-weight-bolder">
							<div class="text-uppercase font-size-xs line-height-xs">Instellingen entiteiten</div>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/bedrijfsgegevens" class="nav-link {if $active == 'bedrijfsgegevens'}active{/if}">
								<span>Bedrijfsgegevens</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/av" class="nav-link {if $active == 'av'}active{/if}">
								<span>Algemene voorwaarden</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/documentenoverzicht" class="nav-link {if $active == 'documenten'}active{/if}">
								<span>Documenten</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="instellingen/werkgever/bankrekeningen" class="nav-link {if $active == 'bankrekeningen'}active{/if}">
								<span>Bankrekeningen</span>
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

