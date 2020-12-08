{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "datatable" "true"}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='loonbeslagen'}


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

							<!-- nieuw loonbeslag -->
							<a href="javascript:void(0)" class="btn btn-outline-primary" data-toggle="modal" data-target="#nieuw">
								<i class="icon-add mr-1"></i>Nieuw loonbeslag
							</a>

							<table>

                                {if isset($loonbeslagen[NULL])}
	                                <tr>
		                                <td colspan="4">
			                                <fieldset class="mt-3">
				                                <legend class="text-uppercase font-size-sm font-weight-bold text-primary">Nieuwe loonbeslagen</legend>
			                                </fieldset>

		                                </td>
	                                </tr>
	                                <tr>
		                                <th>ID</th>
		                                <th>Beslaglegger</th>
		                                <th>Dossiernummer</th>
		                                <th>Hoofdsom</th>
	                                </tr>
                                    {foreach $loonbeslagen[NULL] as $l}
										<tr>
											<td>{$l.loonbeslag_id}</td>
											<td>{$l.beslaglegger}</td>
											<td>{$l.dossiernummer}</td>
											<td>€ {$l.hoofdsom}</td>
										</tr>
                                    {/foreach}
                                {/if}

								<tr>
									<td colspan="4">
										<fieldset class="mt-3">
											<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Informatieverzoeken</legend>
										</fieldset>

									</td>
								</tr>
								<tr>
									<td colspan="4">
										<fieldset class="mt-3">
											<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Definitieve beslagleggingen</legend>
										</fieldset>

									</td>
								</tr>
								<tr>
									<td colspan="4">
										<fieldset class="mt-3">
											<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Afgerond</legend>
										</fieldset>
									</td>
								</tr>
							</table>
						</div>


					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->

	<!---------------------------------------------------------------------------------------------------------
	|| Toevoegen modal
	---------------------------------------------------------------------------------------------------------->
	<div id="nieuw" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form action="" method="post">
					<div class="modal-header">
						<h5 class="modal-title">Nieuw loonbeslag</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body pt-4">

						<!-- Beslaglegger -->
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-3">Beslaglegger</label>
							<div class="col-sm-7">
								<input name="beslaglegger" type="text" class="form-control" required>
							</div>
						</div>

						<!-- Dossiernummer -->
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-3">Dossiernummer</label>
							<div class="col-sm-7">
								<input name="dossiernummer" type="text" class="form-control" required>
							</div>
						</div>

					</div>
					<div class="modal-footer">

						<button type="submit" name="go" class="btn btn-sm btn-success">
							<i class="icon-add mr-1"></i> Toevoegen
						</button>
						<button data-dismiss="modal" class="btn btn-sm btn-outline-danger">
							<i class="icon-cross "></i> Annuleren
						</button>

					</div>
				</form>
			</div>
		</div>
	</div>
{/block}