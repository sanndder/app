<!-- Main sidebar -->
<div class="sidebar sidebar-light sidebar-main sidebar-expand-lg align-self-start" {if $werknemer->archief == 1}style="border-color: red"{/if}>

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

	{* instellen *}
	{if $user_type == 'werkgever'} {assign "compare_value" "1"} {/if}
    {if $user_type == 'uitzender'} {assign "compare_value" "0"} {/if}

	<!-- Sidebar content -->
	<div class="sidebar-content">
		<div class="card card-sidebar-mobile">

            {* knoppen boven het menu, speciale annuleer button voor nieuwe aanmeldingen*}
			<div class="card-header bg-transparent p-0">
				<div class="d-flex justify-content-between sidebar-buttons">

                    {* vorige/vorige alleen waneer alles compleet*}
                    {if $werknemer->complete == $compare_value || $werknemer->complete == 1}
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

					{* werkgever menu *}
					{if $user_type == 'werkgever'}

						{* nog niet klaar *}
                        {if $werknemer->complete != 1}

	                        <!-- li Persoonsgegevens -->
	                        <li class="nav-item">
		                        <a href="crm/werknemers/dossier/gegevens/{$werknemer->werknemer_id}" class="nav-link {if $active == 'gegevens'}active{/if}">
		                            {if $werknemer->gegevens_complete == NULL}
				                        <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {elseif  $werknemer->gegevens_complete == 0}
			                            <i class="icon-pencil7 mr-2"></i>
                                    {else}
			                            <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
			                        Persoonsgegevens
		                        </a>
	                        </li>

	                        <!-- li Documenten -->
	                        <li class="nav-item">
		                        <a {if $werknemer->gegevens_complete != NULL}href="crm/werknemers/dossier/documenten/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'documenten'}active{/if}">
                                    {if $werknemer->documenten_complete == NULL}
				                        <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {elseif  $werknemer->documenten_complete == 0}
	                                    <i class="icon-pencil7 mr-2"></i>
                                    {else}
	                                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
			                        Documenten
		                        </a>
	                        </li>

	                        <!-- li Dienstverband -->
	                        <li class="nav-item">
		                        <a {if $werknemer->documenten_complete != NULL}href="crm/werknemers/dossier/dienstverband/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'dienstverband'}active{/if}">
                                    {if $werknemer->dienstverband_complete == NULL}
				                        <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {elseif  $werknemer->dienstverband_complete == 0}
	                                    <i class="icon-pencil7 mr-2"></i>
                                    {else}
	                                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
			                        Dienstverband
		                        </a>
	                        </li>

	                        <!-- li Verloning -->
	                        <li class="nav-item">
		                        <a {if $werknemer->dienstverband_complete != NULL}href="crm/werknemers/dossier/verloning/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'verloning'}active{/if}">
                                    {if $werknemer->verloning_complete == NULL}
				                        <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {elseif  $werknemer->verloning_complete == 0}
	                                    <i class="icon-pencil7 mr-2"></i>
                                    {else}
	                                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
			                        Verloning
		                        </a>
	                        </li>

                            {if $werknemer->deelnemer_etregeling == 1}
	                            <!-- li ET -->
	                            <li class="nav-item">
		                            <a {if $werknemer->verloning_complete != NULL}href="crm/werknemers/dossier/etregeling/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'etregeling'}active{/if}">
                                        {if $werknemer->etregeling_complete == NULL}
				                            <i class="icon-checkbox-unchecked2 mr-2"></i>
                                        {elseif  $werknemer->etregeling_complete == 0}
				                            <i class="icon-pencil7 mr-2"></i>
                                        {else}
	                                        <i class="icon-checkbox-checked mr-2"></i>
                                        {/if}
			                            ET-regeling
		                            </a>
	                            </li>
                            {/if}

                        {else}{* volledige menu *}

	                        <!-- li Overzicht -->
	                        <li class="nav-item">
		                        <a href="crm/werknemers/dossier/overzicht/{$werknemer->werknemer_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
									<i class="icon-home5 mr-2"></i>Overzicht
		                        </a>
	                        </li>

	                        <!-- li Documenten -->
	                        <li class="nav-item">
		                        <a href="crm/werknemers/dossier/documenten/{$werknemer->werknemer_id}" class="nav-link {if $active == 'documenten'}active{/if}">
			                       <i class="icon-file-text2 mr-2"></i>Documenten
		                        </a>
	                        </li>

                            {if $user_id == '2'}
		                        <li class="nav-item">
			                        <a {if $werknemer->gegevens_complete != NULL}href="crm/werknemers/dossier/documentenn/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'documenten'}active{/if}">
				                        <i class="icon-file-text2 mr-2"></i>Documenten nieuw
			                        </a>
		                        </li>
                            {/if}

	                        <!-- li Plaatsingen -->
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

	                        <!-- li Persoonsgegevens -->
	                        <li class="nav-item">
		                        <a href="crm/werknemers/dossier/gegevens/{$werknemer->werknemer_id}" class="nav-link {if $active == 'gegevens'}active{/if}">
			                        <i class="icon-cog mr-2"></i>Persoonsgegevens
		                        </a>
	                        </li>

	                        <!-- li Dienstverband -->
	                        <li class="nav-item">
		                        <a href="crm/werknemers/dossier/dienstverband/{$werknemer->werknemer_id}" class="nav-link {if $active == 'dienstverband'}active{/if}">
			                        <i class="icon-cog mr-2"></i>Dienstverband
		                        </a>
	                        </li>

	                        <!-- li Verloning -->
							<li class="nav-item">
								<a href="crm/werknemers/dossier/verloning/{$werknemer->werknemer_id}" class="nav-link {if $active == 'verloning'}active{/if}">
									<i class="icon-cog mr-2"></i>Verloning
								</a>
							</li>

                            {if $werknemer->deelnemer_etregeling == 1}
	                            <!-- li ET-regeling -->
	                            <li class="nav-item">
		                            <a href="crm/werknemers/dossier/etregeling/{$werknemer->werknemer_id}" class="nav-link {if $active == 'etregeling'}active{/if}">
			                            <i class="icon-cog mr-2"></i>ET-regeling
		                            </a>
	                            </li>
							{/if}
                        {/if}
					{/if}

                    {* werkgever menu *}
                    {if $user_type == 'uitzender'}

	                    {* nog niet klaar *}
	                    {if $werknemer->complete === NULL}

		                    <!-- li Persoonsgegevens -->
		                    <li class="nav-item">
			                    <a href="crm/werknemers/dossier/gegevens/{$werknemer->werknemer_id}" class="nav-link {if $active == 'gegevens'}active{/if}">
                                    {if $werknemer->gegevens_complete == NULL}
					                    <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {else}
					                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
				                    Persoonsgegevens
			                    </a>
		                    </li>

		                    <!-- li Documenten -->
		                    <li class="nav-item">
			                    <a {if $werknemer->gegevens_complete != NULL}href="crm/werknemers/dossier/documenten/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'documenten'}active{/if}">
                                    {if $werknemer->documenten_complete == NULL}
					                    <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {else}
					                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
				                    Documenten
			                    </a>
		                    </li>

		                    <!-- li Dienstverband -->
		                    <li class="nav-item">
			                    <a {if $werknemer->documenten_complete != NULL}href="crm/werknemers/dossier/dienstverband/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'dienstverband'}active{/if}">
                                    {if $werknemer->dienstverband_complete == NULL}
					                    <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {else}
					                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
				                    Dienstverband
			                    </a>
		                    </li>

		                    <!-- li Verloning -->
		                    <li class="nav-item">
			                    <a {if $werknemer->dienstverband_complete != NULL}href="crm/werknemers/dossier/verloning/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'verloning'}active{/if}">
                                    {if $werknemer->verloning_complete == NULL}
					                    <i class="icon-checkbox-unchecked2 mr-2"></i>
                                    {else}
					                    <i class="icon-checkbox-checked mr-2"></i>
                                    {/if}
				                    Verloning
			                    </a>
		                    </li>

                            {if $werknemer->deelnemer_etregeling == 1}
			                    <!-- li ET -->
			                    <li class="nav-item">
				                    <a {if $werknemer->verloning_complete != NULL}href="crm/werknemers/dossier/etregeling/{$werknemer->werknemer_id}"{/if} class="nav-link {if $active == 'etregeling'}active{/if}">
                                        {if $werknemer->etregeling_complete == NULL}
						                    <i class="icon-checkbox-unchecked2 mr-2"></i>
                                        {else}
						                    <i class="icon-checkbox-checked mr-2"></i>
                                        {/if}
					                    ET-regeling
				                    </a>
			                    </li>
                            {/if}


                        {else}{* volledige menu *}
		                    <!-- li Overzicht -->
		                    <li class="nav-item">
			                    <a href="crm/werknemers/dossier/overzicht/{$werknemer->werknemer_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
				                    <i class="icon-home5 mr-2"></i>Overzicht
			                    </a>
		                    </li>

		                    <!-- li Documenten -->
		                    <li class="nav-item">
			                    <a href="crm/werknemers/dossier/documenten/{$werknemer->werknemer_id}" class="nav-link {if $active == 'documenten'}active{/if}">
				                    <i class="icon-file-text2 mr-2"></i>Documenten
			                    </a>
		                    </li>

		                    <!-- li Plaatsingen -->
		                    <li class="nav-item">
			                    <a href="crm/werknemers/dossier/plaatsingen/{$werknemer->werknemer_id}" class="nav-link {if $active == 'plaatsingen'}active{/if}">
				                    <i class="far fa-handshake mr-2"></i>Plaatsingen
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

						{/if}
					{/if}
				</ul>
			</div>
			<!-- /main navigation -->

		</div>
	</div>
	<!-- /sidebar content -->

</div>
<!-- /main sidebar  -->
