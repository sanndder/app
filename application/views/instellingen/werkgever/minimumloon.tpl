{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}
{assign "uploader" "true"}

{block "content"}

    {include file='instellingen/werkgever/_sidebar.tpl' active='minimumloon'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Bedrijfsgegevens
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Minimumloon aanpassen</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

                        {if isset($msg)}
							<div class="row">
								<div class="col-md-12">
                                    {$msg}
								</div><!-- /col -->
							</div>
							<!-- /row -->
                        {/if}

                        {if count($formdata) > 0}
							<table class="table">
                                {foreach $formdata as $row}
									<tr>
										<td style="width: 200px;">{$row.label}</td>
										<td>
											<div class="input-group" style="width: 200px">
                                                {if $row@index > 0}
													<span class="input-group-prepend">
														<span class="input-group-text">€</span>
													</span>
	                                                <input name="{$row@key}" type="text" class="form-control text-right" value="{$row.value}">

                                                {else}
	                                                <div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text"><i class="icon-calendar5"></i></span>
														</span>
		                                                <input name="{$row@key}" value="{$row.value}" type="text" class="form-control pickadate"/>
	                                                </div>
                                                {/if}
											</div>
										</td>
										<td>
											{if isset($row.error)}
												{foreach $row.error as $e}
													<span class="text-danger">{$e}</span><br />
												{/foreach}
											{/if}
										</td>
									</tr>
                                {/foreach}
							</table>
                        {/if}


						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success">
									<i class="icon-checkmark2 mr-1"></i>Opslaan
								</button>
							</div><!-- /col -->
						</div><!-- /row -->


					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}