{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}
{assign "uploader" "true"}

{block "content"}

    {include file='instellingen/werkgever/_sidebar.tpl' active='feestdagen'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Feestdagen toevoegen</h5>
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
								<th>Datum</th>
								<th>Omschrijving</th>
								<th></th>
							</tr>
							<tr>
								<td style="width: 170px" class="pr-2">
									<div class="input-group">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar5"></i></span>
										</span>
										<input name="datum" value="" type="text" class="form-control pickadate"/>
									</div>
								</td>
								<td style="width: 400px;" class="pr-2">
									<input name="omschrijving" value="" type="text" class="form-control"/>
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
			|| Overzicht
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">

				<!-- card  body-->
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Feestdagen overzicht</h5>
				</div>

				<!-- tabs 1 -->
				<div class="nav-tabs-responsive bg-light border-top">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
                        {foreach $feestdagen_list as $jaren}
							<li class="nav-item">
								<a href="#tab{$jaren@key}" class="nav-link {if $jaren@key == $ditjaar}active{/if}" data-toggle="tab">
                                    {$jaren@key}
								</a>
							</li>
                        {/foreach}
					</ul>
				</div>

				<div class="tab-content">
                    {foreach $feestdagen_list as $jaren}
						<div class="tab-pane fade {if $jaren@key == $ditjaar}active{/if} show" id="tab{$jaren@key}">
							<div class="card-body">

								<form method="post" action="">
									<table style="width: 800px" class="table table-sm table-striped">
										<tr>
											<th></th>
											<th>datum</th>
											<th>dag</th>
											<th>omschrijving</th>
											<th>toegevoegd</th>
										</tr>
                                        {foreach $jaren as $dag}
											<tr style="{if $dag.datum < $vandaag}color: #C1C2C3{/if}{if $dag.datum == $vandaag}font-weight:bold{/if}">
												<td style="width: 20px" class="pl-2 pr-1">
                                                    {if $dag.datum >= $vandaag}
													<button type="button" class="sweet-confirm p-0 btn" data-id="{$dag.id}" data-popup="tooltip" data-placement="top" data-title="Feestdag verwijderen">
														<i class="icon-trash text-danger"></i>
													</button>
													{/if}
												</td>
												<td style="width: 120px" class="pr-3">{$dag.datum|date_format: '%d-%m-%Y'}</td>
												<td style="width: 120px" class="pr-3">{$dagnaam[$dag.datum|date_format: '%u']}</td>
												<td>{$dag.omschrijving}</td>
												<td style="width: 190px">{$dag.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
											</tr>
                                        {/foreach}
									</table>
								</form>

							</div>
						</div>
                    {/foreach}
				</div>

			</div><!-- /card body-->
		</div>

	</div>
	<!-- /content area -->
	</div>
	<!-- /main content -->


{/block}