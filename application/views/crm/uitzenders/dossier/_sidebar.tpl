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
						{if $uitzender->complete == 1}
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/overzicht/{$uitzender->uitzender_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
									<span>Overzicht</span>
								</a>
							</li>
						{/if}
						<li class="nav-item">
							<a href="crm/uitzenders/dossier/bedrijfsgegevens/{$uitzender->uitzender_id}" class="nav-link {if $active == 'bedrijfsgegevens'}active{/if}">
								<span>
									{if $uitzender->bedrijfsgegevens_complete == NULL}
										<i class="icon-checkbox-unchecked2 mr-1"></i>
									{else}
										{if $uitzender->complete == 0}
											{if $uitzender->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-1"></i>{/if}
											{if $uitzender->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-1"></i>{/if}
										{/if}
									{/if}
									Bedrijfsgegevens
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a {if $uitzender->bedrijfsgegevens_complete != NULL}href="crm/uitzenders/dossier/emailadressen/{$uitzender->uitzender_id}"{/if} class="nav-link {if $uitzender->bedrijfsgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'emailadressen'}active{/if}">
								<span>
									{if $uitzender->emailadressen_complete == NULL}
										<i class="icon-checkbox-unchecked2 mr-1"></i>
									{else}
										{if $uitzender->complete == 0}
											{if $uitzender->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-1"></i>{/if}
											{if $uitzender->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-1"></i>{/if}
										{/if}
									{/if}
									Emailinstellingen
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a {if $uitzender->emailadressen_complete != NULL}href="crm/uitzenders/dossier/factuurgegevens/{$uitzender->uitzender_id}"{/if} class="nav-link {if $uitzender->emailadressen_complete == NULL}nav-link-disabled{/if} {if $active == 'factuurgegevens'}active{/if}">
								<span>
									{if $uitzender->factuurgegevens_complete == NULL}
										<i class="icon-checkbox-unchecked2 mr-1"></i>
									{else}
										{if $uitzender->complete == 0}
											{if $uitzender->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-1"></i>{/if}
											{if $uitzender->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-1"></i>{/if}
										{/if}
									{/if}
									Factuurgegevens
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a {if $uitzender->factuurgegevens_complete != NULL}href="crm/uitzenders/dossier/contactpersonen/{$uitzender->uitzender_id}"{/if} class="nav-link {if $uitzender->factuurgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'contactpersonen'}active{/if}">
								<span>
									{if $uitzender->contactpersoon_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-1"></i>
									{else}
										{if $uitzender->complete == 0}
											{if $uitzender->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-1"></i>{/if}
											{if $uitzender->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-1"></i>{/if}
										{/if}
									{/if}
									Contactpersonen
								</span>
							</a>
						</li>
						{if $uitzender->complete == 1 }
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
						{/if}
						<!-- /main -->
					</ul>
				</div>
				<!-- /main navigation -->

			</div>
		</div>
		<!-- /sidebar content -->

	</div>
	<!-- /main sidebar  -->
