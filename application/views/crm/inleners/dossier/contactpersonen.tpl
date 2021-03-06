{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='contactpersonen'}
    {include file='crm/inleners/dossier/modals/contactpersonen.tpl'}

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
				<div class="col-md-10">

					<!-- Basic card -->
					<div class="card">

						<div class="bg-light rounded-top">
							<div class="navbar navbar-light bg-light navbar-expand-sm py-header rounded-top">

								<div class="navbar-collapse text-center text-lg-left flex-wrap collapse show" id="inbox-toolbar-toggle-read">
									<div class="mt-3 mt-lg-0 mr-lg-3">
										<div class="btn-group">
											<button type="button" class="btn btn-light btn-sm" data-id="0" onclick="modalContact(this, 'inlener', {$inlener->inlener_id})">
												<i class="icon-plus-circle2"></i>
												<span class="d-none d-inline-block ml-2">Contactpersoon toevoegen</span>
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

            {if $contactpersonen == NULL}
				<div class="">
					<img style="width: 75px; margin-left: 50px; margin-top: -30px;" src="recources/img/arrow.png"/>
					<span style="display:inline-block; font-size: 16px; margin-left: 15px; margin-top: 30px;">Voeg een contactpersoon toe om uw inschrijving af te ronden. <br />
					<span class="font-weight-bold">Uw eerste contactpersoon moet bevoegd zijn namens uw bedrijf overeenkomsten aan te gaan.</span></span>

				</div>
            {/if}

            {if $contactpersonen !== NULL}
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
											<div class="media-title font-weight-semibold">
                                                {$contact.aanhef}. {$contact.naam}
											</div>
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
											<button data-title="Contact persoon wijzigen" data-id="{$contact.contact_id}" type="button" class="btn btn-outline-info btn-icon rounded-round ml-1" onclick="modalContact(this, 'inlener', {$inlener->inlener_id})" data-popup="tooltip" data-placement="top">
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
            {/if}

            {if $contactpersonen !== NULL && $user_type == 'werkgever' && $inlener->complete == 0}
				<div class="row">
					<div class="col-md-12">
						<form method="post" action="">
							<button type="submit" name="set" value="{key($contactpersonen)}" class="btn btn-success">
								<i class="icon-check mr-1"></i>Contactpersoon goedkeuren
							</button>
						</form>
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

            {if $contactpersonen !== NULL && $user_type == 'uitzender' && $inlener->complete == 0}
				<div class="row">
					<div class=" col-md-10">
						<div class="alert alert-success alert-styled-left alert-arrow-left">
							<span class="font-weight-semibold">Aanmelden nieuwe inlener voldtooid.</span>
							Wij controleren zo spoedig mogelijk de door u ingevoerde gegevens.
						</div>
					</div>
				</div>
            {/if}

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}