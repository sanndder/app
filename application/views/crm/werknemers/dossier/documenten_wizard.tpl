{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
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

							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">ID bewijs uploaden</legend>


								<div class="row">

									<!-- voorkant -->
									<div class="col-xl-6 col-lg-12">

										<div class="mb-3 font-weight-semibold">Voorkant</div>

										<!-- script uploader 1 -->
										<script>
                                            {literal}
                                            $( document ).ready( function() {
                                                $( '#fileupload' ).fileinput( 'refresh', {uploadUrl: 'upload/uploadwerknemerid/{/literal}{$werknemer->werknemer_id}{literal}/1'} );
                                                $( '#fileupload' ).on( 'filebatchselected', function( event, files ) {
                                                    $( '#fileupload' ).fileinput( "upload" );
                                                } ).on( 'fileuploaded', function( event, data ) {
                                                    $( '#form1' ).hide();
                                                    $( '.id-voorkant' ).append( '<img style="max-width: 400px; max-height: 300px;" src="' + data.response.url + '" />' );
                                                    $( '.div-achterkant').show();
                                                } );
                                            } );
                                            {/literal}
										</script>

										<!-- form -->
										<div class="id-voorkant" style="{if $id_voorkant !== NULL }display:none;{/if}">
											<form id="form1" action="#">
												<input name="file" type="file" id="fileupload" class="file-input">
											</form>
										</div>

										<!-- plaatje -->
										<img style="max-width: 400px; max-height: 300px;" src="{$id_voorkant}"/>
									</div>

									<!-- achterkant -->
									<div class="col-xl-6 col-lg-12 div-achterkant"  style="{if $id_voorkant === NULL }display:none;{/if}">

										<div class="mb-3 font-weight-semibold">Achterkant</div>

										<!-- script uploader 2 -->
										<script>
                                            {literal}
                                            $( document ).ready( function() {
                                                $( '#fileupload2' ).fileinput( 'refresh', {uploadUrl: 'upload/uploadwerknemerid/{/literal}{$werknemer->werknemer_id}{literal}/1'} );
                                                $( '#fileupload2' ).on( 'filebatchselected', function( event, files ) {
                                                    $( '#fileupload2' ).fileinput( "upload" );
                                                } ).on( 'fileuploaded', function( event, data ) {
                                                    $( '#form2' ).hide();
                                                    $( '.id-achterkant' ).append( '<img style="max-width: 400px; max-height: 300px;" src="' + data.response.url + '" />' );
                                                } );
                                            } );
                                            {/literal}
										</script>

										<!-- script uploader 2 -->
										<div class="id-achterkant">
											<form id="form2" action="#">
												<input name="file" type="file" id="fileupload2" class="file-input">
											</form>
										</div>

									</div>

								</div><!-- /row -->

							</fieldset>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}