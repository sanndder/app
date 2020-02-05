{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{if $uitzender->archief == 1} <span style="color:red">(archief)</span> {/if}{/block}

{block "content"}

	{include file='crm/uitzenders/dossier/_sidebar.tpl' active='emailadressen'}


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


								<fieldset class="mb-2">
									<legend class="mb-2 text-uppercase font-size-sm font-weight-bold">Standaard emailadres</legend>
									<div class="mb-3">Het standaard emailadres is verplicht.</div>

									<!-- standaard -->
									{if isset($formdata.standaard)}
										{assign "field" "standaard"}
										<div class="form-group row">
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

								</fieldset>

								<fieldset class="mb-2">
									<legend class="mb-2 text-uppercase font-size-sm font-weight-bold">Emailadres facturatie</legend>
									<div class="mb-3">U kunt een appart emailadres opgeven voor uw facturen. Indien u geen emailadres opgeeft wordt het standaard emailadres gebruikt.</div>

									<!-- standaard -->
									{if isset($formdata.facturatie)}
										{assign "field" "facturatie"}
										<div class="form-group row">
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
												{if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br/>
												{/foreach}</span>{/if}
											</div>
										</div>
									{/if}

								</fieldset>

								<fieldset class="mb-2">
									<legend class="mb-2 text-uppercase font-size-sm font-weight-bold">Emailadres administratie</legend>
									<div class="mb-3">U kunt een appart emailadres opgeven voor uw contracten en overeenkomsten. Indien u geen emailadres opgeeft wordt het standaard emailadres gebruikt.</div>

									<!-- standaard -->
									{if isset($formdata.administratie)}
										{assign "field" "administratie"}
										<div class="form-group row">
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
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