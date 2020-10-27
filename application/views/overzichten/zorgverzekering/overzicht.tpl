{extends file='../../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-folder-plus2{/block}
{block "header-title"}Overzicht - Zorgverzekering{/block}
{assign "datatable" "true"}
{assign "select2" "true"}

{block "content"}
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">

				<!---------------------------------------------------------------------------------------------------------
				|| werknemers
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-9">

					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Werknemers</h6>
						</div>

						<div class="card-body">

							<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="15" data-order="[[0,&quot;asc&quot; ],[2,&quot;asc&quot; ]]">
								<thead class="">
									<tr>
										<th></th>
										<th style="width: 75px;"></th>
										<th style="width: 75px;">ID</th>
										<th>Naam</th>
										<th>Uitzender</th>
										<th>Indienst</th>
										<th>Aangemeld op</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td>
										<td>
											<div class="form-check">
												<label class="form-check-label">
													<input type="checkbox" class="form-input-styled-info" checked>
												</label>
											</div>
										</td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>

				</div>

				<!---------------------------------------------------------------------------------------------------------
				|| bestanden
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Bestanden</h6>
						</div>

						<div class="card-body">


							<table>
								<tr>
									<td>

										<div class="d-flex align-items-center">
											<div class="mr-3">
												<a href="#" class="btn bg-teal-400 btn-icon">
													<i class="icon-file-empty"></i>
												</a>
											</div>
											<div>
												<a href="#" class="text-default font-weight-semibold letter-icon-title">Annabelle Doney</a>
												<div class="text-muted font-size-sm">Active</div>
											</div>
										</div>

									</td>
								</tr>
							</table>



						</div>
					</div>

				</div>
			</div>


		</div>
		<!-- /content area -->
	</div>
	<!-- /main content -->



{/block}