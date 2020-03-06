{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Facturatie{/block}
{assign "datatable" "true"}

{block "content"}

	<!---------------------------------------------------------------------------------------------------------
	|| Zijmenu
	---------------------------------------------------------------------------------------------------------->
	<div class="sidebar sidebar-light sidebar-main sidebar-sections sidebar-expand-lg align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Zijmenu</span>
			<a href="#" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->

		<!-- Sidebar content -->
		<div class="sidebar-content">

			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-------------------------------------------------- Knoppen -------------------------------------------------------------->
				<div class="card-body">
					<div class="row">
						<div class="col">
							<a href="facturatie/concepten/afgesprokenwerk" class="btn bg-teal-400 btn-block btn-float">
								<i class="icon-file-plus icon-2x"></i>
								<span>Factuur</span>
							</a>

							<button type="button" class="btn bg-purple-300 btn-block btn-float">
								<i class=" icon-stats-bars icon-stats-bars icon-2x"></i>
								<span>Statistieken</span>
							</button>
						</div>

						<div class="col">
							<button type="button" class="btn bg-warning-400 btn-block btn-float">
								<i class="icon-search4 icon-2x"></i>
								<span>Zoeken</span>
							</button>

							<button type="button" class="btn bg-blue btn-block btn-float">
								<i class="icon-cog3 icon-2x"></i>
								<span>Instellingen</span>
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-------------------------------------------------- Zoeken in wachtrij -------------------------------------------------------------->

			<div class="card card-sidebar-mobile">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Doorzoek wachtrij</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body">

					<form action="" method="get">
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="q1" value="{if isset($smarty.get.q1)}{$smarty.get.q1}{/if}" type="search" class="form-control" placeholder="Bedrijfsnaam">
							<div class="form-control-feedback">
								<i class="icon-office text-muted"></i>
							</div>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="q2" value="{if isset($smarty.get.q2)}{$smarty.get.q2}{/if}" type="search" class="form-control" placeholder="Overige zoektermen">
							<div class="form-control-feedback">
								<i class="icon-search4 text-muted"></i>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<button type="submit" class="btn bg-blue btn-block">
									<i class="icon-search4 font-size-base mr-2"></i>
									Zoeken
								</button>
							</div><!-- /col -->
							<div class="col-md-6">
								<a href="facturatie/overzicht" class="btn btn-light" style="width: 100%">
									<i class="icon-cross font-size-base mr-2"></i>
									Wissen
								</a>
							</div>
						</div><!-- /row -->
					</form>
				</div>

			</div><!-- /main navigation -->
		</div>
		<!-- /sidebar content -->

	</div>

	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-md-12">

					<!-- Basic card -->
					<div class="card">

						<!------------------------------------------------------ header ------------------------------------------------->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Facturen wachtrij</span>
							<div class="header-elements">
							</div>
						</div>

						<!------------------------------------------------------ tabel ------------------------------------------------->
						<table class="table table-striped text-nowrap datatable-basic no-footer" data-order="[[1,&quot;asc&quot; ]]">
							<thead>
								<tr role="row">
									<th>Periode</th>
									<th>Inlener</th>
									<th>Factuur nr.</th>
									<th>Bedrag</th>
									<th>Aangemaakt</th>
									<th>Verzenden</th>
								</tr>
							</thead>
                            {if $facturen != NULL}
								<tbody>
                                    {foreach $facturen as $f}
										<tr>
											<td>{$f.verkoop.periode}</td>
											<td>
                                                {$f.verkoop.bedrijfsnaam}
                                                {if $f.verkoop.project != NULL}
													- {$f.verkoop.project}
                                                {/if}
											</td>
											<td class="text-right">
                                                {$f.verkoop.factuur_nr}
											</td>
											<td class="text-right">
												€ {$f.verkoop.bedrag_incl|number_format:2:',':'.'}
											</td>
											<td>
												<a target="_blank" href="facturatie/factuur/view/{$f.verkoop.factuur_id}">
													factuur_{$f.verkoop.jaar}_{$f.verkoop.periode}.pdf
												</a>
											</td>
											<td class="text-right">
												€ {$f.verkoop.kosten_incl|number_format:2:',':'.'}
											</td>
											<td>
												<a target="_blank" href="facturatie/factuur/viewkosten/{$f.verkoop.factuur_id}">
													kostenoverzicht_{$f.verkoop.jaar}_{$f.verkoop.periode}.pdf
												</a>
											</td>
											<td class="text-right">
                                                {$f.marge.factuur_nr}
											</td>
											<td class="text-right">
												€ {$f.marge.bedrag_incl|number_format:2:',':'.'}
											</td>
											<td>
												<a target="_blank" href="facturatie/factuur/view/{$f.marge.factuur_id}">
													margefactuur_{$f.verkoop.jaar}_{$f.verkoop.periode}.pdf
												</a>
											</td>
											<td>
												<ul class="list-inline mb-0 mt-2 mt-sm-0">
													<li class="list-inline-item dropdown">
														<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown">
															<i class="icon-menu7"></i></a>

														<div class="dropdown-menu dropdown-menu-right">
															<a href="crm/uitzenders/dossier/facturen/{$f.verkoop.uitzender_id}?email={$f.verkoop.factuur_id}" class="dropdown-item">
																<i class="icon-envelop2"></i> Emailen
															</a>
															<a href="javascript:void()" class="dropdown-item">
																<i class="icon-file-download"></i> Download
															</a>
															<a href="crm/uitzenders/dossier/facturen/{$f.verkoop.uitzender_id}?del={$f.verkoop.factuur_id}" class="dropdown-item">
																<i class="icon-cross2"></i> Verwijderen
															</a>
														</div>
													</li>
												</ul>
											</td>
										</tr>
                                    {/foreach}
								</tbody>
                            {/if}

						</table>


					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->



		</div><!-- /content area -->
	</div>
	<!-- /main content -->

{/block}