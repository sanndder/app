{extends file='../../layout.tpl'}
{block "title"}Overzicht werknemer data{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Overzicht - werknemer data{/block}
{assign "datatable" "true"}

{block "content"}

	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!---------------------------------------------------------------------------------------------------------
			|| Zijmenu
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-3">


					<div class="form-group form-group-feedback form-group-feedback-left">
						<input id="datatable-search" type="search" class="form-control py-3" placeholder="Tabel doorzoeken...">
						<div class="form-control-feedback mt-1">
							<i class="icon-search4 text-muted"></i>
						</div>
					</div>

					<!-------------------------------------------------- Instellingen -------------------------------------------------------------->
					<div class="card" style="position: sticky; top: 65px">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Instellingen</span>
						</div>

						<!-- Instellingen -->
						<div class="card-body">

						</div>
					</div>

				</div><!-- /col -->


				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">

						<!------------------------------------------------------ tabel ------------------------------------------------->
						<table class="table datatable-filter-column table-striped text-nowrap table-facturen-overzicht pt-0" style="font-size: 12px" data-page-length="500">
							<thead>
								<tr>
									<th style="width: 10px"></th>
									<th style="width: 10px">ID</th>
									<th>Naam</th>
									<th style="width: 100px">Indienst</th>
									<th style="width: 100px">Laatst gewerkt</th>
									<th style="width: 10px"></th>
								</tr>
							</thead>
                            {if $werknemers != NULL}
								<tbody>
                                    {foreach $werknemers as $w}
	                                    <tr>
		                                    <td></td>
		                                    <td>{$w.werknemer_id}</td>
		                                    <td>
			                                    <a href="crm/werknemers/dossier/overzicht/{$w.werknemer_id}" target="_blank">
			                                        {$w.naam}
			                                    </a>
		                                    </td>
		                                    <td>{if isset($w.indienst)}{$w.indienst|date_format: '%d-%m-%Y'}{/if}</td>
		                                    <td>{if isset($w.laatste_werkweek)}{$w.laatste_werkweek}{/if}</td>
		                                    <td></td>
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