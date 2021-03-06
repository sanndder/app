{extends file='../../../layout.tpl'}
{block "title"}ZZP'er{/block}
{block "header-icon"}icon-user{/block}
{assign "select2" "true"}
{assign "datamask" "true"}
{block "header-title"}
    {if $zzp->zzp_id == 0}
		Nieuwe ZZP'er aanmelden
    {else}
		ZZp'er - {$zzp->bedrijfsnaam}
    {/if}

{/block}

{block "content"}

	<script src="template/global_assets/js/plugins/extensions/jquery_ui/widgets.min.js"></script>

    {include file='crm/zzp/dossier/_sidebar.tpl' active='persoonsgegevens'}


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

								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-6 mb-3">
                                        {if $zzp->complete == 1 }
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
								</div><!-- /row -->

                                {*settings*}
                                {assign "label_lg" "3"}
                                {assign "div_xl" "8"}
                                {assign "div_md" "8"}

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Persoonsgegevens</legend>

									<!-- geslacht -->
                                    {if isset($formdata.geslacht)}
                                        {assign "field" "geslacht"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<select name="{$field}" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" style="width: 150px">
                                                    {if !isset($formdata.$field.list.empty)}
														<option value=""></option>
                                                    {/if}
                                                    {if is_array($formdata.$field.list.options)}
                                                        {assign "options" $formdata.$field.list.options}
                                                    {else}
                                                        {assign "options" $list[$formdata.$field.list.options]}
                                                    {/if}
                                                    {foreach $options as $option}
														<option {if $formdata.$field.value == $option@key}selected=""{/if} value="{$option@key}">{$option}</option>
                                                    {/foreach}
												</select>

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- voorletters -->
                                    {if isset($formdata.voorletters)}
                                        {assign "field" "voorletters"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-2 col-md-4">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}

													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- voornaam -->
                                    {if isset($formdata.voornaam)}
                                        {assign "field" "voornaam"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- tussenvoegsel -->
                                    {if isset($formdata.tussenvoegsel)}
                                        {assign "field" "tussenvoegsel"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-2 col-md-4">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}

													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- achternaam -->
                                    {if isset($formdata.achternaam)}
                                        {assign "field" "achternaam"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- achternaam -->
                                    {if isset($formdata.bsn)}
                                        {assign "field" "bsn"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}


									<!-- gb datum -->
                                    {if isset($formdata.gb_datum)}
                                        {assign "field" "gb_datum"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<div class="input-group" style="width: 250px;">
													<input value="{$formdata.$field.value|date_format: '%d-%m-%Y'}" name="{$field}" type="text" placeholder="dd-mm-jjjj" data-mask="99-99-9999" class="form-control{if isset($formdata.$field.error)}border-danger{/if}" autocomplete="off">
												</div>
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- nationaltieit_id -->
                                    {if isset($formdata.nationaltieit_id)}
                                        {assign "field" "nationaltieit_id"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<select name="{$field}" class="form-control select-search{if isset($formdata.$field.error)}-error{/if}" id="div-{$field}">
                                                    {if !isset($formdata.$field.list.empty)}
														<option value="">Selecteer een nationaliteit</option>
                                                    {/if}
                                                    {if is_array($formdata.$field.list.options)}
                                                        {assign "options" $formdata.$field.list.options}
                                                    {else}
                                                        {assign "options" $list[$formdata.$field.list.options]}
                                                    {/if}
                                                    {foreach $options as $option}
														<option {if $formdata.$field.value == $option@key}selected=""{/if} value="{$option@key}">{$option}</option>
                                                    {/foreach}
												</select>

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

								</fieldset>

								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Contactgegevens</legend>

									<!-- telefoon -->
                                    {if isset($formdata.telefoon)}
                                        {assign "field" "telefoon"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- mobiel -->
                                    {if isset($formdata.mobiel)}
                                        {assign "field" "mobiel"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- email -->
                                    {if isset($formdata.email)}
                                        {assign "field" "email"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input value="{$formdata.$field.value}" name="{$field}" type="text" class="form-control {if isset($formdata.$field.error)}border-danger{/if}" placeholder="" autocomplete="off">
                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<br/>

									<!-- woonland -->
                                    {if isset($formdata.woonland_id)}
                                        {assign "field" "woonland_id"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<select name="{$field}" class="form-control select-search">
                                                    {if !isset($formdata.$field.list.empty)}
														<option value="">Selecteer een nationaliteit</option>
                                                    {/if}
                                                    {if is_array($formdata.$field.list.options)}
                                                        {assign "options" $formdata.$field.list.options}
                                                    {else}
                                                        {assign "options" $list[$formdata.$field.list.options]}
                                                    {/if}
                                                    {foreach $options as $option}
														<option {if $formdata.$field.value == $option@key}selected=""{/if} value="{$option@key}">{$option}</option>
                                                    {/foreach}
												</select>

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

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
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
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
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
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
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
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
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
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
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}

													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}
								</fieldset>

								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" class="btn btn-success btn-sm">
											<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
										</button>
									</div><!-- /col -->
								</div><!-- /row -->

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}