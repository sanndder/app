{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='documentenn'}


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

			<div class="row col-md-12 m-0 p-0">


					<!-- Basic card -->
					<div class="card card-body m-0 p-0">

							<!-- flexbox-->
							<div class="d-inline-flex">

								<!------------------------------ Linker sidebar ---------------------------->
								<div class="fm-sidebar-left p-2 sidebar-light">

									<button type="button" class="btn btn-primary w-100">
										Bestand Uploaden
									</button>

									<hr class="my-2" />

									<ul class="nav nav-sidebar fm-maps" data-nav-type="accordion">
										<li class="nav-item-header font-weight-bolder py-0">
											<div class="text-uppercase font-size-xs line-height-xs">Mappen</div>
										</li>
										<li class="nav-item">
											<span href="instellingen/werkgever/users" class="nav-link py-1">
												<span><i class="icon-folder6 mr-1"></i> Contracten</span>
											</span>
										</li>
										<li class="nav-item">
											<span href="instellingen/werkgever/users" class="nav-link py-1">
												<span><i class="icon-folder6 mr-1"></i> BSN</span>
											</span>
										</li>
										<li class="nav-item">
											<span href="instellingen/werkgever/users" class="nav-link py-1">
												<span><i class="icon-folder-open3 mr-1"></i> UWV</span>
											</span>
										</li>
										<li class="nav-item">
											<span href="instellingen/werkgever/users" class="nav-link nav-link-2 py-1">
												<span><i class="icon-folder6 mr-1"></i> Correspondentie</span>
											</span>
										</li>
										<li class="nav-item">
											<span href="instellingen/werkgever/users" class="nav-link nav-link-2 py-1">
												<span><i class="icon-folder6 mr-1"></i> Ziekmeldingen</span>
											</span>
										</li>
									</ul>

								</div>

								<!------------------------------ midden ---------------------------->
								<div class="fm-content p-2 flex-fill">

									<!---- toolbar ---->
									<div class="fm-toolbar">
										<span class="fm-btn">
											<i class="icon-download4 mr-1"></i>Download
										</span>
										<span class="fm-btn">
											<i class="icon-pencil3 mr-1"></i>Naam wijzigen
										</span>
									</div>

									<hr class="my-2" />

									<!---- tabel ---->
									<table class="fm-files">
										<thead>
											<tr>
												<th style="width: 30px;">
													<i class="icon-checkbox-unchecked"></i>
												</th>
												<th style="width: 30px;"></th>
												<th>Naam</th>
												<th>Laatst gewijzigd</th>
												<th>Grootte</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><i class="icon-checkbox-unchecked"></i></td>
												<td><i class="icon-file-empty"></i></td>
												<td>Uitzendovereenkomst met uitzendbeding Fase A...</td>
												<td>09-06-2021</td>
												<td>156 KB</td>
											</tr>
										</tbody>
									</table>

								</div>

								<!------------------------------ Rechter sidebar ---------------------------->
								<div class="fm-sidebar-right">
									<ul class="nav nav-tabs nav-tabs-bottom">
										<li class="nav-item">
											<a href="#bottom-tab1" class="nav-link" data-toggle="tab">
												<i class="icon-info22"></i>
											</a>
										</li>
										<li class="nav-item">
											<a href="#bottom-tab1" class="nav-link active" data-toggle="tab">
												<i class="icon-bell3"></i>
											</a>
										</li>
									</ul>

									<div class="px-3 pt-1">

										<div>Geldig tot</div>
										<div>
											<input type="text" class="form-control" value="31-12-2022">
										</div>

										<div class="mt-3">Email melding</div>
										<div>
											<div class="form-check mt-1">
												<label class="form-check-label">
													<input name="aankoop" value="1" type="checkbox" class="form-input-styled"  checked="checked">
													Werknemer
												</label>
											</div>
											<div class="form-check mt-1">
												<label class="form-check-label">
													<input name="aankoop" value="1" type="checkbox" class="form-input-styled"  checked="checked">
													Uitzender
												</label>
											</div>
										</div>


									</div>

								</div>

							</div>

					</div><!-- /basic card -->

			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}