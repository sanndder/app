{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

    {include file='crm/inleners/dossier/modals/kopieer.tpl'}
    {include file='crm/inleners/dossier/_sidebar.tpl' active='overzicht'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}

			<div class="row">
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Left side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-9">

					<!----------------------------------------------- card: Bedrijfsgegevens ------------------------------------------------------------->
					<div class="card">
						<div class="card-body">

							<div class="media">

								<div class="media-body">
									<div class="row">
										<div class="col-md-12">
											<h5 class="mt-0">{$inlener->bedrijfsnaam}</h5>
										</div><!-- /col -->
									</div><!-- /row -->

									<div class="row">
										<div class="col-md-6 col-xxl-3">

											<ul class="list-unstyled">
												<li>{$bedrijfsgegevens.straat} {$bedrijfsgegevens.huisnummer} {$bedrijfsgegevens.huisnummer_toevoeging}</li>
												<li>{$bedrijfsgegevens.postcode} {$bedrijfsgegevens.plaats}</li>
												<li class="mt-2"></li>
												<li>{$bedrijfsgegevens.telefoon}</li>
												<li>{$emailadressen.standaard}</li>
											</ul>

										</div><!-- /col -->
										<div class="col-md-6 col-xxl-3">
											<ul class="list-unstyled">
												<li>KvK: {$bedrijfsgegevens.kvknr}</li>
												<li>BTW: {$bedrijfsgegevens.btwnr}</li>
												<li class="mt-2"></li>
                                                {if $bedrijfsgegevens.postbus_nummer != NULL}
													<li>Postbus {$bedrijfsgegevens.postbus_nummer}</li>
													<li>{$bedrijfsgegevens.postbus_postcode} {$bedrijfsgegevens.postbus_plaats}</li>
                                                {/if}
											</ul>
										</div>
									</div><!-- /row -->

								</div>
							</div>


						</div><!-- /card body-->
					</div><!-- /card: Bedrijfsgegevens  -->

					<!---------------------------------------------------- card: Facturen ------------------------------------------------------>
					<div class="card">


						<div class="card-header header-elements-inline">
							<h5 class="card-title">Recente facturen</h5>
						</div>

						<div class="card-body">
							<span><i>Geen fatcuren gevonden</i></span>
						</div>

                        {*
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th style="width: 25px;">Jaar</th>
										<th style="width: 25px;">Periode</th>
										<th style="width: 25px;">Nr.</th>
										<th>PDF</th>
										<th>Bedrag excl.</th>
										<th style="width: 25px"></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>2019</td>
										<td>30</td>
										<td>45669</td>
										<td>factuur_2019_30.pdf</td>
										<td>€ 250,59</td>
										<td>
											<ul class="list-inline mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
													</div>
												</li>
											</ul>
										</td>
									</tr>
									<tr>
										<td>2019</td>
										<td>29</td>
										<td>45659</td>
										<td>factuur_2019_30.pdf</td>
										<td>€ 250,59</td>
										<td>
											<ul class="list-inline mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
													</div>
												</li>
											</ul>
										</td>
									</tr>
									<tr>
										<td>2019</td>
										<td>28</td>
										<td>45449</td>
										<td>factuur_2019_30.pdf</td>
										<td>€ 250,59</td>
										<td>
											<ul class="list-inline mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
													</div>
												</li>
											</ul>
										</td>
									</tr>
									<tr>
										<td>2019</td>
										<td>27</td>
										<td>45379</td>
										<td>factuur_2019_30.pdf</td>
										<td>€ 250,59</td>
										<td>
											<ul class="list-inline mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
														<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
													</div>
												</li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
*}

					</div><!-- /card: Facturen  -->

				</div><!-- / left side -->
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Right side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<!------------------------------------------------------- card: Status ------------------------------------------------------>
                    {if $user_type == 'werkgever'}
						<div class="card">
							<div class="card-header bg-transparent header-elements-inline">
								<span class="text-uppercase font-size-sm font-weight-semibold">Status</span>
							</div>

							<div class="card-body">
								<ul class="media-list">

									<li class="media">
										<div class="mr-2">
											{if $acties.user != NULL}
												<i class="icon-checkbox-checked2 text-green-700"></i>
											{else}
												<i class="icon-checkbox-unchecked2 text-grey-300"></i>
											{/if}
										</div>
										<div class="media-body {if $acties.user == NULL} text-grey-300{/if}">
											User aangemaakt en verzonden naar inlener
										</div>
									</li>

									<li class="media mt-2">
										<div class="mr-2">
                                            {if $acties.user_password != NULL}
												<i class="icon-checkbox-checked2 text-green-700"></i>
                                            {else}
												<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                            {/if}
										</div>
										<div class="media-body {if $acties.user_password == NULL} text-grey-300{/if}">
											Inlener heeft een wachtwoord aangemaakt
										</div>
									</li>

									<li class="media mt-2">
										<div class="mr-2">
                                            {if $acties.av_id != NULL}
												<i class="icon-checkbox-checked2 text-green-700"></i>
                                            {else}
												<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                            {/if}
										</div>
										<div class="media-body {if $acties.av_id == NULL} text-grey-300{/if}">
											Algemene voowaarden zijn getekend
										</div>
									</li>

									<li class="media mt-2">
										<div class="mr-2">
                                            {if $acties.overeenkomst_opdracht != NULL}
												<i class="icon-checkbox-checked2 text-green-700"></i>
                                            {else}
												<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                            {/if}
										</div>
										<div class="media-body {if $acties.overeenkomst_opdracht == NULL} text-grey-300{/if}">
											Overeenkomst van opdracht is getekend
										</div>
									</li>

								</ul>
							</div>
						</div>
						<!-- /card: Status  -->
                    {/if}

                    {if $user_type == 'werkgever'}
					<!------------------------------------------------------- card: acties ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Gekoppelde Ondernemingen</span>
							<div class="header-elements">
								<div class="list-icons">
								</div>
							</div>
						</div>

						<ul class="list-group list-group-flush m-0 p-0">
                            {foreach $ondernemingen as $o}
                                {if isset($o.bedrijfsnaam)}
									<li class="list-group-item p-0 pt-1">
										<div class="pl-3 py-2">
											<i style="font-size: 21px" class="icon-checkmark-circle text-success mr-2"></i>
                                            {$o.name}
										</div>
									</li>
                                {/if}
                            {/foreach}
							<li class="list-group-item p-0">
								<a href="javascript:void(0)"  class="dropdown-item py-3 pl-3" data-toggle="modal" data-target="#modal_copy">
									<i class="icon-copy4 mr-2"></i> Kopiëren naar andere onderneming
								</a>
							</li>
						</ul>
					</div><!-- /card: acties  -->
					{/if}

					<!------------------------------------------------------- card: Uitzender ------------------------------------------------------>
                    {if $user_type == 'werkgever'}
						<div class="card">
							<div class="card-header bg-transparent header-elements-inline">
								<span class="text-uppercase font-size-sm font-weight-semibold">Uitzender</span>
								<div class="header-elements">
									<div class="list-icons">
										<a href="crm/inleners/dossier/algemeneinstellingen/{$inlener->inlener_id}" data-popup="tooltip" data-placement="top" data-title="Wijzig uitzender">
											<i class="icon-pencil7"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="card-body">
                                {if $inlener->uitzenderID() == NULL}
									<i>Inlener is niet aan uitzender gekoppeld</i>
                                {else}
                                    {$inlener->uitzenderID()} - {$uitzender}
                                {/if}

							</div>
						</div>
						<!-- /card: Uitzender  -->
                    {/if}

					<!------------------------------------------------------- card: Accountmanager ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Accountmanager</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Wijzig accountmanager">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div><!-- /card: Accountmanager  -->

					<!------------------------------------------------------- card: Gebruikers --------------------------------------------------------->
                    {if $user_type == 'werkgever'}
						<div class="card">
							<div class="card-header bg-transparent header-elements-inline">
								<span class="text-uppercase font-size-sm font-weight-semibold">Users</span>
								<div class="header-elements">
									<div class="list-icons">
										<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Usermanagement">
											<i class="icon-pencil7"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="card-body">

								<ul class="media-list">
                                    {if $users == NULL }
										<a href="{$base_url}/instellingen/werkgever/users/add?id={$inlener->inlener_id}&user_type=inlener">User aanmaken</a>
                                    {else}
                                        {foreach $users as $u}
											<li class="media mt-0">

												<div class="media-body">
													<a href="#" class="media-title font-weight-semibold">{$u.username}</a>
													<div class="font-size-sm text-muted">{$u.naam}</div>
												</div>
                                                {*
												<div class="ml-3 align-self-center">
													<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Login als">
														<i class="icon-enter"></i>
													</a>
												</div>
												*}
											</li>
                                        {/foreach}
                                    {/if}
								</ul>

							</div>
						</div>
						<!-- /card: users  -->
                    {/if}

				</div><!-- / Right side -->
			</div><!-- /einde main row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}