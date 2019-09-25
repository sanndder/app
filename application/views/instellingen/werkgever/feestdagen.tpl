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
			|| Bedrijfsgegevens
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Feestdagen aanpassen</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

                        {if isset($msg)}
							<div class="row">
								<div class="col-md-12">
                                    {$msg}
								</div><!-- /col -->
							</div>
							<!-- /row -->
                        {/if}


						<table>
							<tr>
								<th>Datum</th>
								<th>Omschrijving</th>
							</tr>
							<tr>
								<td>
									<input name="datum" value="" type="text" class="form-control" />
								</td>
							</tr>
						</table>



						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success">
									<i class="icon-add mr-1"></i>Toevoegen
								</button>
							</div><!-- /col -->
						</div><!-- /row -->


					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}