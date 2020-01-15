{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='documenten'}


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

							<!----------------------------------------------------------------------------- ID bewijs -------------------------------------------------------------------------------------->
							<fieldset class="">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">ID bewijs</legend>
							</fieldset>

							<table>
								<tr>
									<td>Voorkant</td>
									<td>
                                        {if $id_achterkant != NULL}
											<a href="{$id_voorkant}" target="_blank">id_voorkant.jpg</a>
                                        {/if}
									</td>
								</tr>
								<tr>
									<td class="pr-4 pt-2">Achterkant</td>
									<td class="pr-4 pt-2">
                                        {if $id_achterkant != NULL}
											<a href="{$id_achterkant}" target="_blank">id_achterkant.jpg</a>
                                        {/if}
									</td>
								</tr>
							</table>

							<!----------------------------------------------------------------------------- Arbeidsovereenkomst -------------------------------------------------------------------------------------->
							<fieldset class="mt-4">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Arbeidsovereenkomst</legend>
							</fieldset>

                            {if $contract === NULL}
								<a href="{$base_url}/crm/werknemers/dossier/documenten/{$werknemer->id}?contract">
									<i class="icon-file-plus mr-2"></i>Arbeidsovereenkomst genereren
								</a>
                            {else}
								<a href="{$base_url}/documenten/pdf/view/{$contract.document_id}" target="_blank">
									<i class="icon-file-pdf mr-2"></i>Arbeidsovereenkomst downloaden
								</a>
                            {/if}


						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}