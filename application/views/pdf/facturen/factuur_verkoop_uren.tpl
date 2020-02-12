<table style="margin-left: 15px; width: 100%">
	<tr>
		<td style="font-size: 40px; color:#002E65">
			FACTUUR
		</td>
		<td rowspan="2" style="text-align: right; padding-right: 25px; padding-top: 6px;">

			<table style="font-size: 13px;">
				<tr>
					<td class="relatie">bedrijfsnaam</td>
					<td class="relatie-info">
                        {$relatie_gegevens.bedrijfsnaam|default:''}
					</td>
				</tr>
				<tr>
					<td class="relatie">adres</td>
					<td class="relatie-info">
                        {$relatie_gegevens.straat}  {$relatie_gegevens.huisnummer}
					</td>
				</tr>
				<tr>
					<td class="relatie">plaats</td>
					<td class="relatie-info">
                        {$relatie_gegevens.postcode}  {$relatie_gegevens.plaats}
					</td>
				</tr>
				<tr>
					<td class="relatie">btw nr</td>
					<td class="relatie-info">
                        {$relatie_gegevens.btwnr}
					</td>
				</tr>
				<tr>
					<td class="relatie">factuurdatum</td>
					<td class="relatie-info">
                        {$factuurdatum|date_format: '%d-%m-%Y'}
					</td>
				</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td>
			<table style="margin-top: -35px; color:#002E65; font-size: 16px; font-weight: bold; font-style: italic">
				<tr>
					<td>Week {$periode} - {$jaar}</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br/>

<table class="regels">
	<thead>
		<tr>
			<th class="text-left">omschrijving</th>
			<th class="text-right" style="width: 80px">uren</th>
			<th class="text-right" style="width: 80px">aantal</th>
			<th class="text-right" style="width: 80px">tarief</th>
			<th class="text-right" style="width: 90px">factor</th>
			<th class="text-right" style="width: 90px">percentage</th>
			<th class="text-right" style="width: 110px">bedrag</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="5"></td>
			<td></td>
			<td></td>
		</tr>
        {* werknemers en uren *}
        {if isset($regels) && is_array($regels) && count($regels) > 0}
            {foreach $regels as $r}

                {* start regel met naam *}
                {if $r.row_start != NULL}
					<tr>
						<td style="padding-top: 5px;">
							<b>{$r.omschrijving}</b>
						</td>
					</tr>
                {/if}

                {* regels met invoer *}
                {if $r.row_start == NULL && $r.row_end == NULL}
					<tr>
						<td style="padding-left: 18px">{$r.omschrijving}</td>
						<td class="text-right">{$r.uren_aantal}</td>
						<td class="text-right">{$r.uren_decimaal|number_format:2:',':'.'}</td>
						<td class="text-right">
							{if $type == 'verkoop'}&euro; {$r.verkooptarief|number_format:2:',':'.'} {/if}
							{if $type == 'ksoten'}&euro; {$r.bruto_uurloon|number_format:2:',':'.'} {/if}
						</td>
						<td class="text-right">
                            {if $type == 'verkoop'}&euro; {$r.verkooptarief|number_format:2:',':'.'} {/if}
                            {if $type == 'ksoten'}&euro; {$r.bruto_uurloon|number_format:2:',':'.'} {/if}
						</td>
						<td class="text-right">
                           &euro; {$r.factor|number_format:3:',':'.'}
						</td>
						<td class="text-right">
                            {if $type == 'verkoop'}&euro; {$r.subtotaal_verkoop|number_format:2:',':'.'} {/if}
                            {if $type == 'ksoten'}&euro; {$r.subtotaal_kosten|number_format:2:',':'.'} {/if}
						</td>
					</tr>
                {/if}

                {* regel met subtotaal *}
                {if $r.row_end != NULL}
					<tr class="tr-end">
			           	<td colspan="5"></td>
						<td class="bold text-right">subtotaal</td>
						<td class="bold text-right">
							{if $type == 'verkoop'}&euro; {$r.subtotaal_verkoop|number_format:2:',':'.'}{/if}
							{if $type == 'kosten'}&euro; {$r.subtotaal_kosten|number_format:2:',':'.'}{/if}
						</td>
					</tr>
                {/if}

            {/foreach}
        {/if}
	</tbody>
</table>