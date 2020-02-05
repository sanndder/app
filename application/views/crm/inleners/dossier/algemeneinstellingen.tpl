{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "select2" "true"}

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

								<fieldset class="mb-1">
									<legend class="text-uppercase font-size-sm font-weight-bold">Uitzender</legend>

                                    {* inlener is gekoppeld *}
                                    {if $inlener->uitzenderID() != NULL}
                                        {if $user_type == 'werkgever'}
	                                    <div class="mb-3">
											<a href="crm/uitzenders/dossier/overzicht/{$inlener->uitzenderID()}">
                                                {$inlener->uitzenderID()} - {$uitzenders[$inlener->uitzenderID()]}
											</a>
										</div>
	                                    {else}
                                            {$inlener->uitzenderID()} - {$uitzenders[$inlener->uitzenderID()]}
	                                    {/if}
                                        {if $user_type == 'werkgever'}
											<input type="hidden" name="uitzender_id" value="{$inlener->uitzenderID()}"/>
											<button type="submit" name="del" class="btn btn-danger">
												<i class="icon-unlink mr-1"></i>
												Koppeling verwijderen
											</button>
                                        {/if}

                                        {* inlener is NIET gekoppeld *}
                                    {else}
                                        {if $user_type == 'werkgever'}
											<div class="form-group row">
												<label class="col-form-label col-md-2">
													Koppelen aan uitzender
												</label>
												<div class="col-lg-6 col-md-8">

													<select name="uitzender_id" class="form-control select-search">
														<option value="0">Geen uitzender (payrollklant)</option>
                                                        {if $uitzenders !== NULL}
                                                            {foreach $uitzenders as $u}
																<option value="{$u@key}">{$u@key} - {$u}</option>
                                                            {/foreach}
                                                        {/if}
													</select>

												</div>
											</div>
											<button type="submit" name="set" class="btn btn-success">
												<i class="icon-link mr-1"></i>
												Inlener koppelen
											</button>
                                        {/if}
                                    {/if}

								</fieldset>

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}