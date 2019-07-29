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

				<!-- Main navigation -->
				<div class="card-body p-0">
					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<li class="nav-item p-0">
							<a href="crm/uitzenders" class="nav-link">
								<i class="mi-arrow-back"></i>
								<span>Terug naar uitzenders</span>
							</a>
						</li>
						<li class="nav-item-divider m-0"></li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/overzicht/{$uitzender->uitzender_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
								<span>Overzicht</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/bedrijfsgegevens/{$uitzender->uitzender_id}" class="nav-link {if $active == 'bedrijfsgegevens'}active{/if}">
								<span>Bedrijfsgegevens</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/factuurgegevens/{$uitzender->uitzender_id}" class="nav-link {if $active == 'factuurgegevens'}active{/if}">
								<span>Factuurgegevens</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/contactpersonen/{$uitzender->uitzender_id}" class="nav-link {if $active == 'contactpersonen'}active{/if}">
								<span>Contactpersonen</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/documenten/{$uitzender->uitzender_id}" class="nav-link {if $active == 'documenten'}active{/if}">
								<span>Documenten</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/notities/{$uitzender->uitzender_id}" class="nav-link {if $active == 'notities'}active{/if}">
								<span>Notities</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/facturen/{$uitzender->uitzender_id}" class="nav-link {if $active == 'facturen'}active{/if}">
								<span>Facturen</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/inleners/{$uitzender->uitzender_id}" class="nav-link {if $active == 'inleners'}active{/if}">
								<span>Inleners</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/werknemers/{$uitzender->uitzender_id}" class="nav-link {if $active == 'werknemers'}active{/if}">
								<span>Werknemers</span>
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
