<div class="container">

	<h2 style="padding-top: 25px; margin-bottom: 0px">{$werknemer.naam}</h2>
	<h2 style="padding-top: 0px; margin-top: 0; font-weight: normal">Urenbriefje {$titel}</h2>

	{if $data != NULL && is_array($data) && count($data) > 0}
		{foreach $data as $inlener}
			<h2 style="color: #002E65; border-bottom: 1px solid #333">{$inlener.inlener}</h2>

            {if isset($inlener.uren) && is_array($inlener.uren) && count($inlener.uren) > 0}
	           <h3 style="margin-bottom: 0">Uren</h3>
	            <table class="urenbriefje">
					{foreach $inlener.uren as $dag}
                        {if isset($dag.rows) && is_array($dag.rows) && count($dag.rows) > 0}
                        {foreach $dag.rows as $row}
						<tr>
							<td style="padding-right: 15px;">{$dag@key|date_format: '%d-%m-%Y'}</td>
							<td style="padding-right: 15px;">{$row.aantal}</td>
							<td style="padding-right: 15px;">{$inlener.urentypes[$row.urentype_id].label}</td>
							<td style="padding-right: 15px;"></td>
						</tr>
						{/foreach}
						{/if}
					{/foreach}
	            </table>
            {/if}

            {if isset($inlener.km) && is_array($inlener.km) && count($inlener.km) > 0}
				<h3 style="margin-bottom: 0">Kilometers</h3>
				<table class="urenbriefje">
                    {foreach $inlener.km as $km}
						<tr>
							<td style="padding-right: 15px;">{$km.datum|date_format: '%d-%m-%Y'}</td>
							<td style="padding-right: 15px;">{$km.aantal} km</td>
						</tr>
                    {/foreach}
				</table>
            {/if}


		{/foreach}
	{/if}



</div>