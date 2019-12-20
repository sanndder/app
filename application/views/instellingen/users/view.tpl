{extends file='../../layout.tpl'}
{block "title"}Gebruikers{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Gebruikers{/block}

{block "content"}

    {if $usertype == 'werkgever'}{include file='instellingen/werkgever/_sidebar.tpl' active='users'}{/if}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xxl-6 col-xl-8 col-lg-12">

                    {if isset($msg)}{$msg}{/if}

					<!-- Basic card -->
					<div class="card">

						<div class="card-body">

							<table class="table">
								<tr>
									<td class="font-weight-bold">Naam:</td>
									<td>{$user.naam}</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Username:</td>
									<td>{$user.username}</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Wachtwoord:</td>
									<td>
                                        {if $user.password === NULL}
	                                        <i>wachten op activeren</i>
                                        {else}
                                            ***************
                                        {/if}
									</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Aangemaakt op:</td>
									<td>{$user.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Aangemaakt door:</td>
									<td>{$user.created_by}</td>
								</tr>
								<tr>
									<td class="font-weight-bold">Aanmeldlink verloopt op:</td>
									<td>
                                        {if $user.new_key_expires === NULL}
	                                        -
	                                    {else}
                                            {$user.new_key_expires|date_format: '%d-%m-%Y om %R:%S'}
                                        {/if}
									</td>
								</tr>
							</table>

						</div>
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}