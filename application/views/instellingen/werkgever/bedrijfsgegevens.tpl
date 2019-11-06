{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}
{assign "uploader" "true"}

{block "content"}

	{include file='instellingen/werkgever/_sidebar.tpl' active='bedrijfsgegevens'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {include file='instellingen/werkgever/_topbar.tpl'}

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Bedrijfsgegevens
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Bedrijfsgegevens</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

						{if isset($msg)}
						<div class="row">
							<div class="col-md-12">
								{$msg}
							</div><!-- /col -->
						</div><!-- /row -->
						{/if}

						<div class="row">
							<div class="col-xl-6 col-lg-12">

								<!-- bedrijfsnaam -->
								{if isset($formdata.bedrijfsnaam)}
									{assign "field" "bedrijfsnaam"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

								<!-- kvknr -->
								{if isset($formdata.kvknr)}
									{assign "field" "kvknr"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

								<!-- btwnr -->
								{if isset($formdata.btwnr)}
									{assign "field" "btwnr"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

							</div><!-- /col -->
							<div class="col-xl-6 col-lg-12">

								<!-- straat -->
								{if isset($formdata.straat)}
									{assign "field" "straat"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

								<!-- huisnummer -->
								{if isset($formdata.huisnummer)}
									{assign "field" "huisnummer"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

								<!-- postcode -->
								{if isset($formdata.postcode)}
									{assign "field" "postcode"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

								<!-- plaats -->
								{if isset($formdata.plaats)}
									{assign "field" "plaats"}
									<div class="form-group row">
										<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}:</label>
										<div class="col-xl-8 col-md-8">
											<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
											{if isset($formdata.$field.error)}
												<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br />{/foreach}</span>{/if}
										</div>
									</div>
								{/if}

							</div><!-- /col -->
						</div><!-- /row -->

						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success"><i class="icon-checkmark2 mr-1"></i>Opslaan</button>
							</div><!-- /col -->
						</div><!-- /row -->

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->



			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Ondertekening
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Handtekening</h5>
				</div>

				<div class="card-body">

					<div class="row">
						<div class="col-xl-6 col-lg-12">

                            {* upload alleen wanneer er geen logo is *}
                            {if $handtekening === NULL }
								<script>
                                    {literal}
                                    $(document).ready(function ()
                                    {
                                        $('#fileupload2').fileinput('refresh', {uploadUrl: 'upload/uploadhantekeningwerkgever/{/literal}{$smarty.session.entiteit_id}{literal}'});
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

								<img src="{$handtekening}" style="max-width: 400px; max-height: 200px;" />
								<br />
								<br />
								<a href="{$base_url}/instellingen/werkgever/bedrijfsgegevens/?delhandtekening" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Handtekening verwijderen</a>
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
                            {if $logo === NULL }
								<script>
                                    {literal}
                                    $(document).ready(function ()
                                    {
                                        $('#fileupload').fileinput('refresh', {uploadUrl: 'upload/uploadlogowerkgever/{/literal}{$smarty.session.entiteit_id}{literal}'});
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

								<img src="{$logo}" style="max-width: 500px; max-height: 300px;" />
								<br />
								<br />
								<a href="{$base_url}/instellingen/werkgever/bedrijfsgegevens/?dellogo" class="btn btn-danger btn-sm"><i class="icon-cross2 mr-1"></i>Logo verwijderen</a>
                            {/if}

						</div><!-- /col -->

					</div><!-- /row -->

				</div><!-- /card body -->
			</div><!-- /basic card -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}