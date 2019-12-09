{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}

{block "content"}

    {include file='instellingen/werkgever/_sidebar.tpl' active='vergoedingen'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Vergoeding toevoegen</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

                        {if isset($errors)}
							<div class="row">
								<div class="col-md-12">
									<div class="alert alert-warning alert-styled-left alert-arrow-left alert-dismissible" role="alert">
                                        {foreach $errors as $arr}
                                            {foreach $arr as $e}
                                                {$e}
                                            {/foreach}
                                        {/foreach}
									</div><!-- /col -->
								</div><!-- /col -->
							</div>
							<!-- /row -->
                        {/if}


						<table>
							<tr>
								<th>Vergoeding</th>
								<th>Belast/Onbelast</th>
								<th></th>
							</tr>
							<tr>
								<td style="width: 400px;" class="pr-2">
									<input type="text" class="form-control" name="naam" required>
								</td>
								<td style="width:150px;" class="pr-2">
									<select name="belast" class="form-control" required>
										<option value=""></option>
										<option value="1">Belast</option>
										<option value="0">Onbelast</option>
									</select>
								</td>
								<td>
									<button type="submit" name="set" class="btn btn-success">
										<i class="icon-add mr-1"></i>Toevoegen
									</button>
								</td>
							</tr>
						</table>

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| msg
			-------------------------------------------------------------------------------------------------------------------------------------------------->
            {if isset($msg)}
				<div class="row">
					<div class="col-md-12">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Vergoedingen overzicht</h5>
				</div>

				<div class="card-body">

                    {if !empty($vergoedingen)}
						<form method="post" action="">
							<table class="table table-striped" style="width: 600px;">
								<thead>
									<tr>
										<th></th>
										<th>Vergoeding</th>
										<th>Belast/Onbelast</th>
									</tr>
								</thead>
								<tbody>
                                    {foreach $vergoedingen as $v}
										<tr>
											<th style="width: 20px;">
												<button type="button" class="sweet-confirm p-0 btn" data-id="{$v.vergoeding_id}" data-popup="tooltip" data-placement="top" data-title="Vergoeding verwijderen">
													<i class="icon-trash text-danger"></i>
												</button>
											</th>
											<td>{$v.naam}</td>
											<td>
                                                {if $v.belast == 1}
													Belast
                                                {else}
													Onbelast
                                                {/if}
											</td>
										</tr>
                                    {/foreach}
								</tbody>
							</table>
						</form>
                    {/if}

				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}