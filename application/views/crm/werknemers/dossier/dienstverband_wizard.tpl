{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "uploader" "true"}

{assign "datamask" "true"}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='dienstverband'}

	<script>
		$(function() {

			$('[name="default_cao"]').on('change',function(){
				if( $(this).val() == 'BOUW-UTA' || $(this).val() == 'BOUW' )
				{
					$('.pensioen-stipp').hide();
					$('.pensioen-bouw').show();
				}
				else
				{
					$('.pensioen-stipp').show();
					$('.pensioen-bouw').hide();
				}
			});
		});

	</script>

	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-10">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-xl-10">

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Standaard factoren
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">


                                {*settings*}
                                {assign "label_lg" "3"}
                                {assign "div_xl" "8"}
                                {assign "div_md" "8"}


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Dienstverband</legend>
									{if isset($errors.indienst)}
										<div class="alert alert-warning alert-styled-left alert-arrow-left">{$errors.indienst}</div>
									{/if}
									<div class="mb-3">Vanaf wanneer verricht de werknemer werkzaamheden namens FlexxOffice Uitzend B.V. .</div>
									<div class="input-group" style="width: 155px;">
										<input name="indienst" value="{if $indienst !== NULL}{$indienst|date_format: '%d-%m-%Y'}{/if}" type="text" class="form-control pickadate-start-dienstverband" />
									</div>
								</fieldset>

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">CAO Uitzendkracht</legend>
                                    {if isset($errors.cao)}
										<div class="alert alert-warning alert-styled-left alert-arrow-left">{$errors.cao}</div>
                                    {/if}
									<div class="mb-3">Onder welke CAO valt de werknemer</div>

									<select name="default_cao" style="width: 155px;" class="form-control">
										<option></option>
										<option value="NBBU" {if isset($default_cao) && $default_cao == 'NBBU'} selected{/if}>NBBU</option>
										<option value="BOUW" {if isset($default_cao) && $default_cao == 'BOUW'} selected{/if}>Bouw bouwplaats</option>
										<option value="BOUW-UTA" {if isset($default_cao) && $default_cao == 'BOUW-UTA'} selected{/if}>Bouw Uta</option>
									</select>



								</fieldset>

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| Fase
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								{if $user_type == 'werkgever'}
								<fieldset class="mb-3">

									<legend class="text-uppercase font-size-sm font-weight-bold">Fase instellingen</legend>

									<div class="form-check">
										<label class="form-check-label">
											<span class="checked">
												<input value="A" type="radio" class="form-input-styled" name="fase" checked="">
											</span>
											Fase A
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<span>
												<input value="B" type="radio" class="form-input-styled" name="fase">
											</span>
											Fase B
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<span>
												<input value="B" type="radio" class="form-input-styled" name="fase">
											</span>
											Fase C
										</label>
									</div>

								</fieldset>
                                {/if}
								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| Pensioen
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<fieldset class="mb-3">

									<legend class="text-uppercase font-size-sm font-weight-bold">Pensioen instellingen</legend>

									<div class="pensioen-stipp" {if isset($default_cao) && ($default_cao == 'BOUW' || $default_cao == 'BOUW-UTA')} style="display: none" {/if}>
										<div class="form-check">
											<label class="form-check-label">
												<span class="checked">
													<input value="0" type="radio" class="form-input-styled" name="stipp" checked="">
												</span>
												Geen pensioen
											</label>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<span>
													<input value="basis" {if isset($pensioen) && $pensioen.stipp == 'basis'} checked{/if} type="radio" class="form-input-styled" name="stipp">
												</span>
												Stipp Basis
											</label>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<span>
													<input value="plus" {if isset($pensioen) && $pensioen.stipp == 'plus'} checked{/if} type="radio" class="form-input-styled" name="stipp">
												</span>
												Stipp Plus
											</label>
										</div>
									</div>

									<div class="pensioen-bouw" {if !isset($default_cao) || ($default_cao == 'NBBU')} style="display: none" {/if}>
										<div class="form-check">
											<label class="form-check-label">
												<span class="checked">
													<input value="1" type="radio" class="form-input-styled" checked="">
												</span>
												Bouw pensioen
											</label>
										</div>
									</div>

								</fieldset>


								<!-- opslaan -->
								<div class="row mt-4">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set_wizard" class="btn btn-success btn-sm">
											<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
										</button>
									</div><!-- /col -->
								</div><!-- /row -->

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}