{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='dienstverband'}


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


					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| Start
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Dienstverband</legend>
                                    {if isset($errors.indienst)}
										<div class="alert alert-warning alert-styled-left alert-arrow-left">{$errors.indienst}</div>
                                    {/if}
									<div class="mb-3">Vanaf wanneer verricht de werknemer werkzaamheden namens Abering Uitzend B.V. .</div>
									<div class="input-group" style="width: 155px;">
										<input name="indienst" value="{if $indienst !== NULL}{$indienst|date_format: '%d-%m-%Y'}{/if}" type="text" class="form-control pickadate-start-dienstverband" />
									</div>
								</fieldset>

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| CAO
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<fieldset class="mb-3">

									<legend class="text-uppercase font-size-sm font-weight-bold">CAO Uitzendkracht</legend>
                                    {if isset($errors.cao)}
										<div class="alert alert-warning alert-styled-left alert-arrow-left">{$errors.cao}</div>
                                    {/if}
									<div class="mb-3">Onder welke CAO valt de werknemer</div>

									<div class="d-flex align-items-baseline">

										<div>
											<select name="default_cao" style="width: 155px;" class="form-control">
												<option></option>
												<option value="NBBU" {if isset($default_cao) && $default_cao == 'NBBU'} selected{/if}>NBBU</option>
												<option value="BOUW" {if isset($default_cao) && $default_cao == 'BOUW'} selected{/if}>Bouw bouwplaats</option>
												<option value="BOUW-UTA" {if isset($default_cao) && $default_cao == 'BOUW-UTA'} selected{/if}>Bouw Uta</option>
											</select>
										</div>
										<div class="ml-3">
											<button type="submit" name="set_cao" class="btn btn-success">
												<i class="icon-checkmark2 mr-1"></i>CAO opslaan
											</button>
										</div>

									</div>

								</fieldset>

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| Fase
								-------------------------------------------------------------------------------------------------------------------------------------------------->
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

								<!-------------------------------------------------------------------------------------------------------------------------------------------------
								|| Pensioen
								-------------------------------------------------------------------------------------------------------------------------------------------------->
								<fieldset class="mb-3">

									<legend class="text-uppercase font-size-sm font-weight-bold">Pensioen instellingen</legend>

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
												<input value="basis" type="radio" class="form-input-styled" name="stipp">
											</span>
											Stipp Basis
										</label>
									</div>
									<div class="form-check">
										<label class="form-check-label">
											<span>
												<input value="plus" type="radio" class="form-input-styled" name="stipp">
											</span>
											Stipp Plus
										</label>
									</div>


								</fieldset>



							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}