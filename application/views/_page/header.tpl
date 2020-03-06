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

	{if isset($user_accounts) && is_array($user_accounts) && count($user_accounts) > 1 && !isset($smarty.session.logindata.override)}
	<div class="collapse navbar-collapse" id="navbar-mobile">

		{if $user_type != 'werkgever'}

            {foreach $user_accounts as $a}
			<ul class="navbar-nav">

				<li class="nav-item">
					<a href="{$current_url}?switchto={$a@key}" class="navbar-nav-link">
                        {if $account_id == $a@key}<i class="icon-user icon-lg"></i>{/if}
						<span class="ml-1" {if $account_id != $a@key} style="color:#999"{/if}>{$a.name}</span>
					</a>
				</li>

			</ul>
            {/foreach}

		{else}

            {foreach $user_accounts as $a}
				<ul class="navbar-nav">

					<li class="nav-item">
						<a href="{$current_url}?switchto={$a@key}" class="navbar-nav-link">
                            {if $account_id == $a@key}<i class="icon-user icon-lg"></i>{/if}
							<span class="ml-1" {if $account_id != $a@key} style="color:#999"{/if}>{$a.name}</span>
						</a>
					</li>

				</ul>
            {/foreach}


		{/if}


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