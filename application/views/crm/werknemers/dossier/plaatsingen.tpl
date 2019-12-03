{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "select2" "true"}

{block "content"}

	{include file='crm/werknemers/dossier/_sidebar.tpl' active='plaatsingen'}


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

							<!--------------------------------------------- Uitzender ------------------------------------------------->
							<form method="post" action="">
								<fieldset class="">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Uitzender</legend>
								</fieldset>

								<table>
									<tr>
										<td class="pr-2" style="width: 500px">
											<select required name="uitzender_id" class="form-control select-search">
												<option value="">Selecteer een uitzender</option>
                                                {if $uitzenders !== NULL}
                                                    {foreach $uitzenders as $u}
														<option {if $werknemer_uitzender.uitzender_id == $u@key} selected{/if} value="{$u@key}">{$u@key} - {$u}</option>
                                                    {/foreach}
                                                {/if}
											</select>
										</td>
										<td>
											<button type="submit" name="set" value="set_uitzender" class="btn btn-outline-success btn-sm">
												<i class="icon-check mr-1"></i>Wijzigen
											</button>
										</td>
									</tr>
								</table>

							</form>


							<!--------------------------------------------- Plaatsing ------------------------------------------------->
							<form method="post" action="">
								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Plaatsingen</legend>
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