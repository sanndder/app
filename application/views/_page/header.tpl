<div class="navbar navbar-expand-md navbar-dark">
	<div class="navbar-brand wmin-0 mr-5">
		<img src="template/global_assets/images/logo_light.png" alt="">
	</div>

	{* verborgen, alleen voor mobile divices *}
	<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
			<i class="icon-tree5"></i>
		</button>
	</div>

	{if isset($user_accounts) && is_array($user_accounts) && count($user_accounts) > 1}
	<div class="collapse navbar-collapse" id="navbar-mobile">
		<ul class="navbar-nav">

			<li class="nav-item dropdown">
				<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="true">
					<i class="icon-users2"></i>
					<span class="ml-2">{$user_accounts[$account_id].name}</span>
					<i class="icon-arrow-down5"></i>
				</a>

				<div class="dropdown-menu dropdown-content wmin-md-350 ">
					<div class="dropdown-content-header pb-2">
						<span class="font-weight-semibold">Uw accounts</span>
					</div>

					<div class="dropdown-content-body p-0">
						<ul class="media-list">

							{foreach $user_accounts as $a}
							<li class="media font-size-lg dropdown-item">
								<a href="{$current_url}?switchto={$a@key}" class="w-100 h-100 p-3">
									<div class="d-flex justify-content-between w-100">
										<div><i class="icon-circle-right2 mr-2"></i>{$a.name}</div>
										{if $account_id == $a@key}<div><span class="badge bg-success ml-md-3">Actief</span></div>{/if}
									</div>
								</a>
							</li>
                            {/foreach}

						</ul>
					</div>

				</div>
			</li>
		</ul>

	</div>
    {/if}

	{* rechterkant *}
	<div class="collapse navbar-collapse" id="navbar-mobile">

		{if isset($user_name)}
		<ul class="navbar-nav ml-auto">

			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
					<i class="icon-user mr-3 icon-2x"></i>
					<span>{$user_name}</span>
				</a>

				<div class="dropdown-menu dropdown-menu-right">
                    {if !isset($hide_menu)}<a href="mijnaccount/index" class="dropdown-item"><i class="icon-cog5"></i> Mijn account</a>{/if}
					<a href="login/index?logout" class="dropdown-item"><i class="icon-switch2"></i> Uitloggen</a>
				</div>
			</li>

		</ul>
		{/if}

	</div>

</div>