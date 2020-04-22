{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Facturen & Marge{/block}
{assign "ckeditor" "true"}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="card">


				<div class="card-header header-elements-inline">
					<h5 class="card-title">Facturen & Marge</h5>
				</div>


				{if $facturen === NULL}
					<div class="table-responsive">
						<div class="p-4 font-italic">Geen facturen gevonden</div>
					</div>
				{else}
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th style="width: 25px;">Jaar</th>
									<th style="width: 25px;">Periode</th>
									<th>Inlener</th>
									<th style="width: 120px" class="text-right">Factuur nr</th>
									<th style="width: 120px" class="text-right">Verkoop (€)</th>
									<th style="width: 120px" class="text-right">Kosten (€)</th>
									<th style="width: 120px" class="text-right">Factuur nr</th>
									<th style="width: 120px" class="text-right">Marge (€)</th>
									<th style="width: 25px"></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
                                {foreach $facturen as $f}
									<tr>
										<td>{$f.verkoop.jaar}</td>
										<td>{$f.verkoop.periode}</td>
										<td style="width: 1px; white-space: nowrap;">
                                            {$f.verkoop.bedrijfsnaam}
                                            {if $f.verkoop.project != NULL}
												- {$f.verkoop.project}
                                            {/if}
										</td>
										<td class="text-right">
                                            {$f.verkoop.factuur_nr}
										</td>
										<td class="text-right">
											<a target="_blank" href="facturatie/factuur/view/{$f.verkoop.factuur_id}">
												€ {$f.verkoop.bedrag_incl|number_format:2:',':'.'}
											</a>
										</td>
										<td class="text-right">
											<a target="_blank" href="facturatie/factuur/viewkosten/{$f.verkoop.factuur_id}">
												€ {$f.verkoop.kosten_incl|number_format:2:',':'.'}
											</a>
										</td>
										<td class="text-right">
                                            {$f.marge.factuur_nr}
										</td>
										<td class="text-right">
											<a target="_blank" href="facturatie/factuur/view/{$f.marge.factuur_id}">
												€ {$f.marge.bedrag_incl|number_format:2:',':'.'}
											</a>
										</td>
										<td>
											<ul class="list-inline mb-0 mt-2 mt-sm-0">
												<li class="list-inline-item dropdown">
													<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown">
														<i class="icon-menu7"></i></a>

													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void()" class="dropdown-item">
															<i class="icon-file-download"></i> Download
														</a>
														{*
														<a href="crm/uitzenders/dossier/facturen/{$f.verkoop.uitzender_id}?del={$f.verkoop.factuur_id}" class="dropdown-item">
															<i class="icon-cross2"></i> Verwijderen
														</a>
	                                                     *}
													</div>
												</li>
											</ul>
										</td>
										<td></td>
									</tr>
                                {/foreach}
							</tbody>
						</table>
					</div>
                {/if}

			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}