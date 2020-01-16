{extends file='../layout.tpl'}
{block "title"}Welkom{/block}
{block "header-icon"}{/block}
{block "header-title"}{/block}
{assign "hide_menu" "true"}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper" style="margin-top: 40px;">
		<!-- Content area -->
		<div class="content d-flex justify-content-center align-items-center">


			<form method="post" class="login-form" style="width: 30rem" action="">
				<div class="card mb-0">
					<div class="card-body">

						<!-------------------------------------------------- wachtwoord form ------------------------------------------------------------------>
                        {if !isset($expired)}
                            {if !isset($success)}
								<div class="text-center mb-3">
                                    {if isset($reset)}
										<i class="icon-lock icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    {else}
										<i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    {/if}
									<h5 class="mb-0">{if isset($reset)}Herstel{else}Activeer{/if} uw account</h5>
								</div>
								<div class="form-group text-center text-muted content-divider">
									<span class="px-2">Uw wachtwoord</span>
								</div>
                                {if isset($msg)}{$msg}{/if}
								<span class="d-block">Voorwaarden voor uw wachtwoord:</span>
								<ul>
									<li>Minstens 6 tekens lang</li>
									<li>Minstens 1 cijfer</li>
									<li>Minstens 1 speciaal teken (b.v.: !,$,%,&)</li>
								</ul>
								<div class="form-group form-group-feedback form-group-feedback-left">
									<input type="password" name="password[1]" class="form-control" placeholder="Wachtwoord">
									<div class="form-control-feedback">
										<i class="icon-lock5 text-muted"></i>
									</div>
								</div>
								<div class="form-group form-group-feedback form-group-feedback-left">
									<input type="password" name="password[2]" class="form-control" placeholder="Herhaal wachtwoord">
									<div class="form-control-feedback">
										<i class="icon-lock5 text-muted"></i>
									</div>
								</div>
								<button type="submit" name="setpassword" class="btn bg-teal-400 btn-block">
                                {if isset($reset)}
	                                Wachwtoord opnieuw instellen
                                {else}
	                                Registratie compleet maken
                                {/if}
									<i class="icon-circle-right2 ml-2"></i></button>
                            {else}
								<!-------------------------------------------------- gelukt ------------------------------------------------------------------>
								<div class="text-center mb-3">
									<i class="icon-check icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
									<h5 class="mb-0">Uw account is klaar voor gebruik!</h5>
									<span class="d-block mt-2">U kunt nu inloggen met uw nieuwe wachtwoord.</span>

									<a href="login" class="btn bg-teal-400 px-4 mt-4">Naar het inlogscherm
										<i class="icon-circle-right2 ml-2"></i>
									</a>

								</div>
                            {/if}
                        {/if}


						<!-------------------------------------------------- expired ------------------------------------------------------------------>
                        {if isset($expired)}
							<div class="text-center mb-3">
								<i class="icon-blocked text-danger p-3 mb-3 mt-1" style="font-size: 60px"></i>
								<h5 class="mb-0">Er gaat wat fout!</h5>

								<span class="d-block mt-2 text-danger">
									{foreach $msg as $m}
                                        {$m}
										<br/>
                                    {/foreach}
								</span>

								<a href="login" class="btn bg-teal-400 px-4 mt-4">Naar het inlogscherm
									<i class="icon-circle-right2 ml-2"></i>
								</a>

							</div>
                        {/if}
					</div>
				</div>
			</form>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->



{/block}