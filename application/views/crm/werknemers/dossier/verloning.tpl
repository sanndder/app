{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
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
                                {assign "label_lg" "4"}
                                {assign "div_xl" "8"}
                                {assign "div_md" "8"}


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Instellingen verloning</legend>

                                    {if $user_type == 'werkgever'}
	                                    <div class="row ">
		                                    <label class="col-md-3">Inhouden zorgverzekering</label>

		                                    <div class="col-md-8">

			                                    <div class="form-check form-check-inline">
				                                    <label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="inhouden_zorgverzekering" required {if (isset($verloning.inhouden_zorgverzekering) && $verloning.inhouden_zorgverzekering == 1)} checked{/if}>
													</span>
					                                    Ja
				                                    </label>
			                                    </div>
			                                    <div class="form-check form-check-inline">
				                                    <label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="inhouden_zorgverzekering" {if (isset($verloning.inhouden_zorgverzekering) && $verloning.inhouden_zorgverzekering == 0) || $verloning.inhouden_zorgverzekering == NULL} checked{/if}>
												</span>
					                                    Nee
				                                    </label>
			                                    </div>
		                                    </div>
	                                    </div>

	                                    <div class="row row-light-plus">
											<label class="col-md-3">Vakantiegeld direct uitkeren</label>

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
													<input value="0" type="radio" class="form-input-styled" name="vakantiegeld_direct" {if (isset($verloning.vakantiegeld_direct) && $verloning.vakantiegeld_direct == 0 ) || $verloning.vakantiegeld_direct == NULL} checked{/if}>
												</span>
														Nee
													</label>
												</div>
											</div>
										</div>

	                                    <div class="row mt-2">
		                                    <label class="col-md-3">Feestdagen direct uitkeren</label>

		                                    <div class="col-md-8">

			                                    <div class="form-check form-check-inline">
				                                    <label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="feestdagen_direct" required {if isset($verloning.feestdagen_direct) && $verloning.feestdagen_direct == 1} checked{/if}>
													</span>
					                                    Ja
				                                    </label>
			                                    </div>
			                                    <div class="form-check form-check-inline">
				                                    <label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="feestdagen_direct" {if (isset($verloning.feestdagen_direct) && $verloning.feestdagen_direct == 0 ) || $verloning.vakantiegeld_direct == NULL} checked{/if}>
												</span>
					                                    Nee
				                                    </label>
			                                    </div>
		                                    </div>
	                                    </div>

	                                    <div class="row row-light-plus">
		                                    <label class="col-md-3">Kort verzuim direct uitkeren</label>

		                                    <div class="col-md-8">

			                                    <div class="form-check form-check-inline">
				                                    <label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="kortverzuim_direct" required {if isset($verloning.kortverzuim_direct) && $verloning.kortverzuim_direct == 1} checked{/if}>
													</span>
					                                    Ja
				                                    </label>
			                                    </div>
			                                    <div class="form-check form-check-inline">
				                                    <label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="kortverzuim_direct" {if (isset($verloning.kortverzuim_direct) && $verloning.kortverzuim_direct == 0 ) || $verloning.vakantiegeld_direct == NULL} checked{/if}>
												</span>
					                                    Nee
				                                    </label>
			                                    </div>
		                                    </div>
	                                    </div>


										<div class="row mt-2">
											<label class="col-md-3">Wettelijke vakantieuren direct uitkeren</label>

											<div class="col-md-8">

												<div class="form-check form-check-inline">
													<label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="vakantieuren_wettelijk_direct" required {if isset($verloning.vakantieuren_wettelijk_direct) && $verloning.vakantieuren_wettelijk_direct == 1} checked{/if}>
													</span>
														Ja
													</label>
												</div>
												<div class="form-check form-check-inline">
													<label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="vakantieuren_wettelijk_direct" {if (isset($verloning.vakantieuren_wettelijk_direct) && $verloning.vakantieuren_wettelijk_direct == 0) || $verloning.vakantieuren_wettelijk_direct == NULL} checked{/if}>
												</span>
														Nee
													</label>
												</div>
											</div>
										</div>

										<div class="row row-light-plus">
											<label class="col-md-3">Bovenwettelijke vakantieuren direct uitkeren</label>

											<div class="col-md-8">

												<div class="form-check form-check-inline">
													<label class="form-check-label">
													<span class="checked">
														<input value="1" type="radio" class="form-input-styled" name="vakantieuren_bovenwettelijk_direct" required {if isset($verloning.vakantieuren_bovenwettelijk_direct) && $verloning.vakantieuren_bovenwettelijk_direct == 1} checked{/if}>
													</span>
														Ja
													</label>
												</div>
												<div class="form-check form-check-inline">
													<label class="form-check-label">
												<span>
													<input value="0" type="radio" class="form-input-styled" name="vakantieuren_bovenwettelijk_direct" {if (isset($verloning.vakantieuren_bovenwettelijk_direct) && $verloning.vakantieuren_bovenwettelijk_direct == 0) || $verloning.vakantieuren_bovenwettelijk_direct == NULL} checked{/if}>
												</span>
														Nee
													</label>
												</div>
											</div>
										</div>

	                                    <div class="row pt-2 pb-2">
		                                    <label class="col-md-3 pt-2">Aantal wettelijke vakantiedagen</label>

		                                    <div class="col-md-8">

			                                    <input style="width: 120px; text-align: right" name="aantal_vakantiedagen_wettelijk" value="{if isset($verloning.aantal_vakantiedagen_wettelijk)}{$verloning.aantal_vakantiedagen_wettelijk|number_format:0}{/if}{if $verloning.aantal_vakantiedagen_wettelijk == NULL}20{/if}" type="text" class="form-control"/>

		                                    </div>
	                                    </div>

	                                    <div class="row row-light-plus">
		                                    <label class="col-md-3 pt-2">Aantal bovenwettelijke vakantiedagen</label>

		                                    <div class="col-md-8">

			                                    <input style="width: 120px; text-align: right" name="aantal_vakantiedagen_bovenwettelijk" value="{if isset($verloning.aantal_vakantiedagen_bovenwettelijk)}{$verloning.aantal_vakantiedagen_bovenwettelijk|number_format:0}{/if}{if $verloning.aantal_vakantiedagen_bovenwettelijk == NULL}5{/if}" type="text" class="form-control"/>

		                                    </div>
	                                    </div>


										<div class="row mt-2">
											<label class="col-md-3">ATV direct uitkeren</label>

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
													<input value="0" type="radio" class="form-input-styled" name="atv_direct" {if (isset($verloning.atv_direct) && $verloning.atv_direct == 0) || $verloning.atv_direct == NULL} checked{/if}>
												</span>
														Nee
													</label>
												</div>
											</div>
										</div>

	                                    <div class="row row-light-plus">
		                                    <label class="col-md-3 pt-2">Aantal ATV dagen</label>

		                                    <div class="col-md-8">

			                                    <input style="width: 120px; text-align: right" name="aantal_atv_dagen" value="{if isset($verloning.aantal_atv_dagen)}{$verloning.aantal_atv_dagen|number_format:0}{/if}{if $verloning.aantal_atv_dagen == NULL}0{/if}" type="text" class="form-control"/>

		                                    </div>
	                                    </div>
                                    {/if}



									<div class="row mt-3">
										<label class="col-md-3">Deelnemer ET-regeling</label>

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
													<input value="0" type="radio" class="form-input-styled" name="et_regeling" {if (isset($verloning.et_regeling) && $verloning.et_regeling == 0) || $verloning.et_regeling == NULL} checked{/if}>
												</span>
													Nee
												</label>
											</div>
										</div>
										<div class="col-md-6">
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