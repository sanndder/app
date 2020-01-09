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
                    {if $werknemer->complete == 1}
						<a href="crm/werknemers/dossier/{$method}/{$werknemer->prev.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Vorige: {$werknemer->prev.id} - {$werknemer->prev.naam}">
							<i class="icon-arrow-left12"></i>
						</a>
						<a href="crm/werknemers" class="btn border-0">
							<i class="icon-undo2 mr-1"></i>
							Terug naar werknemers
						</a>
						<a href="crm/werknemers/dossier/{$method}/{$werknemer->next.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Volgende: {$werknemer->next.id} - {$werknemer->next.naam}">
							<i class="icon-arrow-right13"></i>
						</a>
                        {* annuleren bij nieuwe aanmelding*}
                    {else}
						<a href="crm/werknemers" class="btn border-0 w-100 text-warning">
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
                    {if $werknemer->complete == 1}
						<li class="nav-item">
							<a href="crm/werknemers/dossier/overzicht/{$werknemer->werknemer_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
									<span>
										<i class="icon-home5 mr-2"></i>Overzicht
									</span>
							</a>
						</li>
                    {/if}

					<!-- li Documenten, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $werknemer->complete != 1}order-2{/if}">
						<a {if $werknemer->gegevens_complete != NULL}href="crm/werknemers/dossier/documenten/{$werknemer->werknemer_id}"{/if} class="nav-link {if $werknemer->gegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'documenten'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $werknemer->documenten_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $werknemer->complete == 0}
                                    {if $werknemer->gegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $werknemer->gegevens_complete == 1}
										<i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
                                {else}
                                    {* standaard icon *}
									<i class="icon-file-text2 mr-2"></i>
                                {/if}
                            {/if}
							Documenten
						</a>
					</li>

                    {if $werknemer->complete == 1 }
						<!-- li plaatsing -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/plaatsingen/{$werknemer->werknemer_id}" class="nav-link {if $active == 'plaatsingen'}active{/if}">
								<i class="far fa-handshake mr-2"></i>Plaatsingen
							</a>
						</li>
						<!-- li Notities -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/notities/{$werknemer->werknemer_id}" class="nav-link {if $active == 'notities'}active{/if}">
								<i class="icon-pencil mr-2"></i>Notities
							</a>
						</li>
						<!-- li reserveringen -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/reserveringen/{$werknemer->werknemer_id}" class="nav-link {if $active == 'reserveringen'}active{/if}">
								<i class="icon-file-stats mr-2"></i>Reserveringen
							</a>
						</li>
						<!-- li ziekmeldingen -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/ziekmeldingen/{$werknemer->werknemer_id}" class="nav-link {if $active == 'ziekmeldingen'}active{/if}">
								<i class="icon-folder-plus2 mr-2"></i>Ziekmeldingen
							</a>
						</li>
						<!-- li Urenbriefjes -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/urenbriefjes/{$werknemer->werknemer_id}" class="nav-link {if $active == 'urenbriefjes'}active{/if}">
								<i class="icon-alarm mr-2"></i>Urenbriefjes
							</a>
						</li>
						<!-- li Loonstroken -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/loonstroken/{$werknemer->werknemer_id}" class="nav-link {if $active == 'loonstroken'}active{/if}">
								<i class="icon-stack-text mr-2"></i>Loonstroken
							</a>
						</li>
						<!-- li loonbeslagen -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/loonbeslagen/{$werknemer->werknemer_id}" class="nav-link {if $active == 'loonbeslagen'}active{/if}">
								<i class="icon-coin-euro mr-2"></i>Loonbeslagen
							</a>
						</li>
						<!-- Header Instellingen -->
						<li class="nav-item-header">Instellingen</li>
						<!-- li Algemene instellingen -->
						<li class="nav-item {if $werknemer->complete != 1}order-1{/if}">
							<a href="crm/werknemers/dossier/algemeneinstellingen/{$werknemer->werknemer_id}" class="nav-link {if $active == 'algemeneinstellingen'}active{/if}">
                                {* standaard icon *}
								<i class="icon-cog mr-2"></i>
								Algemene instellingen
							</a>
						</li>
                    {/if}


					<!-- li Gegevens, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $werknemer->complete != 1}order-1{/if}">
						<a href="crm/werknemers/dossier/gegevens/{$werknemer->werknemer_id}" class="nav-link {if $active == 'gegevens'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $werknemer->gegevens_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $werknemer->complete == 0}
                                    {if $werknemer->gegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $werknemer->gegevens_complete == 1}
										<i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
                                {else}
                                    {* standaard icon *}
									<i class="icon-cog mr-2"></i>
                                {/if}
                            {/if}
							Persoonsgegevens
						</a>
					</li>

					<!-- li Dienstverband, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $werknemer->complete != 1}order-3{/if}">
						<a href="crm/werknemers/dossier/dienstverband/{$werknemer->werknemer_id}" class="nav-link {if $werknemer->documenten_complete == NULL}nav-link-disabled{/if}{if $active == 'dienstverband'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $werknemer->dienstverband_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $werknemer->complete == 0}
                                    {if $werknemer->dienstverband_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $werknemer->dienstverband_complete == 1}
										<i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
                                {else}
                                    {* standaard icon *}
									<i class="icon-cog mr-2"></i>
                                {/if}
                            {/if}
							Dienstverband
						</a>
					</li>

					<!-- li Verloning, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $werknemer->complete != 1}order-4{/if}">
						<a href="crm/werknemers/dossier/verloning/{$werknemer->werknemer_id}" class="nav-link {if $werknemer->dienstverband_complete == NULL}nav-link-disabled{/if}{if $active == 'verloning'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $werknemer->verloning_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $werknemer->complete == 0}
                                    {if $werknemer->verloning_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $werknemer->verloning_complete == 1}
										<i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
                                {else}
                                    {* standaard icon *}
									<i class="icon-cog mr-2"></i>
                                {/if}
                            {/if}
							Verloning
						</a>
					</li>

                    {if $werknemer->complete == 1 }
						<!-- li Algemene instellingen -->
						<li class="nav-item">
							<a href="crm/werknemers/dossier/etregeling/{$werknemer->werknemer_id}" class="nav-link {if $active == 'etregeling'}active{/if}">
                                {* standaard icon *}
								<i class="icon-cog mr-2"></i>
								ET-regeling
							</a>
						</li>
                    {/if}

				</ul>
			</div>
			<!-- /main navigation -->

		</div>
	</div>
	<!-- /sidebar content -->

</div>
<!-- /main sidebar  -->
