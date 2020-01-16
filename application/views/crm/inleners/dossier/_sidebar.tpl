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
						{if $inlener->complete == 1}
						<a href="crm/inleners/dossier/{$method}/{$inlener->prev.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Vorige: {$inlener->prev.id} - {$inlener->prev.bedrijfsnaam}">
							<i class="icon-arrow-left12"></i>
						</a>
						<a href="crm/inleners" class="btn border-0">
							<i class="icon-undo2 mr-1"></i>
							Terug naar inleners
						</a>
						<a href="crm/inleners/dossier/{$method}/{$inlener->next.id}" class="btn border-0 flex-grow-1" data-popup="tooltip" data-placement="top" data-title="Volgende: {$inlener->next.id} - {$inlener->next.bedrijfsnaam}">
							<i class="icon-arrow-right13"></i>
						</a>

						{* annuleren bij nieuwe aanmelding*}
						{else}
							<a href="crm/inleners" class="btn border-0 w-100 text-warning">
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
						{if $inlener->complete == 1}
							<li class="nav-item">
								<a href="crm/inleners/dossier/overzicht/{$inlener->inlener_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
									<span>
										<i class="icon-home5 mr-2"></i>Overzicht
									</span>
								</a>
							</li>
						{/if}

						<!-- li Contactpersonen, verplaatsen naar einde lijst wanneer nieuwe aanmelding -->
						<li class="nav-item {if $inlener->complete != 1}order-6{/if}">
							<a {if $inlener->factuurgegevens_complete != NULL}href="crm/inleners/dossier/contactpersonen/{$inlener->inlener_id}"{/if} class="nav-link {if $inlener->factuurgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'contactpersonen'}active{/if}">
									{* afwijkende icons voor nieuwe aanmelding *}
									{if $inlener->contactpersoon_complete == NULL}
										<i class="icon-checkbox-unchecked2 mr-2"></i>
									{else}
										{if $inlener->complete == 0}
											{if $inlener->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
											{if $inlener->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
										{else}
											{* standaard icon *}
											<i class="icon-address-book3 mr-2"></i>
										{/if}
									{/if}
								Contactpersonen
							</a>
						</li>

						{if $inlener->complete == 1 }
							<!-- li Notities -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/notities/{$inlener->inlener_id}" class="nav-link {if $active == 'notities'}active{/if}">
									<i class="icon-pencil mr-2"></i>Notities
								</a>
							</li>

							<!-- li Documenten -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/documenten/{$inlener->inlener_id}" class="nav-link {if $active == 'documenten'}active{/if}">
									<i class="icon-file-text2 mr-2"></i>Documenten
								</a>
							</li>

							<!-- li Krediet -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/kredietoverzicht/{$inlener->inlener_id}" class="nav-link {if $active == 'kredietoverzicht'}active{/if}">
									<i class="icon-stats-dots mr-2"></i>Kredietoverzicht
								</a>
							</li>

							<!-- li Facturen -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/facturen/{$inlener->inlener_id}" class="nav-link {if $active == 'facturen'}active{/if}">
									<i class="icon-coin-euro mr-2"></i>Facturen
								</a>
							</li>

							<!-- li Werknemers -->
							<li class="nav-item">
								<a href="crm/inleners/dossier/werknemers/{$inlener->inlener_id}" class="nav-link {if $active == 'werknemers'}active{/if}">
									<i class="icon-user mr-2"></i>Werknemers
								</a>
							</li>

							<!-- Header Instellingen -->
							<li class="nav-item-header">Instellingen</li>


							<!-- li Algemene instellingen -->
							<li class="nav-item {if $inlener->complete != 1}order-1{/if}">
								<a href="crm/inleners/dossier/algemeneinstellingen/{$inlener->inlener_id}" class="nav-link {if $active == 'algemeneinstellingen'}active{/if}">
									{* standaard icon *}
									<i class="icon-cog mr-2"></i>
									Algemeen
								</a>
							</li>
						{/if}


						<!-- li Bedrijfsgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $inlener->complete != 1}order-2{/if}">
							<a href="crm/inleners/dossier/bedrijfsgegevens/{$inlener->inlener_id}" class="nav-link {if $active == 'bedrijfsgegevens'}active{/if}">
								{* afwijkende icons voor nieuwe aanmelding *}
								{if $inlener->bedrijfsgegevens_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								{else}
									{if $inlener->complete == 0}
										{if $inlener->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
										{if $inlener->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
									{else}
										{* standaard icon *}
										<i class="icon-cog mr-2"></i>
									{/if}
								{/if}
								Bedrijfsgegevens
							</a>
						</li>

						<!-- li Emailinstellingen, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $inlener->complete != 1}order-3{/if}">
							<a {if $inlener->bedrijfsgegevens_complete != NULL}href="crm/inleners/dossier/emailadressen/{$inlener->inlener_id}"{/if} class="nav-link {if $inlener->bedrijfsgegevens_complete == NULL}nav-link-disabled{/if} {if $active == 'emailadressen'}active{/if}">
								{* afwijkende icons voor nieuwe aanmelding *}
								{if $inlener->emailadressen_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								{else}
									{if $inlener->complete == 0}
									{if $inlener->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
										{if $inlener->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
									{else}
										{* standaard icon *}
										<i class="icon-cog mr-2"></i>
									{/if}
								{/if}
								Emailadressen
							</a>
						</li>

						<!-- li Factuurgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $inlener->complete != 1}order-4{/if}">
							<a {if $inlener->emailadressen_complete != NULL}href="crm/inleners/dossier/factuurgegevens/{$inlener->inlener_id}"{/if} class="nav-link {if $inlener->emailadressen_complete == NULL}nav-link-disabled{/if} {if $active == 'factuurgegevens'}active{/if}">
								{* afwijkende icons voor nieuwe aanmelding *}
								{if $inlener->factuurgegevens_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
								{else}
									{if $inlener->complete == 0}
										{if $inlener->bedrijfsgegevens_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
										{if $inlener->bedrijfsgegevens_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
									{else}
										{* standaard icon *}
										<i class="icon-cog mr-2"></i>
									{/if}
								{/if}
								Factuurgegevens
							</a>
						</li>

                        {if $inlener->complete != 1 }
						{* li CAO alleen bij aanmelden *}
						<li class="nav-item {if $inlener->complete != 1}order-5{/if}">
							<a {if $inlener->emailadressen_complete != NULL}href="crm/inleners/dossier/cao/{$inlener->inlener_id}"{/if} class="nav-link {if $inlener->emailadressen_complete == NULL}nav-link-disabled{/if} {if $active == 'cao'}active{/if}">
                                {* afwijkende icons voor nieuwe aanmelding *}
                                {if $inlener->cao_complete == NULL}
									<i class="icon-checkbox-unchecked2 mr-2"></i>
                                {else}
                                    {if $inlener->complete == 0}
                                        {if $inlener->cao_complete == 0}<i class="icon-pencil7 mr-2"></i>{/if}
                                        {if $inlener->cao_complete == 1}<i class="icon-checkbox-checked mr-2"></i>{/if}
                                    {else}
                                        {* standaard icon *}
										<i class="icon-cog mr-2"></i>
                                    {/if}
                                {/if}
								Cao
							</a>
						</li>
                        {/if}

                        {if $inlener->complete == 1 }
						<!-- li Verloningsgegevens, andere volgorde wanneer nieuwe aanmelding -->
						<li class="nav-item {if $inlener->complete != 1}order-5{/if}">
							<a href="crm/inleners/dossier/verloninginstellingen/{$inlener->inlener_id}" class="nav-link {if $active == 'verloninginstellingen'}active{/if}">
								{* standaard icon *}
								<i class="icon-cog mr-2"></i>
								CAO & Verloning
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
