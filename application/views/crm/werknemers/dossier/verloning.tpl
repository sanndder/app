{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='verloning'}


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
					|| Instellingen
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
									<legend class="text-uppercase font-size-sm font-weight-bold">Instellingen verloning</legend>

									{if $user_type == 'werkgever'}
									<div class="row">
										<label class="col-md-2">Vakantiegeld direct uitkeren</label>

										<div class="col-md-8">

											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="vakantiegeld_direct" required {if isset($verloning.vakantiegeld_direct) && $verloning.vakantiegeld_direct == 1} checked{/if}>
													</span>
													Ja
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="vakantiegeld_direct" {if isset($verloning.vakantiegeld_direct) && $verloning.vakantiegeld_direct == 0} checked{/if}>
												</span>
													Nee
												</label>
											</div>
										</div>
									</div>

									<div class="row mt-2">
										<label class="col-md-2">Vakantieuren direct uitkeren</label>

										<div class="col-md-8">

											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="vakantieuren_direct" required {if isset($verloning.vakantieuren_direct) && $verloning.vakantieuren_direct == 1} checked{/if}>
													</span>
													Ja
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="vakantieuren_direct" {if isset($verloning.vakantieuren_direct) && $verloning.vakantieuren_direct == 0} checked{/if}>
												</span>
													Nee
												</label>
											</div>
										</div>
									</div>

									<div class="row mt-2">
										<label class="col-md-2">ATV direct uitkeren</label>

										<div class="col-md-8">

											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="atv_direct" required {if isset($verloning.atv_direct) && $verloning.atv_direct == 1} checked{/if}>
													</span>
													Ja
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="atv_direct" {if isset($verloning.atv_direct) && $verloning.atv_direct == 0} checked{/if}>
												</span>
													Nee
												</label>
											</div>
										</div>
									</div>
                                    {/if}

									<div class="row mt-4">
										<label class="col-md-2">Deelnemer ET-regeling</label>

										<div class="col-md-2">

											<div class="form-check form-check-inline">
												<label class="form-check-label">
													<span>
														<input value="1" type="radio" class="form-input-styled" name="et_regeling" required {if isset($verloning.et_regeling) && $verloning.et_regeling == 1} checked{/if}>
													</span>
													Ja
												</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label">
												<span class="checked">
													<input value="0" type="radio" class="form-input-styled" name="et_regeling" {if isset($verloning.et_regeling) && $verloning.et_regeling == 0} checked{/if}>
												</span>
													Nee
												</label>
											</div>
										</div>
										<div class="col-md-8">
											<i>Deelnemen aan de ET-regeling is alleen voor werknemers die woonachtig zijn in het buitenland en voor werk in Nederland verblijven</i>
										</div>
									</div>

								</fieldset>


								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" value="werknemers_factoren" class="btn btn-success btn-sm">
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