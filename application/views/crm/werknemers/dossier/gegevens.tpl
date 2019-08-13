{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}
	{if $werknemer->werknemer_id == 0}
		Nieuwe werknemer aanmelden
	{else}
		Werknemer - {$werknemer->naam}
	{/if}

{/block}

{block "content"}

	{include file='crm/werknemers/dossier/_sidebar.tpl' active='gegevens'}


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

					{*
					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<div class="scan-button">
								<a href="javascript:void()" class="btn btn-outline-primary" onclick="showUpload( this)" disabled="none">
									<i class="icon-vcard mr-2" style="font-size: 40px"></i>
									<span style="font-size: 18px">ID-bewijs scannen</span>
								</a>
							</div>

							<div class="scan-upload" style="display: block;">
								<form method="post" enctype="multipart/form-data">
									<div class="form-group row">
										<div class="col-lg-10">
											<div class="custom-file">
												<input name="file" type="file" class="custom-file-input" id="customFile">
												<label class="custom-file-label" for="customFile">ID bewijs zoeken</label>
											</div>
										</div>
									</div>
									<button name="scan" class="btn btn-success" type="submit">
										Scannen
									</button>
								</form>
							</div>
							
							<script>
								{literal}
									function showUpload(obj)
									{
									    $(obj).hide();
									    $('.scan-upload').show();
                                    }
								{/literal}
							</script>

						</div>

					</div>*}

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">

								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" class="btn btn-success btn-sm"><i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan</button>
									</div><!-- /col -->
								</div><!-- /row -->

								{*settings*}
								{assign "label_lg" "3"}
								{assign "div_xl" "8"}
								{assign "div_md" "8"}


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Persoonsgegevens</legend>

									<!-- voorletters -->
									{if isset($formdata.voorletters)}
										{assign "field" "voorletters"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-2 col-md-4">
												<input value="{if isset($carddata.voorletters)}{$carddata.voorletters}{else}{$formdata.$field.value}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- voornaam -->
									{if isset($formdata.voornaam)}
										{assign "field" "voornaam"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{if isset($carddata.voornaam)}{$carddata.voornaam}{else}{$formdata.$field.value}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}


													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- tussenvoegsel -->
									{if isset($formdata.tussenvoegsel)}
										{assign "field" "tussenvoegsel"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-2 col-md-4">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

									<!-- achternaam -->
									{if isset($formdata.achternaam)}
										{assign "field" "achternaam"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{if isset($carddata.achternaam)}{$carddata.achternaam}{else}{$formdata.$field.value}{/if}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}


													<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}


								</fieldset>

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Contactgegevens</legend>

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