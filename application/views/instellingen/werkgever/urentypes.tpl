{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}

{block "content"}

    {include file='instellingen/werkgever/_sidebar.tpl' active='urentypes'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Urentype toevoegen</h5>
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
								<th>Type</th>
								<th>Percentage</th>
								<th>Naam</th>
								<th></th>
							</tr>
							<tr>
								<td style="width: 200px;" class="pr-2">
									<select name="urentype_categorie_id" class="form-control make-naam" required>
										<option value=""></option>
                                        {foreach $urentypes_categorien as $c}
											<option value="{$c.urentype_categorie_id}">{$c.label}</option>
                                        {/foreach}
									</select>
								</td>
								<td style="width:100px;" class="pr-2">
									<input name="percentage" value="" type="text" class="form-control text-right make-naam" autocomplete="off" required/>
								</td>
								{*
								<td style="width:100px;" class="pr-2">
									<input name="factor" value="" type="text" class="form-control text-right make-naam" autocomplete="off" required/>
								</td>*}
								<td style="width:400px;" class="pr-2">
									<input name="naam" value="" type="text" class="form-control urentype-naam" autocomplete="off" required/>
								</td>
								<td>
									<button type="submit" name="set" class="btn btn-success">
										<i class="icon-add mr-1"></i>Toevoegen
									</button>
								</td>
							</tr>
						</table>

						<!-------------- javascript for naam ------------------>
                        {literal}
							<script>
                                $( '.make-naam' ).on( 'change keyup', function() {
                                    $( '[name="naam"]' ).val( $( '[name="urentype_categorie_id"] option:selected' ).text() + ' ' + $( '[name="percentage"]' ).val() + '%' );

                                } );
							</script>
                        {/literal}

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
					<h5 class="card-title">Urentypes overzicht</h5>
				</div>

				<div class="card-body">

                    {if !empty($urentypes_array)}
	                    <form method="post" action="">
						<table class="table">
							<thead>
								<tr>
                                    {foreach $urentypes_array as $header}
										<th class="pl-0">{$header@key}</th>
										<th style="border: 0"></th>
                                    {/foreach}
								</tr>
							</thead>
							<tbody>

								<tr>
                                    {foreach $urentypes_array as $array}
										<td class="p-0" style="vertical-align: text-top">

											<table class="table table-striped">
												<tr>
													<th style="width: 20px;"></th>
													<th class="pl-1">Naam</th>
													<th class="text-right" style="width: 110px">Percentage</th>
													{*<th class="text-right" style="width: 110px">Factor</th>*}
												</tr>
                                                {foreach $array as $u}
													<tr>
														<td class="pl-2 pr-0">
															{if $u.urentype_id != 1}
															<button type="button" class="sweet-confirm p-0 btn" data-id="{$u.urentype_id}" data-popup="tooltip" data-placement="top" data-title="Urentype verwijderen">
																<i class="icon-trash text-danger"></i>
															</button>
                                                            {/if}
														</td>
														<td class="pl-1">{$u.naam}</td>
														<td class="text-right">{$u.percentage}</td>
                                                        {*<td class="text-right">{$u.factor}</td>*}
													</tr>
                                                {/foreach}
											</table>

										</td>
										<td style="border: 0"></td>
                                    {/foreach}
								</tr>

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