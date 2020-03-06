{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-coin-euro{/block}
{block "header-title"}Loonstroken{/block}
{assign "uploader" "true"}

{block "content"}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xxl-6 col-xl-6 col-lg-10">


					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Loonstroken uploaden
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title text-primary">Loonstroken uploaden</h5>
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-lg-10">

									<script>
                                        {literal}
										$(document).ready(function(){
											$('#fileupload').fileinput('refresh', {uploadUrl:'upload/uploadloonstroken'});
											$('#fileupload').on('fileuploaded', function(){
												window.location.reload();
											});
										});
                                        {/literal}
									</script>

									<form action="#">
										<input name="file" type="file" id="fileupload" class="file-input">
									</form>

								</div><!-- /col -->
							</div><!-- /row -->

						</div><!-- /card body -->
					</div><!-- /basic card -->

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Loonstroken wachtrij
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title text-primary">Loonstroken wachtrij</h5>
						</div>

						<div class="card-body">

                            {if $zips !== NULL}
								<table class="table">
									<thead>
										<tr>
											<th></th>
											<th class="pl-1"
											>ID</th>
											<th>Bestand</th>
											<th>Loonstroken totaal</th>
											<th>Loonstroken verwerkt</th>
											<th>Upload datum</th>
											<th></th>
										</tr>
									</thead>
                                    {foreach $zips as $zip}
	                                    <tr>
		                                    <td class="p-1">
			                                    <a onclick="return confirm('Bestand verwijderen?')" href="verloning/loonstroken/uploaden?del={$zip.zip_id}">
				                                    <i class="icon-trash text-danger"></i>
			                                    </a>
		                                    </td>
		                                    <td class="pl-1">{$zip.zip_id}</td>
		                                    <td>{$zip.file_name_display}</td>
		                                    <td>{$zip.pdf_totaal}</td>
		                                    <td>{$zip.pdf_totaal - $zip.pdf_resterend}</td>
		                                    <td>{$zip.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
		                                    <td>
			                                    <a class="text-primary" href="verloning/loonstroken/uploaden?verwerk={$zip.zip_id}">
				                                   <i class="icon-play3 mr-1"></i>verwerken
			                                    </a>
		                                    </td>
	                                    </tr>

                                    {/foreach}
								</table>
                            {/if}


						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}