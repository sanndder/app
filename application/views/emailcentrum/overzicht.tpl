{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-envelope{/block}
{block "header-title"}Emailcentrum{/block}

{block "content"}

    {include file='emailcentrum/_sidebar.tpl'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!---------------------------------------------------------------- zoeken ------------------------------------------------------------->
			<div class="card">
				<div class="card-body"></div>
			</div>

			<!---------------------------------------------------------------- email ------------------------------------------------------------->
			<div class="card">
				<div class="table-responsive" style="overflow-x:visible!important;">
					<table class="table table-inbox">
						<tbody data-link="row" class="rowlink">

							{foreach $emails as $email}
								<tr class="unread">
									<td class="table-inbox-checkbox rowlink-skip">

										<div class="dropdown">
											<a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" class="dropdown-item"><i class="icon-pencil"></i>Bewerken</a>
												<a href="#" class="dropdown-item"><i class="icon-envelope"></i>Nu verzenden</a>
												<a href="#" class="dropdown-item"><i class="icon-trash"></i>Verwijderen</a>
											</div>
										</div>

									</td>
									<td class="table-inbox-star rowlink-skip">
										{if $email.send == 0}<i class="fa fa-hourglass-half text-muted ml-2"></i>{/if}
										{if $email.send == 1}<i class="icon-check text-muted ml-2"></i>{/if}
									</td>
									<td class="table-inbox-image">
                                        {if isset($email.recipients) && count($email.recipients) == 1}
                                            {if $email.recipients[0].uitzender_id !== NULL}
												<span class="btn bg-warning-400 rounded-circle btn-icon btn-sm"><span class="letter-icon">U</span></span>
                                            {/if}
                                            {if $email.recipients[0].inlener_id !== NULL}
		                                        <span class="btn bg-warning-400 rounded-circle btn-icon btn-sm"><span class="letter-icon">I</span></span>
                                            {/if}
                                            {if $email.recipients[0].werknemer_id !== NULL}
		                                        <span class="btn bg-warning-400 rounded-circle btn-icon btn-sm"><span class="letter-icon">W</span></span>
                                            {/if}
										{/if}
									</td>
									<td class="table-inbox-name">
										<a href="emailcentrum/view">
                                            {if isset($email.recipients) && count($email.recipients) == 1}
												<div class="letter-icon-title text-default">{$email.recipients[0].name}</div>
                                            {/if}
										</a>
									</td>
									<td class="table-inbox-name">
                                        {if isset($email.recipients) && count($email.recipients) == 1}
											<div class="letter-icon-title text-default">{$email.recipients[0].email}</div>
                                        {/if}
									</td>
									<td class="table-inbox-message">
										<span class="table-inbox-subject">{$email.subject}</span>
										<span class="text-muted font-weight-normal"></span>
									</td>
									<td class="table-inbox-attachment">
                                        {if isset($email.attachments)}
										<i class="icon-attachment text-muted"></i>
                                        {/if}
									</td>
									<td class="table-inbox-time" style="width: auto">
										{if $folder == ''}{$email.created_on|date_format:'%R'}{/if}
										{if $folder == 'send'}{$email.send_on|date_format:'%d-%m-%Y'}{/if}
									</td>
								</tr>
							{/foreach}

						</tbody>
					</table>
				</div>
			</div>

			<script src="template/global_assets/js/plugins/extensions/rowlink.js"></script>
			<script>
                // Initialize
                $('tbody.rowlink').rowlink({
                    target: '.table-inbox-name > a'
                });
			</script>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}