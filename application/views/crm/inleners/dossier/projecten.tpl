{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='projecten'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-11">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-md-11">

					<!-- Basic card -->
					<div class="card mb-2">

						<div class="card-body">


							<!--------------- toevoegen ---------------------------------------------------------------------------------------------------------------------------------------------------->
							<fieldset class="mb-1">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Project toevoegen</legend>
							</fieldset>

							<form method="post" action="">
								<table>
									<tr>
										<td>Project omschrijving</td>
										<td></td>
									</tr>
									<tr>
										<td class="pt-2 pr-2" style="width: 400px;">
											<input name="omschrijving" value="{if isset($input.omschrijving)}{$input.omschrijving}{/if}" type="text" class="form-control"/>
										</td>
										<td class="pt-2">
											<button type="submit" name="set" class="btn btn-success">
												<i class="icon-add mr-1"></i>Toevoegen
											</button>
										</td>
									</tr>
								</table>
							</form>

							<!--------------- overzicht ---------------------------------------------------------------------------------------------------------------------------------------------------->
							<fieldset class="mb-1 mt-4">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Projecten</legend>
							</fieldset>


							<table>
								<tr>
									<th class="pr-3">ID</th>
									<th class="pr-3">Project omschrijving</th>
									<th></th>
								</tr>
                                {if isset($projecten) && $projecten != NULL}
                                    {foreach $projecten as $p}
										<tr>
											<td>{$p.project_id}</td>
											<td>{$p.omschrijving}</td>
											<td>
												<a onclick="return confirm('Project verwijderen?');" class="text-danger" href="crm/inleners/dossier/projecten/{$inlener->inlener_id}?del={$p.id}">
													<i class="icon-trash mr-1"></i>verwijderen
												</a>
											</td>
										</tr>
                                    {/foreach}
                                {/if}

							</table>

						</div>
					</div>
				</div>
			</div>


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}