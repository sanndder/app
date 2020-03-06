{extends file='../../layout.tpl'}
{block "title"}Werknemers{/block}
{block "header-icon"}icon-files-empty{/block}
{block "header-title"}Werknemer documenten{/block}

{block "content"}

	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main sidebar
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="sidebar sidebar-light sidebar-main sidebar-sections sidebar-expand-lg align-self-start">
		<!-- Sidebar content -->
		<div class="sidebar-content">

			<!---------------------------------------------------------------------------------------------------------
			||Snel zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-- knoppen -->
				<div class="card-body pt-3">

					<ul class="nav nav-sidebar mb-3">
						<li class="nav-item-header p-0">Snel naar <i class="icon-arrow-right5" style="display:inline-block"></i> </li>
					</ul>

					{* uitzenders *}
					<div class="d-flex align-items-center mb-3 mb-sm-0">
						<a href="crm/uitzenders">
							<div class="rounded-circle bg-teal-400">
								<i class="icon-office icon-xl text-white p-2"></i>
							</div>
						</a>
						<a href="crm/uitzenders" class="text-default">
							<div class="ml-3">
								<h5 class="font-weight-semibold mb-0"></h5>
								<span class="text-muted text-uppercase">Uitzenders</span>
							</div>
						</a>
					</div>

					{* inleners *}
					<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
						<a href="crm/inleners" class="text-default">
							<div class="rounded-circle bg-warning-400">
								<i class="icon-user-tie icon-xl text-white p-2"></i>
							</div>
						</a>
						<a href="crm/inleners" class="text-default">
							<div class="ml-3">
								<h5 class="font-weight-semibold mb-0"></h5>
								<span class="text-muted text-uppercase">Inleners</span>
							</div>
						</a>
					</div>

					{* werknemers of zzp'ers *}
					<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
						<a href="crm/werknemers" class="text-default">
							<div class="rounded-circle bg-blue">
								<i class="icon-user icon-xl text-white p-2"></i>
							</div>
						</a>
						<a href="crm/werknemers" class="text-default">
							<div class="ml-3">
								<h5 class="font-weight-semibold mb-0"></h5>
								<span class="text-muted text-uppercase">Werknemers</span>
							</div>
						</a>
					</div>


				</div>
			</div>

		</div>
	</div>


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
				<div class="col-xl-12">

					<!-- Basic card -->
					<div class="card">

						<table class="table">
							<thead>
								<tr>
									<th style="width: 60px">ID</th>
									<th>werknemer</th>
									<th style="width: 30px; text-align: right"></th>
									<th style="width: 120px">contract</th>
									<th style="width: 120px">start</th>
									<th style="width: 120px">einde</th>
									<th style="width: 120px">wekentelling
									<th style="width: 180px">verzonden
									<th style="width: 240px">ondertekend
									<th></th>
								</tr>
							</thead>
							<tbody>
								{if $documenten !== NULL}
									{foreach $documenten as $d}
										<tr>
											<td>
												<a href="crm/werknemers/dossier/documenten/{$d.werknemer_id}">
													{$d.werknemer_id}
												</a>
											</td>
											<td>
												<a href="crm/werknemers/dossier/documenten/{$d.werknemer_id}">
													{$d.naam}
												</a>
											</td>
											<td class="pr-0">
												<a target="_blank" href="documenten/pdf/view/{$d.document_id}">
													<img src="recources/img/icons/pdf.svg" style="height: 20px">
												</a>
											</td>
											<td>
												<a target="_blank" href="documenten/pdf/view/{$d.document_id}">
													Fase {$d.fase}
												</a>
											</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>
												{if $d.send == 0}
													<span class="text-warning-800"><i class="icon-warning2"></i> niet verzonden</span>
												{else}
													<span class="text-success font-weight-bold"><i class="icon-check mr-1"></i>{$d.send_on|date_format: '%d-%m-%Y'}</span>
												{/if}
											</td>
											<td>{if $d.signed == 0}
                                                    {if $d.send == 1}
														<i class="icon-hour-glass2 mr-1"></i> wachten op ondertekening
	                                                {/if}
                                                {else}
													<span class="text-success font-weight-bold"><i class="icon-check mr-1"></i>{$d.signed_on|date_format: '%d-%m-%Y'}</span>
                                                {/if}
											</td>
											<td></td>
										</tr>
                                    {/foreach}
								{/if}
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>
{/block}