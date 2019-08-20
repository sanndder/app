<div class="navbar navbar-expand-md navbar-dark">
	<div class="navbar-brand wmin-0 mr-5">
		<img src="template/global_assets/images/logo_light.png" alt="">
	</div>

	<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
			<i class="icon-tree5"></i>
		</button>
	</div>

	<div class="collapse navbar-collapse" id="navbar-mobile">

		<ul class="navbar-nav ml-auto">

			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
					<i class="icon-user mr-3 icon-2x"></i>
					<span>{$user_name}</span>
				</a>

				<div class="dropdown-menu dropdown-menu-right">
					<a href="mijnaccount/index" class="dropdown-item"><i class="icon-cog5"></i> Mijn account</a>
					<a href="login/index?logout" class="dropdown-item"><i class="icon-switch2"></i> Uitloggen</a>
				</div>
			</li>

		</ul>
	</div>

</div>