{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{if $uitzender->archief == 1} <span style="color:red">(archief)</span> {/if}{/block}

{block "content"}

	{include file='crm/uitzenders/dossier/_sidebar.tpl' active='systeeminstellingen'}


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


								{*settings*}
								{assign "label_lg" "3"}
								{assign "div_xl" "4"}
								{assign "div_md" "6"}


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Systeeminstellingen</legend>

									<!-- Facturen wachtrij -->
                                    {if isset($formdata.facturen_wachtrij)}
                                        {assign "field" "facturen_wachtrij"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- Facturen wachtrij -->
                                    {if isset($formdata.logo_op_inleenovereenkomst)}
                                        {assign "field" "logo_op_inleenovereenkomst"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

								</fieldset>



								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
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