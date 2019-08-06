{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "uploader" "true"}

{block "content"}

	{include file='crm/inleners/dossier/_sidebar.tpl' active='algemeneinstellingen'}


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
									<legend class="text-uppercase font-size-sm font-weight-bold">Standaard factoren</legend>
									<div class="mb-3">Deze factoren worden overgenomen voor nieuw aangemelde inleners.</div>

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

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Handtekening
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Handtekening</h5>
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-xl-6 col-lg-12">

									{* upload alleen wanneer er geen logo is *}
									{if $inlener->handtekening() === NULL }
										<script>
											{literal}
                                            $(document).ready(function ()
                                            {
                                                $('#fileupload2').fileinput('refresh', {uploadUrl: 'upload/uploadhantekeninginlener/{/literal}{$inlener->inlener_id}{literal}'});
                                                $('#fileupload2').on('fileuploaded', function() {
                                                    window.location.reload();
                                                });

                                            });
											{/literal}
										</script>

										<form action="#">
											<input name="file" type="file" id="fileupload2" class="file-input">
										</form>
									{else}

										<img src="{$inlener->handtekening('url')}" />
										<br />
										<br />
										<a href="crm/inleners/dossier/algemeneinstellingen/{$inlener->inlener_id}?delhandtekening" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Handtekening verwijderen</a>
									{/if}

								</div><!-- /col -->

							</div><!-- /row -->

						</div><!-- /card body -->
					</div><!-- /basic card -->


					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Logo
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title">Logo</h5>
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-xl-6 col-lg-12">

									{* upload alleen wanneer er geen logo is *}
									{if $inlener->logo() === NULL }
										<script>
											{literal}
											$(document).ready(function ()
											{
												$('#fileupload').fileinput('refresh', {uploadUrl: 'upload/uploadlogoinlener/{/literal}{$inlener->inlener_id}{literal}'});
												$('#fileupload').on('fileuploaded', function() {
													window.location.reload();
												});

											});
											{/literal}
										</script>

										<form action="#">
											<input name="file" type="file" id="fileupload" class="file-input">
										</form>
									{else}

										<img src="{$inlener->logo('url')}" />
										<br />
										<br />
										<a href="crm/inleners/dossier/algemeneinstellingen/{$inlener->inlener_id}?dellogo" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Logo verwijderen</a>
									{/if}

								</div><!-- /col -->

							</div><!-- /row -->

						</div><!-- /card body -->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}