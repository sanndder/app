{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}

{block "content"}

    {include file='instellingen/werkgever/_sidebar.tpl' active='bankrekeningen'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xxl-6 col-xl-10">

                    {if isset($msg) && !is_array($msg)}
						<div class="row">
							<div class="col-md-12">
                                {$msg}
							</div><!-- /col -->
						</div>
						<!-- /row -->
                    {/if}

                    {include file='instellingen/werkgever/_topbar.tpl'}

					<form method="post" action="">

                        {foreach $bankrekeningen as $formdata}
							<!-- Basic card -->
							<div class="card">
								<div class="card-body">

                                    {if isset($msg[$formdata@key]) && is_array($msg)}
										<div class="row">
											<div class="col-md-12">
                                                {$msg[$formdata@key]}
											</div><!-- /col -->
										</div>
										<!-- /row -->
                                    {/if}

									<div class="row">
										<div class="col-md-10">

											<!-- omschrijving -->
                                            {if isset($formdata.omschrijving)}
                                                {assign "field" "omschrijving"}
												<div class="form-group row">
													<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
														:
													</label>
													<div class="col-xl-8 col-md-8">
														<input value="{$formdata.$field.value}" name="{$field}[{$formdata@key}]" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                        {if isset($formdata.$field.error)}
															<span class="form-text text-danger">
															{foreach $formdata.$field.error as $e}
                                                                {$e}
																<br/>
                                                            {/foreach}
															</span>
                                                        {/if}
													</div>
												</div>
                                            {/if}


											<!-- iban -->
                                            {if isset($formdata.iban)}
                                                {assign "field" "iban"}
												<div class="form-group row">
													<label class="col-lg-3 col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
														:
													</label>
													<div class="col-xl-8 col-md-8">
														<input value="{$formdata.$field.value}" name="{$field}[{$formdata@key}]" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                        {if isset($formdata.$field.error)} <span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}</span>{/if}
													</div>
												</div>
                                            {/if}

										</div><!-- /col -->
										<div class="col-md-2">
											<!-- buttons -->
											<div class="col-sm-12 text-right">
												<button name="set[{$formdata@key}]" type="submit" class="btn btn-outline-success btn-icon rounded-round" data-popup="tooltip" data-placement="top" data-original-title="Opslaan">
													<em class="icon-check mr-sm"></em>
												</button>
                                                {if $formdata@key != 0}
													<button data-title="Bankrekening verwijderen?" data-id="{$formdata@key}" name="del[]" type="button" class="sweet-confirm btn btn-outline-danger btn-icon rounded-round ml-1" data-popup="tooltip" data-placement="top" data-original-title="Verwijderen">
														<em class="icon-cross mr-sm"></em>
													</button>
                                                {/if}
											</div>
										</div>
									</div><!-- /row -->


								</div><!-- /card body -->
							</div>
							<!-- /basic card -->
                        {/foreach}

					</form>

				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}