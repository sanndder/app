{extends file='../../../layout.tpl'}
{block "title"}ZZP'er{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}ZZP'er - {$zzp->bedrijfsnaam}{/block}

{block "content"}

    {include file='crm/zzp/dossier/_sidebar.tpl' active='factuurgegevens'}


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


								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Factuurgegevens</legend>

									<!-- iban -->
                                    {if isset($formdata.iban)}
                                        {assign "field" "iban"}
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

									<!-- Ter attentie van -->
                                    {if isset($formdata.tav)}
                                        {assign "field" "tav"}
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

									<!-- Factuur betaaltermijn -->
                                    {if isset($formdata.termijn)}
                                        {assign "field" "termijn"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<select name="{$field}" class="form-control" style="width: 150px;">
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

									<!-- Factuur frequentie -->
                                    {if isset($formdata.frequentie)}
                                        {assign "field" "frequentie"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<input type="hidden" name="{$field}" value="">
                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- g_rekening -->
                                    {if isset($formdata.g_rekening)}
                                        {assign "field" "g_rekening"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- g rekening percentage -->
                                    {if isset($formdata.g_rekening_percentage)}
                                        {assign "field" "g_rekening_percentage"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">
												<select name="{$field}" class="form-control" style="width: 150px">
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


									<!-- Factuur emailen -->
                                    {if isset($formdata.factuur_emailen)}
                                        {assign "field" "factuur_emailen"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!--  factuur_per_medewerker -->
                                    {if isset($formdata.factuur_per_medewerker)}
                                        {assign "field" "factuur_per_medewerker"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!--  afgesproken_werk -->
                                    {if isset($formdata.afgesproken_werk)}
                                        {assign "field" "afgesproken_werk"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

                                                {if isset($formdata.$field.error)}
													<span class="form-text text-danger">{foreach $formdata.$field.error as $e}{$e}
													<br/>
                                                {/foreach}</span>{/if}
											</div>
										</div>
                                    {/if}

									<!-- bijlages_invoegen -->
                                    {if isset($formdata.bijlages_invoegen)}
                                        {assign "field" "bijlages_invoegen"}
										<div class="form-group row">
											<label class="col-lg-{$label_lg} col-form-label {if isset($formdata.$field.error)}text-danger{/if}">{$formdata.$field.label}
												:
											</label>
											<div class="col-xl-{$div_xl} col-md-{$div_md}">

                                                {foreach $formdata.$field.radio.options as $option}
													<div class="form-check {if isset($formdata.$field.radio.inline) && $formdata.$field.radio.inline == true }form-check-inline{/if}">
														<label class="form-check-label">
														<span class="{if $formdata.$field.value == $option@key}checked{/if}">
															<input value="{$option@key}" type="radio" class="form-input-styled" name="{$field}" {if $formdata.$field.value == $option@key}checked=""{/if}>
														</span>
                                                            {$option}
														</label>
													</div>
                                                {/foreach}

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