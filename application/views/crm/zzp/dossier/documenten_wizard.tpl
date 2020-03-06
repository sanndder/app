{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$zzp->naam}{/block}
{assign "uploader" "true"}

{block "content"}

    {include file='crm/zzp/dossier/_sidebar.tpl' active='documenten'}


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

							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">Uittreksel KvK uploaden</legend>

								<div class="row">

									<!-- voorkant -->
									<div class="col-xl-5 col-lg-12">

										<!-- script uploader 1 -->
										<script>
                                            {literal}
											$(document).ready(function(){
												$('#fileupload3').fileinput('refresh', {uploadUrl:'upload/uploadkvk/{/literal}{$zzp->zzp_id}{literal}'});
												$('#fileupload3').on('filebatchselected', function(event, files){
													$('#fileupload').fileinput("upload");
												}).on('fileuploaded', function(event, data){
													$('#fileupload3').fileinput('clear');
													$('#form3').hide();
													$('.img-uittreksel').show().find('img').attr('src', data.response.url);
													location.reload();
												});
											});
                                            {/literal}
										</script>

										<!-- form -->
										<form id="form3" action="#" style="{if $uittreksel !== NULL }display:none;{/if}">
											<input name="file" type="file" id="fileupload3" class="file-input">
										</form>

										<!-- plaatje -->
										<div class="uittreksel" style="{if $uittreksel === NULL }display:none;{/if}">
											<a href="crm/zzp/dossier/uittrekselkvk/{$zzp->zzp_id}/{$uittreksel.id}" target="_blank" class="mr-2">
												uittreksel Kvk downloaden
											</a>
											<a href="javascript:void(0)" onclick="deleteUittreksel( {$zzp->zzp_id} )" class="text-danger">
												<i class="icon-trash mr-1"></i>
												Uittreksel KvK verwijderen
											</a>
										</div>

									</div><!-- /voorkant -->
								</div><!-- /voorkant -->


								<fieldset class="mb-3 mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold">ID bewijs uploaden</legend>

									<div class="row">

										<!-- voorkant -->
										<div class="col-xl-5 col-lg-12">

											<div class="mb-3 font-weight-semibold">Voorkant</div>

											<!-- script uploader 1 -->
											<script>
                                                {literal}
												$(document).ready(function(){
													$('#fileupload').fileinput('refresh', {uploadUrl:'upload/uploadwerknemerid/{/literal}{$zzp->zzp_id}{literal}/1'});
													$('#fileupload').on('filebatchselected', function(event, files){
														$('#fileupload').fileinput("upload");
													}).on('fileuploaded', function(event, data){
														$('#fileupload').fileinput('clear');
														$('#form1').hide();
														$('.img-voorkant').show().find('img').attr('src', data.response.url);
														$('.div-achterkant').show();
														$('#form2').show();
														location.reload();
													});
												});
                                                {/literal}
											</script>

											<!-- form -->
											<form id="form1" action="#" style="{if $id_voorkant !== NULL }display:none;{/if}">
												<input name="file" type="file" id="fileupload" class="file-input">
											</form>

											<!-- plaatje -->
											<div class="img-voorkant" style="{if $id_voorkant === NULL }display:none;{/if}">
												<a href="{$id_voorkant}" target="_blank">
													<img class="img-idbewijs mb-2" style="max-width: 400px; max-height: 300px;" src="{$id_voorkant}"/>
												</a>
												<a href="javascript:void(0)" onclick="deleteIDbewijs( {$zzp->zzp_id}, 'voorkant')" class="text-danger">
													<i class="icon-trash mr-1"></i>
													ID bewijs verwijderen
												</a>
											</div>

										</div><!-- /voorkant -->

										<!-- achterkant -->
										<div class="col-xl-5 col-lg-12 div-achterkant" style="{if $id_voorkant === NULL }display:none;{/if}">

											<div class="mb-3 font-weight-semibold">Achterkant</div>

											<!-- script uploader 2 -->
											<script>
                                                {literal}
												$(document).ready(function(){
													$('#fileupload2').fileinput('refresh', {uploadUrl:'upload/uploadwerknemerid/{/literal}{$zzp->zzp_id}{literal}/2'});
													$('#fileupload2').on('filebatchselected', function(event, files){
														$('#fileupload2').fileinput("upload");
													}).on('fileuploaded', function(event, data){
														$('#fileupload2').fileinput('clear');
														$('#form2').hide();
														$('.img-achterkant').show().find('img').attr('src', data.response.url);
													});
												});
                                                {/literal}
											</script>

											<!-- script uploader 2 -->
											<form id="form2" action="#" style="{if $id_achterkant !== NULL }display:none;{/if}">
												<input name="file" type="file" id="fileupload2" class="file-input">
											</form>

											<!-- plaatje -->
											<div class="img-achterkant" style="{if $id_achterkant === NULL }display:none;{/if}">
												<a href="{$id_achterkant}" target="_blank">
													<img class="img-idbewijs mb-2" style="max-width: 400px; max-height: 300px;" src="{$id_achterkant}"/>
												</a>
												<a href="javascript:void(0)" onclick="deleteIDbewijs( {$zzp->zzp_id}, 'achterkant')" class="text-danger">
													<i class="icon-trash mr-1"></i>
													ID bewijs verwijderen
												</a>
											</div>

										</div><!-- /achterkant -->

									</div><!-- /row -->

								</fieldset>

								<form method="post" action="">
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold">ID bewijs gegevens</legend>


										<div class="form-group row">
											<label class="col-lg-2 col-form-label">Vervaldatum:</label>
											<div class="col-lg-6 text-right mb-3">
												<input required name="vervaldatum" value="{if isset($smarty.post.vervaldatum)}{$smarty.post.vervaldatum}{else}{if $vervaldatum !== NULL}{$vervaldatum|date_format: '%d-%m-%Y'}{/if}{/if}" type="text" class="form-control pickadate-id" style="width: 130px;"/>
											</div>
										</div>
									</fieldset>

									<button type="submit" name="set_wizard" class="btn btn-success btn-sm">
										<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
									</button>
								</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div>
			<!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}