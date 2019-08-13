{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "uploader" "true"}

{block "content"}

	{include file='crm/inleners/dossier/_sidebar.tpl' active='verloninginstellingen'}


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

						<div class="card-header bg-light pb-0 pt-sm-0 header-elements-sm-inline">
							<div class="header-elements">
								<ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
									<li class="nav-item">
										<a href="#card-tab1" class="nav-link active" data-toggle="tab">
											Algemeen
										</a>
									</li>
									<li class="nav-item">
										<a href="#card-tab2" class="nav-link" data-toggle="tab">
											Urentypes
										</a>
									</li>
									<li class="nav-item">
										<a href="#card-tab2" class="nav-link" data-toggle="tab">
											Vergoedingen
										</a>
									</li>
									<li class="nav-item">
										<a href="#card-tab3" class="nav-link" data-toggle="tab">
											Staffelkorting
										</a>
									</li>
								</ul>
							</div>
						</div>

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">

								{*settings*}
								{assign "label_lg" "3"}
								{assign "div_xl" "8"}
								{assign "div_md" "8"}


								<fieldset class="mb-3">


									<!-- factor_normaal -->
									{if isset($formdata.factor_normaal)}
										{assign "field" "factor_normaal"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{if is_numeric($formdata.$field.value)}{$formdata.$field.value|number_format:3:',':'.'}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- factor_overuren -->
									{if isset($formdata.factor_overuren)}
										{assign "field" "factor_overuren"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{if is_numeric($formdata.$field.value)}{$formdata.$field.value|number_format:3:',':'.'}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

								</fieldset>


								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" value="inleners_factoren" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
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