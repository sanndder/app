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
				<a href="dashboard/werknemer" class="navbar-nav-link">
					<i class="icon-home4 mr-2"></i>
					Dashboard
				</a>
			</li>

			<li class="nav-item">
				<a href="werknemer/loonstroken/overzicht" class="navbar-nav-link">
					<i class="icon-files-empty mr-2" style="font-weight: bold"></i>
					Loonstroken
				</a>
			</li>

			{if $user_id == 240 || $user_id == 2}
			<li class="nav-item">
				<a href="ureninvoer" class="navbar-nav-link">
					<i class="mi-timer mr-2" style="font-weight: bold"></i>
					Ureninvoer
				</a>
			</li>
            {/if}

            {if isset($menu_vcu) && $menu_vcu == true}
			<li class="nav-item">
				<a href="vcu/werknemer" class="navbar-nav-link">
					<i class="icon-file-check mr-2" style="font-weight: bold"></i>
					VCU
				</a>
			</li>
            {/if}

		</ul>

	</div>
</div>


