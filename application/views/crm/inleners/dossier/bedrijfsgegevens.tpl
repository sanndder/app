{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{assign "select2" "true"}
{block "header-title"}
    {if $inlener->inlener_id == 0}
		Nieuwe inlener aanmelden
    {else}
		Inlener - {$inlener->bedrijfsnaam}
    {/if}
{/block}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='bedrijfsgegevens'}
    {include file='_modals/geschiedenis.tpl'}

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
				<div class="col-xl-10">

					<!-- Basic card -->
					<div class="card">
						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">

                                {*settings*}
                                {assign "label_lg" "3"}
                                {assign "div_xl" "8"}
                                {assign "div_md" "8"}

								<!-- opslaan -->
                                {if $user_type == 'werkgever'}
									<div class="row">
										<div class="col-lg-6 mb-3">
                                            {* bovenste knop alleen wanneer inlener compleet *}
                                            {if $inlener->complete == 1 }
												<button type="submit" name="set" class="btn btn-success btn-sm">
													<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
												</button>
                                            {/if}
										</div><!-- /col -->
                                        {if $ENV == 'development' || $user_id == 2}
											<div class="col-lg-6 text-right mb-3">
											<span data-title="Formulier invullen" data-popup="tooltip" data-placement="top" style="cursor:pointer;" onclick="fillForm()">
												<i class="icon-pencil3 mr-2" style="font-size: 22px"></i>
											</span>
											</div>
                                        {/if}
									</div>
									<!-- /row -->

									<!------ opslaan --------->
									<div class="row">
                                        {* geschiedenis alleen wanneer inlener compleet *}
                                        {if $inlener->complete == 1}
											<div class="col-lg-6 text-right mb-3">
												<span data-title="Wijzigingen weergeven" data-popup="tooltip" data-placement="top" style="cursor:pointer;" onclick="showHistory('inlener_bedrijfsgegevens', 'inlener_id', {$inlener->inlener_id} )">
													<i class="icon-history mr-2" style="font-size: 22px"></i>
												</span>
											</div>
                                        {/if}
									</div>
									<!-- /row -->
                                {/if}

                                {* bij nieuwe inlener ook uitzender kiezen, alleen als werkgever *}
                                {if $inlener->complete != 1 && $user_type == 'werkgever'}
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold">Uitzender</legend>

										<div class="form-group row">
                                            {assign "field" "uitzender_id"}
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">Uitzender:</label>
											<div class="col-form-label col-xl-{$div_xl} col-md-{$div_md} pt-0">

                                                {* vanuit uizender geen dropdownlijst *}
                                                {if isset($smarty.get.uitzender_id) && isset($uitzenders[$smarty.get.uitzender_id])}
													<input type="hidden" name="uitzender_id" value="{$smarty.get.uitzender_id}"/>
													<div class="pt-2 font-weight-bold">
                                                        {$uitzenders[$smarty.get.uitzender_id]}
													</div>
                                                {else}
													<select name="uitzender_id" class="form-control select-search {if isset($formdata.$field.error)}border-danger{/if}">
														<option value="0">Geen uitzender (payrollklant)</option>
                                                        {if $uitzenders !== NULL}
                                                            {foreach $uitzenders as $u}
																<option {if $formdata.uitzender_id.value == $u@key} selected{/if} value="{$u@key}">{$u@key} - {$u}</option>
                                                            {/foreach}
                                                        {/if}
													</select>
                                                    {if isset($formdata.$field.error)}
														<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}


																<br/>
                                                            {/foreach}</span>
                                                    {/if}
                                                {/if}

											</div>
										</div>

									</fieldset>
                                {/if}

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bedrijfsgegevens</legend>

									<div class="row">
										<div class="alert alert-warning alert-styled-left alert-dismissible col-md-12" style="display: none">
											<span class="font-weight-semibold"></span>
										</div>
									</div>

									<!-- kvknr -->
                                    {if isset($formdata.kvknr)}
                                        {assign "field" "kvknr"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="input-kvk form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- bedrijfsnaam -->
                                    {if isset($formdata.bedrijfsnaam)}
                                        {assign "field" "bedrijfsnaam"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- btwnr -->
                                    {if isset($formdata.btwnr)}
                                        {assign "field" "btwnr"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- btwnr -->
                                    {if isset($formdata.telefoon)}
                                        {assign "field" "telefoon"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}
								</fieldset>

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Bezoekadres</legend>

									<!-- straat -->
                                    {if isset($formdata.straat)}
                                        {assign "field" "straat"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- huisnummer -->
                                    {if isset($formdata.huisnummer)}
                                        {assign "field" "huisnummer"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- huisnummer_toevoeging -->
                                    {if isset($formdata.huisnummer_toevoeging)}
                                        {assign "field" "huisnummer_toevoeging"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- postcode -->
                                    {if isset($formdata.postcode)}
                                        {assign "field" "postcode"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input style="width: 100px;" value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- plaats -->
                                    {if isset($formdata.plaats)}
                                        {assign "field" "plaats"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}
								</fieldset>


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Postbus</legend>

									<!-- postbus_nummer -->
                                    {if isset($formdata.postbus_nummer)}
                                        {assign "field" "postbus_nummer"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- postbus_postcode -->
                                    {if isset($formdata.postbus_postcode)}
                                        {assign "field" "postbus_postcode"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}

									<!-- postbus_plaats -->
                                    {if isset($formdata.postbus_plaats)}
                                        {assign "field" "postbus_plaats"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">
                                                    {foreach $formdata.$field.error as $e}{$e}<br/>{/foreach}
													</span>
                                                {/if}
											</div>
										</div>
                                    {/if}
								</fieldset>

								<!-- opslaan -->
                                {if $user_type == 'werkgever'}
									<div class="row">
										<div class="col-lg-12 mb-3">
											<button type="submit" name="set" class="btn btn-success btn-sm">
												<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
											</button>
										</div><!-- /col -->
									</div>
									<!-- /row -->
                                {/if}

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}