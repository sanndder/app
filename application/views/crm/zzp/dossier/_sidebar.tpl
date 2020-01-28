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
                    {if $zzp->complete == 1}
						<a href="crm/zzp/dossier/{$method}/{$zzp->prev.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Vorige: {$zzp->prev.id} - {$zzp->prev.bedrijfsnaam}">
							<i class="icon-arrow-left12"></i>
						</a>
						<a href="crm/zzp" class="btn border-0">
							<i class="icon-undo2 mr-1"></i>
							Terug naar ZZP'ers
						</a>
						<a href="crm/zzp/dossier/{$method}/{$zzp->next.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Volgende: {$zzp->next.id} - {$zzp->next.bedrijfsnaam}">
							<i class="icon-arrow-right13"></i>
						</a>

                        {* annuleren bij nieuwe aanmelding*}
                    {else}
						<a href="crm/zzp" class="btn border-0 w-100 text-warning">
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
                    {if $zzp->complete == 1}
						<li class="nav-item">
							<a href="crm/zzp/dossier/overzicht/{$zzp->zzp_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
								<span>
									<i class="icon-home5 mr-2"></i>Overzicht
								</span>
							</a>
						</li>
                    {/if}


                    {if $zzp->complete == 1 }
						<!-- li Notities -->
						<li class="nav-item">
							<a href="crm/zzp/dossier/notities/{$zzp->zzp_id}" class="nav-link {if $active == 'notities'}active{/if}">
								<i class="icon-pencil mr-2"></i>Notities
							</a>
						</li>

						<!-- li Documenten -->
						<li class="nav-item">
							<a href="crm/zzp/dossier/documenten/{$zzp->zzp_id}" class="nav-link {if $active == 'documenten'}active{/if}">
								<i class="icon-file-text2 mr-2"></i>Documenten
							</a>
						</li>

						<!-- li Facturen -->
						<li class="nav-item">
							<a href="crm/zzp/dossier/facturen/{$zzp->zzp_id}" class="nav-link {if $active == 'facturen'}active{/if}">
								<i class="icon-coin-euro mr-2"></i>Facturen
							</a>
						</li>

						<!-- Header Instellingen -->
						<li class="nav-item-header">Instellingen</li>


						<!-- li Algemene instellingen -->
						<li class="nav-item {if $zzp->complete != 1}order-1{/if}">
							<a href="crm/zzp/dossier/algemeneinstellingen/{$zzp->zzp_id}" class="nav-link {if $active == 'algemeneinstellingen'}active{/if}">
                                {* standaard icon *}
								<i class="icon-cog mr-2"></i>
								Algemeen
							</a>
						</li>
                    {/if}


					<!-- li Bedrijfsgegevens, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $zzp->complete != 1}order-1{/if}">
						<a href="crm/zzp/dossier/bedrijfsgegevens/{$zzp->zzp_id}" class="nav-link {if $active == 'bedrijfsgegevens'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $zzp->bedrijfsgegevens_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $zzp->complete == 0}
                                    {if $zzp->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $zzp->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
                                {else}
                                    {* standaard icon *}
									<i class="icon-cog mr-2"></i>
                                {/if}
                            {/if}
							Bedrijfsgegevens
						</a>
					</li>

					<!-- li Persoonsgegeves, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $zzp->complete != 1}order-2{/if}">
						<a {if $zzp->bedrijfsgegevens_complete != NULL}href="crm/zzp/dossier/persoonsgegevens/{$zzp->zzp_id}"{/if} class="nav-link {if $zzp->bedrijfsgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'persoonsgegevens'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $zzp->persoonsgegevens_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $zzp->complete == 0}
                                    {if $zzp->persoonsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $zzp->persoonsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
                                {else}
                                    {* standaard icon *}
									<i class="icon-cog mr-2"></i>
                                {/if}
                            {/if}
							Persoonsgegevens
						</a>
					</li>

					<!-- li Documenten, andere volgorde wanneer nieuwe aanmelding -->
					{if $zzp->complete != 1}
					<li class="nav-item {if $zzp->complete != 1}order-3{/if}">
						<a {if $zzp->persoonsgegevens_complete != NULL}href="crm/zzp/dossier/documenten/{$zzp->zzp_id}"{/if} class="nav-link {if $zzp->persoonsgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'documenten'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $zzp->documenten_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $zzp->complete == 0}
                                    {if $zzp->documenten_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $zzp->documenten_complete == 1}
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
                    {/if}

					<!-- li Factuurgegevens, andere volgorde wanneer nieuwe aanmelding -->
					<li class="nav-item {if $zzp->complete != 1}order-4{/if}">
						<a {if $zzp->persoonsgegevens_complete != NULL}href="crm/zzp/dossier/factuurgegevens/{$zzp->zzp_id}"{/if} class="nav-link {if $zzp->persoonsgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'factuurgegevens'}active{/if}">
                            {* afwijkende icons voor nieuwe aanmelding *}
                            {if $zzp->factuurgegevens_complete == NULL}
								<i class="icon-checkbox-unchecked2 mr-2"></i>
                            {else}
                                {if $zzp->complete == 0}
                                    {if $zzp->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                    {if $zzp->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
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
