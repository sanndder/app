{extends file='../../layout.tpl'}
{block "title"}Werknemers{/block}
{block "header-icon"}icon-question3{/block}
{block "header-title"}Prospects{/block}
{assign "datatable" "true"}


{block "content"}



	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<div class="row">
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Links taken
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-4">

					<!-- Basic card -->
					<div class="card">

						<!-- Tabs -->
						<ul class="nav nav-tabs nav-tabs-solid nav-justified bg-blue border-x-0 border-bottom-0 border-top-indigo-300 mb-0">
							<li class="nav-item">
								<a href="#messages-tue" class="nav-link font-size-sm text-uppercase active" data-toggle="tab">
									Vandaag
								</a>
							</li>

							<li class="nav-item">
								<a href="#messages-mon" class="nav-link font-size-sm text-uppercase" data-toggle="tab">
									Deze week
								</a>
							</li>

							<li class="nav-item">
								<a href="#messages-fri" class="nav-link font-size-sm text-uppercase" data-toggle="tab">
									Afgerond
								</a>
							</li>
						</ul>
						<!-- /tabs -->


						<!-- Tabs content -->
						<div class="tab-content card-body">
							<div class="tab-pane fade" id="messages-tue">
								<ul class="media-list">
									<li class="media">
										<div class="mr-3 position-relative">
											<img src="../../../../global_assets/images/demo/users/face10.jpg" class="rounded-circle" width="36" height="36" alt="">
											<span class="badge bg-danger-400 badge-pill badge-float border-2 border-white">8</span>
										</div>

										<div class="media-body">
											<div class="d-flex justify-content-between">
												<a href="#">James Alexander</a>
												<span class="font-size-sm text-muted">14:58</span>
											</div>

											The constitutionally inventoried precariously...
										</div>
									</li>

								</ul>
							</div>

							<div class="tab-pane fade" id="messages-mon">
								<ul class="media-list"></ul>
							</div>

							<div class="tab-pane fade active show" id="messages-fri">
								<ul class="media-list"></ul>
							</div>
						</div>
						<!-- /tabs content -->


					</div>

				</div><!-- /col -->

				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| rechts prospects
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-8">

                    {if isset($msg)}{$msg}{/if}

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body pb-1">
							<div class="media flex-column flex-md-row">

								<div class="media-body">
									<h6 class="mb-0">
										<a href="javascript:void(0)" data-toggle="modal" data-target="#nieuw" class="btn bg-blue rounded-round btn-icon btn-sm mr-1">
											<span class="letter-icon font-weight-bold" style="font-size: 18px; line-height: 23px">+</span>
										</a>
										Prospects
									</h6>
								</div>

								<div class="form-group form-group-feedback form-group-feedback-left">
									<input id="datatable-search" type="search" class="form-control" placeholder="Tabel doorzoeken..." style="width: 350px">
									<div class="form-control-feedback">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>
							</div>

						</div><!-- /card body-->


						<!-- table -->
						<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="25" data-order="[[0,&quot;asc&quot; ],[2,&quot;asc&quot; ]]">
							<thead class="">
								<tr>
									<th></th>
									<th>Bedrijfsnaam</th>
									<th>KvK</th>
									<th>Plaats</th>
									<th>Telefoon</th>
									<th>Email</th>
									<th>Status</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            {if isset($prospects) && is_array($prospects) && count($prospects) > 0}
								<tbody>
                                    {foreach $prospects as $p}
	                                    <tr>
		                                    <td>{$p.status_id}</td>
		                                    <td>
			                                    <a href="crm/prospects/prospects/details/{$p.prospect_id}">
			                                      {$p.bedrijfsnaam}
			                                    </a>
		                                    </td>
		                                    <td>{$p.kvknr}</td>
		                                    <td>{$p.plaats}</td>
		                                    <td>{$p.telefoon}</td>
		                                    <td>{$p.email}</td>
		                                    <td>{$p.status}</td>
		                                    <td></td>
	                                    </tr>
                                    {/foreach}
								</tbody>
                            {/if}
						</table>


					</div>
					<!-- /basic card -->
				</div><!-- /col -->
			</div><!-- /row -->


		</div>
		<!-- /content area -->
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
						<h5 class="modal-title">Nieuwe prospect</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body pt-4">

						<!-- Bedrijfsnaam -->
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-3">Bedrijfsnaam</label>
							<div class="col-sm-7">
								<input name="bedrijfsnaam" type="text" class="form-control" required value="{if isset($smarty.post.bedrijfsnaam)}{$smarty.post.bedrijfsnaam}{/if}">
							</div>
						</div>

						<!-- Telefoonnummer -->
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-3">Telefoonnummer</label>
							<div class="col-sm-7">
								<input name="telefoon" type="text" class="form-control"  value="{if isset($smarty.post.telefoon)}{$smarty.post.telefoon}{/if}">
							</div>
						</div>

						<!-- status -->
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-3">Status</label>
							<div class="col-sm-7">
								<select name="status" class="form-control">
									<option value="1" {if isset($smarty.post.status) && $smarty.post.status == 1} selected{/if}>Nieuw</option>
									<option value="2" {if isset($smarty.post.status) && $smarty.post.status == 2} selected{/if}>Opvolgen</option>
									<option value="3" {if isset($smarty.post.status) && $smarty.post.status == 3} selected{/if}>Geen interesse</option>
								</select>
							</div>
						</div>

						<!-- Reden -->
						<div class="form-group row mb-3 input-reden" {if (isset($smarty.post.status) && $smarty.post.status != 3) || !isset($smarty.post.status)}style="display: none"{/if}>
							<label class="col-form-label col-sm-3">Reden geen interesse</label>
							<div class="col-sm-7">
								<textarea name="reden" class="form-control" {if isset($smarty.post.status) && $smarty.post.status == 3} required{/if}>{if isset($smarty.post.reden)}{$smarty.post.reden}{/if}</textarea>
							</div>
						</div>

						<script>
							{literal}
							$('[name="status"]').on('change',function(){
								if($(this).val() == 3 )
									$('.input-reden').show().find('textarea').prop('required',true);
								else
									$('.input-reden').hide().find('textarea').prop('required',false);
							});
							{/literal}
						</script>
					</div>

					<div class="modal-footer">

						<button type="submit" name="new" class="btn btn-sm btn-success">
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