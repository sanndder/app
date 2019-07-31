{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{/block}

{block "content"}

	{include file='crm/uitzenders/dossier/_sidebar.tpl' active='contactpersonen'}
	{include file='crm/uitzenders/dossier/modals/contactpersonen.tpl'}

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
			<form method="post" action="">

				<div class="row">
					<div class="col-md-10">

						<!-- Basic card -->
						<div class="card">

							<div class="bg-light rounded-top">
								<div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top">
									<div class="text-center d-lg-none w-100">
										<button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-read">
											<i class="icon-circle-down2"></i>
										</button>
									</div>

									<div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-read">
										<div class="mt-3 mt-lg-0 mr-lg-3">
											<div class="btn-group">
												<button type="button" class="btn btn-light" data-id="0" onclick="modalContact(this, 'uitzender', {$uitzender->uitzender_id})">
													<i class="icon-plus-circle2"></i>
													<span class="d-none d-lg-inline-block ml-2">Contactpersoon toevoegen</span>
												</button>
											</div>
										</div>

										<div class="navbar-text ml-lg-auto"></div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				{foreach $contactpersonen as $contact}
					<div class="row">

						<div class="col-md-10">
							<!-- Basic card -->
							<div class="card">
								<div class="card-body">

									<div class="row">
										<div class="col-md-1 ml-0 text-right">
											<i class="icon-user icon-3x d-none d-lg-block" style="margin-top: -5px"></i>
										</div>

										<div class="col-md-4 mb-2">
											<div class="media-title font-weight-semibold">{$contact.aanhef}
												. {$contact.naam}</div>
											<span class="text-muted">{$contact.functie} {if $contact.afdeling != NULL} - {$contact.afdeling}{/if} </span>
										</div>

										<div class="col-md-3 mb-2">
											<ul class="list list-unstyled mb-0">
												<li><i class="icon-phone mr-2"></i> {$contact.telefoon} </li>
												<li><i class="icon-mail5 mr-2"></i> {$contact.email}</li>
											</ul>
										</div>

										<div class="col-md-3 font-italic">
											{if $contact.opmerking != NULL}
												{$contact.opmerking}
											{/if}
										</div>

										<div class="col-md-1 text-right">
											<button data-title="Contact persoon wijzigen" data-id="{$contact.contact_id}" type="button" class="btn btn-outline-info btn-icon rounded-round ml-1" onclick="modalContact(this, 'uitzender', {$uitzender->uitzender_id})" data-popup="tooltip" data-placement="top">
												<em class="icon-pencil mr-sm"></em>
											</button>
										</div>

									</div><!-- /row -->

								</div>
							</div>
						</div>
						<!-- /col -->
					</div>
					<!-- /row -->

				{/foreach}


			</form>
		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}