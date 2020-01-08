{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}
	{if $uitzender->uitzender_id == 0}
		Nieuwe uitzender aanmelden
	{else}
		Uitzender - {$uitzender->bedrijfsnaam}
	{/if}

{/block}

{block "content"}

	{include file='crm/uitzenders/dossier/_sidebar.tpl' active='bedrijfsgegevens'}


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

								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-6 mb-3">
										<button type="submit" name="set" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
									</div><!-- /col -->
                                    {if $ENV == 'development'}
										<div class="col-lg-6 text-right mb-3">
											<span data-title="Formulier invullen" data-popup="tooltip" data-placement="top" style="cursor:pointer;" onclick="fillForm()">
												<i class="icon-pencil3 mr-2" style="font-size: 22px"></i>
											</span>
										</div>
                                    {/if}
								</div><!-- /row -->

								{*settings*}
								{assign "label_lg" "3"}
								{assign "div_xl" "8"}
								{assign "div_md" "8"}


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bedrijfsgegevens</legend>
									<!-- bedrijfsnaam -->
									{if isset($formdata.bedrijfsnaam)}
										{assign "field" "bedrijfsnaam"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- kvknr -->
									{if isset($formdata.kvknr)}
										{assign "field" "kvknr"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- btwnr -->
									{if isset($formdata.btwnr)}
										{assign "field" "btwnr"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
									{/if}

									<!-- btwnr -->
									{if isset($formdata.telefoon)}
										{assign "field" "telefoon"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}
								</fieldset>

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bezoekadres</legend>

									<!-- straat -->
									{if isset($formdata.straat)}
										{assign "field" "straat"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- huisnummer -->
									{if isset($formdata.huisnummer)}
										{assign "field" "huisnummer"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- huisnummer_toevoeging -->
									{if isset($formdata.huisnummer_toevoeging)}
										{assign "field" "huisnummer_toevoeging"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- postcode -->
									{if isset($formdata.postcode)}
										{assign "field" "postcode"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- plaats -->
									{if isset($formdata.plaats)}
										{assign "field" "plaats"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}
								</fieldset>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Postbus</legend>

									<!-- postbus_nummer -->
									{if isset($formdata.postbus_nummer)}
										{assign "field" "postbus_nummer"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- postbus_postcode -->
									{if isset($formdata.postbus_postcode)}
										{assign "field" "postbus_postcode"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- postbus_plaats -->
									{if isset($formdata.postbus_plaats)}
										{assign "field" "postbus_plaats"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
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