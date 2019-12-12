	<!-- Main sidebar -->
	<div class="sidebar sidebar-light sidebar-main sidebar-expand-lg align-self-start">

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
						{if $uitzender->complete == 1}
						<a href="crm/uitzenders/dossier/{$method}/{$uitzender->prev.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Vorige: {$uitzender->prev.id} - {$uitzender->prev.bedrijfsnaam}">
							<i class="icon-arrow-left12"></i>
						</a>
						<a href="crm/uitzenders" class="btn border-0">
							<i class="icon-undo2 mr-1"></i>
							Terug naar uitzenders
						</a>
						<a href="crm/uitzenders/dossier/{$method}/{$uitzender->next.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Volgende: {$uitzender->next.id} - {$uitzender->next.bedrijfsnaam}">
							<i class="icon-arrow-right13"></i>
						</a>

						{* annuleren bij nieuwe aanmelding*}
						{else}
							<a href="crm/uitzenders" class="btn border-0 w-100 text-warning">
								<i class="icon-cross mr-1"></i>
								Annuleren
							</a>
						{/if}

					</div>
				</div>

				<!-- Main navigation -->
				<div class="card-body p-0">

					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- li Overzicht -->
						{if $uitzender->complete == 1}
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/overzicht/{$uitzender->uitzender_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
									<span>
										<i class="icon-home5 mr-2"></i>Overzicht
									</span>
								</a>
							</li>
						{/if}

						<!-- li Contactpersonen, verplaatsen naar einde lijst wanneer nieuwe aanmelding -->
						<li class="nav-item {if $uitzender->complete != 1}order-4{/if}">
							<a {if $uitzender->factuurgegevens_complete != NULL}href="crm/uitzenders/dossier/contactpersonen/{$uitzender->uitzender_id}"{/if} class="nav-link {if $uitzender->factuurgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'contactpersonen'}active{/if}">
									{* afwijkende icons voor nieuwe aanmelding *}
									{if $uitzender->contactpersoon_complete == NULL}
										<i class="icon-checkbox-unchecked2 mr-2"></i>
									{else}
										{if $uitzender->complete == 0}
											{if $uitzender->contactpersoon_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
											{if $uitzender->contactpersoon_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
										{else}
											{* standaard icon *}
											<i class="icon-address-book3 mr-2"></i>
										{/if}
									{/if}
								Contactpersonen
							</a>
						</li>

						{if $uitzender->complete == 1 }
							<!-- li Notities -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/notities/{$uitzender->uitzender_id}" class="nav-link {if $active == 'notities'}active{/if}">
									<i class="icon-pencil mr-2"></i>Notities
								</a>
							</li>

							<!-- li Documenten -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/documenten/{$uitzender->uitzender_id}" class="nav-link {if $active == 'documenten'}active{/if}">
									<i class="icon-file-text2 mr-2"></i>Documenten
								</a>
							</li>

							<!-- li Facturen -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/facturen/{$uitzender->uitzender_id}" class="nav-link {if $active == 'facturen'}active{/if}">
									<i class="icon-coin-euro mr-2"></i>Facturen
								</a>
							</li>

							<!-- li Inleners -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/inleners/{$uitzender->uitzender_id}" class="nav-link {if $active == 'inleners'}active{/if}">
									<i class="icon-user-tie mr-2"></i>Inleners
								</a>
							</li>

							<!-- li Werknemers -->
							<li class="nav-item">
								<a href="crm/uitzenders/dossier/werknemers/{$uitzender->uitzender_id}" class="nav-link {if $active == 'werknemers'}active{/if}">
									<i class="icon-user mr-2"></i>Werknemers
								</a>
							</li>

							<!-- Header Instellingen -->
							<li class="nav-item-header">Instellingen</li>


							<!-- li Algemene instellingen -->
							<li class="nav-item {if $uitzender->complete != 1}order-1{/if}">
								<a href="crm/uitzenders/dossier/algemeneinstellingen/{$uitzender->uitzender_id}" class="nav-link {if $active == 'algemeneinstellingen'}active{/if}">
									{* standaard icon *}
									<i class="icon-cog mr-2"></i>
									Algemene instellingen
								</a>
							</li>
						{/if}


						<!-- li Bedrijfsgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $uitzender->complete != 1}order-1{/if}">
							<a href="crm/uitzenders/dossier/bedrijfsgegevens/{$uitzender->uitzender_id}" class="nav-link {if $active == 'bedrijfsgegevens'}active{/if}">
								{* afwijkende icons voor nieuwe aanmelding *}
								{if $uitzender->bedrijfsgegevens_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								{else}
									{if $uitzender->complete == 0}
										{if $uitzender->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
										{if $uitzender->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
									{else}
										{* standaard icon *}
										<i class="icon-cog mr-2"></i>
									{/if}
								{/if}
								Bedrijfsgegevens
							</a>
						</li>

						<!-- li Emailinstellingen, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $uitzender->complete != 1}order-2{/if}">
							<a {if $uitzender->bedrijfsgegevens_complete != NULL}href="crm/uitzenders/dossier/emailadressen/{$uitzender->uitzender_id}"{/if} class="nav-link {if $uitzender->bedrijfsgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'emailadressen'}active{/if}">
								{* afwijkende icons voor nieuwe aanmelding *}
								{if $uitzender->emailadressen_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								{else}
									{if $uitzender->complete == 0}
									{if $uitzender->emailadressen_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
										{if $uitzender->emailadressen_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
									{else}
										{* standaard icon *}
										<i class="icon-cog mr-2"></i>
									{/if}
								{/if}
								Emailinstellingen
							</a>
						</li>

						<!-- li Factuurgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $uitzender->complete != 1}order-3{/if}">
							<a {if $uitzender->emailadressen_complete != NULL}href="crm/uitzenders/dossier/factuurgegevens/{$uitzender->uitzender_id}"{/if} class="nav-link {if $uitzender->emailadressen_complete == NULL}nav-link-disabled{/if} {if $active == 'factuurgegevens'}active{/if}">
								{* afwijkende icons voor nieuwe aanmelding *}
								{if $uitzender->factuurgegevens_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								{else}
									{if $uitzender->complete == 0}
										{if $uitzender->factuurgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
										{if $uitzender->factuurgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
									{else}
										{* standaard icon *}
										<i class="icon-cog mr-2"></i>
									{/if}
								{/if}
								Factuurgegevens
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
