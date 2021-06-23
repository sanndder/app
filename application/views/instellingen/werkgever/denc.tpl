{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}
{assign "uploader" "true"}
{assign "datamask" "true"}

{block "content"}

    {include file='instellingen/werkgever/_sidebar.tpl' active='denc'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Instellingen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Documenten & Certificaten instellingen</h5>
				</div>

				<div class="card-body">

                    {if isset($msg)}{$msg}{/if}

					<div class="row">
						<div class="col-md-12">

							<form method="post" action="">
								<table>
									<tr style="font-weight: bold">
										<td>Naam</td>
										<td style="width: 5px"></td>
										<td>Geldigheid</td>
										<td></td>
									</tr>
									<tr>
										<td style="width: 345px">
											<input type="text" name="naam" class="form-control" required>
										</td>
										<td></td>
										<td style="width: 45px">
											<input type="text" name="geldigheid" class="form-control text-right" required>
										</td>
										<td>
											maanden (0 voor oneindig)
										</td>
										<td>
											<button type="submit" class="btn btn-sm btn-success ml-3" name="submit">
												<i class="icon-check mr-1"></i> toevoegen
											</button>
										</td>
									</tr>
								</table>
							</form>

                            {if $velden !== NULL}
								<table class="mt-4">
                                    {foreach $velden as $veld}
										<tr style="border-bottom: 5px solid #FFF">
											<td style="background-color: #EDEDED; padding: 10px 10px; width: 345px; font-weight: bold">{$veld.naam}</td>
											<td style="width: 5px"></td>
											<td style="background-color: #EDEDED; padding: 10px 10px; font-weight: bold; width: 110px">
                                                {if $veld.geldigheid == 0}onbeperkt{else}{$veld.geldigheid} maanden{/if}
											</td>
											<td style="width: 5px"></td>
											<td style="background-color: #EDEDED; padding: 10px 10px;">
												<a href="{$base_url}/instellingen/werkgever/denc?del={$veld.document_id}" class="text-danger">
													<i class="icon-trash"></i></a>
											</td>
										</tr>
                                    {/foreach}
								</table>
                            {/if}


						</div><!-- /col -->
					</div><!-- /row -->


				</div><!-- /card body -->
			</div><!-- /basic card -->

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| uploaden
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Documenten & Certificaten uploaden</h5>
				</div>

				<div class="card-body">

                    {if $velden !== NULL}
                    {foreach $velden as $veld}

	                    {if isset($documenten[$veld.document_id])}
							<div class="row mb-4">
								<div class="col-md-12">

									<table class="mt-2">
										<tr style="border-bottom: 1px solid #CCC">
											<td style="width: 300px">Naam</td>
											<td>Dagtekening</td>
											<td style="width: 100px"></td>
											<td style="width: 200px">Geldig tot</td>
											<td>Acties</td>
										</tr>
										<tr>
											<td class="font-weight-bolder">
												{$veld.naam}
												<input type="hidden" class="file_id" value="{$documenten[$veld.document_id].file_id}">
											</td>
											<td>
												<input style="width: 110px;" data-mask="99-99-9999" type="text" class="text-right dagtekening" name="dagtekening" value="{$documenten[$veld.document_id].dagtekening|date_format: '%d-%m-%Y'}">
											</td>
											<td>
												<button type="button" class="btn btn-success btn-sm" style="padding: 3px 5px; display: none">
													<i class="icon-check"></i>
												</button>
												<i class="icon-spinner2 spinner" style="display: none"></i>
												<i class="icon-check2 text-success" style="font-size: 16px;display: none"></i>
											</td>
											<td>
                                                {if $documenten[$veld.document_id].geldig_tot == NULL}
	                                                onbeperkt
                                                {else}
                                                    {$documenten[$veld.document_id].geldig_tot|date_format: '%d-%m-%Y'}
                                                {/if}
											</td>
											<td>
												<a href="" target="_blank">
													<i class="icon-file-download2" style="font-size: 22px"></i>
												</a>
												<a onclick="return confirm('Document verwijderen?')" href="{$base_url}/instellingen/werkgever/denc?delfile={$veld.document_id}">
													<i class="icon-trash text-danger" text-danger pt-2" style="font-size: 22px;"></i>
												</a>
											</td>
										</tr>
									</table>

								</div><!-- /col -->
							</div>
	                    {else}
							<div class="row">
								<div class="col-md-12">
									<div class="font-weight-bolder mb-1">{$veld.naam}</div>
								</div><!-- /col -->
							</div>
							<!-- /row -->
							<div class="row mb-4">
								<div class="col-md-12">

									<div class="upload">
										<input name="file[{$veld.document_id}]" type="file" multiple id="fileupload" class="file-input fileupload">
									</div>

								</div><!-- /col -->
							</div>
	                    {/if}


						<!-- /row -->

                    {/foreach}
						<script>
                            {literal}
							$(document).ready(function()
							{
								let data = {};

								$('.fileupload').fileinput('refresh', {uploadUrl:'upload/uploadwerkgeverdocument'});
								$('.fileupload').on('fileuploaded', function()
								{
									window.location.reload();
								});
							});

							$(document).on('keydown', '.dagtekening', function(){
								$tr = $(this).closest('tr');
								$tr.find('.btn-success').show();
								$tr.find('.icon-spinner2').hide();
								$tr.find('.icon-check2').hide();
							});

							$(document).on('click', '.btn-success', function(){
								$tr = $(this).closest('tr');
								$tr.find('.btn-success').hide();
								$tr.find('.icon-spinner2').show();
								$.post( "instellingen/werkgever/updatedagtekening/" + $tr.find('.file_id').val() + '/' + $tr.find('[name="dagtekening"]').val(), function() {
									$tr.find('.icon-spinner2').hide();
									$tr.find('.icon-check2').show();
								});
							});
                            {/literal}
						</script>
                    {/if}


				</div>
			</div>


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}