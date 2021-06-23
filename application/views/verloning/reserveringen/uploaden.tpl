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
							<h5 class="card-title text-primary">Reserveringen uploaden</h5>
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-lg-10">

									<script>
                                        {literal}
										$(document).ready(function(){
											let data = {};

											$('#fileupload').fileinput(
												'refresh',
													{
														uploadUrl:'upload/uploadreserveringen',
														showPreview: false,
														allowedFileExtensions: ['xls','xlsx']
													},
												);
											$('#fileupload').on('fileuploaded', function(){
												window.location.reload();
											});
											$('#fileupload').fileinput('refresh', {uploadExtraData:data});
										});
                                        {/literal}
									</script>

									<form action="#">

										<table style="width: 100%">
											<tr>
												<td>

													<div class="upload">
														<input name="file" type="file" multiple id="fileupload" class="file-input">
													</div>

												</td>
											</tr>
										</table>

									</form>

								</div><!-- /col -->
							</div><!-- /row -->

						</div><!-- /card body -->
					</div><!-- /basic card -->

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Stand reserveringen
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title text-primary">Stand reserveringen per {$last_update|date_format: '%d-%m-%Y'}</h5>
						</div>

						<div class="card-body">

							<table>
								<tr>
									<td class="pr-5">Vakantiegeld</td>
									<td class="text-right">€ {if isset($stand.vakantiegeld)}{$stand.vakantiegeld|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Vakantieuren F12</td>
									<td class="text-right">€ {if isset($stand.vakantieuren_F12)}{$stand.vakantieuren_F12|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Feestdagen</td>
									<td class="text-right">€ {if isset($stand.feestdagen)}{$stand.feestdagen|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Kort verzuim</td>
									<td class="text-right">€ {if isset($stand.kort_verzuim)}{$stand.kort_verzuim|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<th>Totaal</th>
									<th class="text-right">
										€
                                        {if isset($stand.kort_verzuim)}
	                                        {$stand.vakantiegeld + $stand.vakantieuren_F12 + $stand.feestdagen + $stand.kort_verzuim|number_format:2:',':'.'}
                                        {else}
	                                        0,00
										{/if}
									</th>
								</tr>
							</table>


						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}