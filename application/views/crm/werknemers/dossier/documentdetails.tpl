{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "uploader" "true"}

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

							<fieldset>
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Document details</legend>
							</fieldset>

							<table class="table">
								<tr>
									<td>Categorie</td>
									<td>{$document_details.categorie}</td>
									<td></td>
								</tr>
								<tr>
									<td>Type</td>
									<td>{$document_details.template_name}</td>
									<td></td>
								</tr>
								<tr>
									<td>Bestand</td>
									<td>
										<a target="_blank" href="documenten/pdf/view/{$document_details.document_id}" class="font-weight-semibold">document.pdf</a>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>Verzonden</td>
									<td>
                                        {if $document_details.send == 1}
                                            {$document_details.send_on|date_format: '%d-%m-%Y om %R:%S'}
                                        {else}
											niet verzonden
                                        {/if}
									</td>
									<td></td>
								</tr>
								<tr>
									<td>Ondertekend</td>
									<td>
                                        {if $document_details.signed == 1}
											{$document_details.signed_on|date_format: '%d-%m-%Y om %R:%S'}
                                        {else}
	                                        <script>
                                                {literal}
												$(document).ready(function ()
												{
													$('#fileupload').fileinput('refresh',
														{
															uploadUrl: 'upload/uploadondertekening/{/literal}{$document_details.document_id}{literal}',
															showPreview: false,
															allowedFileExtensions: ['pdf','PDF']
														});
													$('#fileupload').on("filebatchselected", function(event, files) {
														$('#fileupload').fileinput("upload");
													});
													$('#fileupload').on('fileuploaded', function() {
														window.location.reload();
													});

												});
                                                {/literal}
	                                        </script>

	                                        <form action="#">
		                                        <input name="file" type="file" id="fileupload" class="file-input">
	                                        </form>
                                            
                                        {/if}
									</td>
									<td></td>
								</tr>
							</table>

						</div>
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}