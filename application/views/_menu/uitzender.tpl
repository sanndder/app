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
				<a href="dashboard/uitzender" class="navbar-nav-link">
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
				</div>
			</li>

			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
					<i class="icon-users mr-2"></i>
					Overzichten
				</a>

				<div class="dropdown-menu">
					<a href="overzichten/marge" class="dropdown-item">
						<i class="icon-euro"></i>Marge & Uren
					</a>
				</div>
			</li>

			{if $uitzender_sysyteeminstellingen->facturen_wachtrij == 1}
				<li class="nav-item">
					<a href="facturenoverzicht/wachtrij" class="navbar-nav-link">
						<i class="icon-hour-glass mr-2" style="font-weight: bold"></i>
						Wachtrij
						{if $count_wachtrij > 0}
							<span class="badge badge-pill badge-warning ml-1" style="position:relative; top: auto; right: auto">{$count_wachtrij}</span>
						{/if}
					</a>
				</li>
            {/if}

			<li class="nav-item">
				<a href="facturenoverzicht/uitzender" class="navbar-nav-link">
					<i class="mi-euro-symbol mr-2" style="font-weight: bold"></i>
					Facturen & Marge
				</a>
			</li>

			<li class="nav-item">
				<a href="proforma" class="navbar-nav-link">
					<i class="icon-calculator2 mr-2"></i>
					Proforma
				</a>
			</li>


			<li class="nav-item">
				<a href="ureninvoer" class="navbar-nav-link">
					<i class="mi-timer mr-2" style="font-weight: bold"></i>
					Ureninvoer
				</a>
			</li>

			{*
			<li class="nav-item">
				<a href="instellingen/uitzender" class="navbar-nav-link">
					<i class="icon-cog mr-2"></i>
					Instellingen
				</a>
			</li>
			*}
		</ul>

	</div>
</div>


