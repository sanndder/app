<div class="navbar navbar-expand-lg navbar-light navbar-sticky">
	<!-- hidden buttons for mobile view -->
	<div class="text-left d-lg-none">
		<!-- left side bar -->
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
			<i class="icon-paragraph-justify3 mr-2"></i>Zijmenu
		</button>
	</div>
	<div class="text-right d-lg-none">
		<!-- main menu -->
		<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-navigation">
			<i class="icon-unfold mr-2"></i>
			Menu
		</button>
	</div>

	<div class="navbar-collapse collapse" id="navbar-navigation">
		<ul class="navbar-nav">

			<li class="nav-item">
				<a href="dashboard/werkgever" class="navbar-nav-link">
					<i class="icon-home4 mr-2"></i>
					Dashboard
				</a>
			</li>

			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
					<i class="icon-users mr-2"></i>
					CRM
				</a>

				<div class="dropdown-menu">
					<a href="crm/uitzenders" class="dropdown-item">
						<i class="icon-office"></i>Uitzenders
					</a>
					<a href="crm/inleners" class="dropdown-item">
						<i class="icon-user-tie"></i>Inleners
					</a>
					{if $werkgever_type == 'uitzenden'}
					<a href="crm/werknemers" class="dropdown-item">
						<i class="icon-user"></i>Werknemers
					</a>
                    {/if}
                    {if $werkgever_type == 'bemiddeling'}
						<a href="crm/zzp" class="dropdown-item">
							<i class="icon-user"></i>ZZP'ers
						</a>
					{/if}
					<a href="crm/prospects" class="dropdown-item">
						<i class="icon-question3"></i>Prospects
					</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
					<i class="icon-list mr-2"></i>
					Overzichten {*<span class="badge badge-pill badge-warning ml-2" style="position: relative">3</span>*}
				</a>

				<div class="dropdown-menu">
					{*
					<a href="overzichten/hollandzorg/index" class="dropdown-item d-flex justify-content-between">
						<div><i class="fas fa-briefcase-medical mr-2"></i>Hollandzorg</div>
						<span class="badge badge-pill badge-warning" style="position: relative;">3</span>
					</a>*}
					<a href="overzichten/facturen/index" class="dropdown-item d-flex justify-content-between">
						<div><i class="mi-euro-symbol mr-2"></i>Facturen</div>
					</a>
					<a href="overzichten/banktransacties/index" class="dropdown-item d-flex justify-content-between">
						<div><i class="icon-list2 mr-2"></i>Banktransacties</div>
					</a>
					<a href="overzichten/factoring/index?aankoop=1&eind=1&compleet=1&incompleet=1" class="dropdown-item d-flex justify-content-between">
						<div><i class="icon-cash4 mr-2"></i>Factoring</div>
					</a>
					<a href="overzichten/snelstart/index" class="dropdown-item d-flex justify-content-between">
						<div><i class="icon-books mr-2"></i>Snelstart</div>
					</a>
					<a href="overzichten/omzet/index" class="dropdown-item d-flex justify-content-between">
						<div><i class="icon-chart mr-2"></i>Omzet & Kosten</div>
					</a>
					<a href="overzichten/zorgverzekering/" class="dropdown-item d-flex justify-content-between">
						<div><i class="icon-folder-plus2 mr-2"></i>Zorgverzekering</div>
					</a>
				</div>
			</li>

			<li class="nav-item">
				<a href="facturatie/overzicht" class="navbar-nav-link">
					<i class="mi-euro-symbol mr-2" style="font-weight: bold"></i>
					Facturatie
				</a>
			</li>


			<li class="nav-item">
				<a href="ureninvoer" class="navbar-nav-link">
					<i class="mi-timer mr-2" style="font-weight: bold"></i>
					Ureninvoer
				</a>
			</li>

            {if $werkgever_type == 'uitzenden'}
				<li class="nav-item">
					<a href="proforma" class="navbar-nav-link">
						<i class="icon-calculator2 mr-2"></i>
						Proforma
					</a>
				</li>
			{/if}


			<li class="nav-item">
				<a href="emailcentrum" class="navbar-nav-link">
					<i class="icon-envelop3 mr-2"></i>
					Emailcentrum
				</a>
			</li>

			{if $user_id == 2}
				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
						<i class="icon-file-spreadsheet mr-2"></i>
						Verloning
					</a>

					<div class="dropdown-menu">
						<a href="verloning/export/index" class="dropdown-item">
							<i class="icon-download"></i>Export
						</a>
						<a href="verloning/loonstroken/uploaden" class="dropdown-item">
							<i class="icon-coin-euro"></i>Loonstroken uploaden
						</a>
						<a href="verloning/reserveringen/uploaden" class="dropdown-item">
							<i class="icon-upload"></i>Reserveringen uploaden
						</a>
					</div>
				</li>
            {/if}

			<li class="nav-item">
				<a href="instellingen/werkgever" class="navbar-nav-link">
					<i class="icon-cog mr-2"></i>
					Instellingen
				</a>
			</li>
		</ul>

		<ul class="navbar-nav ml-md-auto">
			<li class="nav-item">
				<span class="navbar-nav-link toggle-right-sidebar">
					<i class="icon-pencil mr-2"></i>Notitie
				</span>
			</li>
		</ul>

	</div>
</div>


