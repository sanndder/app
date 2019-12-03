	<!-- Top bar -->

	<div class="card">
		<div class="card-body pb-2 pt-2 d-flex justify-content-between">

				{if count($entiteiten) == 1}
					<span class="pt-1">
                        {foreach $entiteiten as $entiteit}
							{$entiteit.schermnaam}
						{/foreach}
					</span>
				{else}
					<ul class="list-inline list-inline-condensed mb-0">
						<li class="list-inline-item">Geselecteerde entiteit: </li>
						<li class="list-inline-item dropdown pl-0">
							<a href="javascript:void(0)" class="btn btn-link text-left text-default dropdown-toggle pl-2 pt-1" data-toggle="dropdown" style="width: 100px;">
	                            {$entiteiten[$smarty.session.entiteit_id].entiteit_id} - {$entiteiten[$smarty.session.entiteit_id].schermnaam}
							</a>
							<div class="dropdown-menu">
	                            {foreach $entiteiten as $entiteit}
	                                {if $entiteit.entiteit_id != $smarty.session.entiteit_id}
										<a href="{$current_url}?entity_id={$entiteit.entiteit_id}{if $qs != ''}&{$qs|replace:$replace:''|replace:'&&':''}{/if}" class="dropdown-item">
	                                        {$entiteit.entiteit_id} - {$entiteit.schermnaam}
										</a>
	                                {/if}
	                            {/foreach}
							</div>
						</li>
					</ul>

                {/if}


			<div>
				<a class="btn btn-light btn-sm" href="javascript:void(0)">
					<i class="icon-plus-circle2"></i>
					Nieuwe entiteit toevoegen
				</a>
			</div>

		</div>
	</div>

	<!-- /Top bar  -->

