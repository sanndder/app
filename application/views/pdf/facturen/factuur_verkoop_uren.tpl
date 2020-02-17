<table style="margin-left: 15px; width: 100%">
	<tr>
		<td style="font-size: 40px; color:#002E65">
			{if !isset($type) || $type == 'verkoop'} FACTUUR {/if}
			{if $type == 'kosten'} KOSTENOVERZICHT {/if}
		</td>
		<td rowspan="2" style="text-align: right; padding-right: 25px; padding-top: 6px;">

            {if $type == 'verkoop'}
			<table style="font-size: 12px;">
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
                        {if isset($factuurdatum)}
							{$factuurdatum|date_format: '%d-%m-%Y'}
						{/if}
					</td>
				</tr>
				<tr>
					<td class="relatie">vervaldatum</td>
					<td class="relatie-info">
						{if isset($vervaldatum)}
                            {$vervaldatum|date_format: '%d-%m-%Y'}
                        {/if}
					</td>
				</tr>
			</table>
            {/if}

		</td>
	</tr>
	<tr>
		<td>
			<table style="{if $type == 'kosten'}margin-top:0px;{else}margin-top: -35px;{/if} color:#002E65; font-size: 16px; font-weight: bold; font-style: italic">
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
	                {if ($type == 'verkoop' && $r.doorbelasten_aan != 'uitzender') || $type == 'kosten'}
					<tr>
						<td style="padding-left: 18px">{$r.omschrijving}</td>
						<td class="text-right">
                            {if $r.uren_aantal != NULL}{$r.uren_aantal}{/if}
						</td>
						<td class="text-right">
							{if $r.uren_decimaal != NULL}{$r.uren_decimaal|number_format:2:',':'.'}{/if}
						</td>
						<td class="text-right">
							{if $type == 'verkoop'}&euro; {$r.verkooptarief|number_format:2:',':'.'} {/if}
							{if $type == 'kosten'}&euro; {$r.bruto_uurloon|number_format:2:',':'.'} {/if}
						</td>
						<td class="text-right">
							{* uren bij verkoop altijd factor 1 *}
                            {if $r.uren_aantal != NULL}
                                {if $type == 'verkoop'}1,000{else}{$r.factor|number_format:3:',':'.'}{/if}
	                        {else}
                                {$r.factor|number_format:3:',':'.'}
	                        {/if}
						</td>
						<td class="text-right">
                            {if $type == 'verkoop'}100%{/if}
                            {if $type == 'kosten'}{$r.percentage|number_format:2:',':'.'}%{/if}
						</td>
						<td class="text-right">
                            {if $type == 'verkoop'}&euro; {$r.subtotaal_verkoop|number_format:2:',':'.'} {/if}
                            {if $type == 'kosten'}&euro; {$r.subtotaal_kosten|number_format:2:',':'.'} {/if}
						</td>
					</tr>
                    {/if}
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

        {* factuur totalen *}
		<tr>
			<td colspan="7" style="height: 25px;"></td>
		</tr>
		<tr class="totaal">
			<td colspan="6" class="text-right">totaal excl. BTW</td>
			<td class="text-right">&euro;
                {if $type == 'verkoop'}{$factuur.bedrag_excl|number_format:2:',':'.'}{/if}
                {if $type == 'kosten'}{$factuur.kosten_excl|number_format:2:',':'.'}{/if}
			</td>
		</tr>
		<tr class="totaal">
			<td colspan="6" class="text-right">BTW</td>
			<td class="text-right">
                {if $factuur.bedrag_btw == NULL}
					BTW verlegd
                {else}
					&euro;
					{if $type == 'verkoop'}{$factuur.bedrag_btw|number_format:2:',':'.'}{/if}
					{if $type == 'kosten'}{$factuur.kosten_btw|number_format:2:',':'.'}{/if}
                {/if}

			</td>
		</tr>
		<tr class="totaal">
			<td colspan="6" class="text-right">totaal</td>
			<td class="text-right">
				&euro;
                {if $type == 'verkoop'}{$factuur.bedrag_incl|number_format:2:',':'.'}{/if}
                {if $type == 'kosten'}{$factuur.kosten_incl|number_format:2:',':'.'}{/if}
			</td>
		</tr>

	</tbody>
</table>

{if $type == 'verkoop'}
	{if isset($grekening_bedrag) && $grekening_bedrag != NULL && $grekening_bedrag != '' && $grekening_bedrag != 0}
		<div class="grekening">
	        Er mag {$grekening_percentage|number_format:0:',':'.'}% (&euro; {$grekening_bedrag|number_format:2:',':'.'}) naar de G-rekening op IBAN {$iban_factoring}
		</div>
	{/if}

	{if isset($cessie_tekst) && $cessie_tekst != NULL && $cessie_tekst != ''}
		<div class="cessie_tekst">
			{$cessie_tekst}
		</div>
	{/if}
	{/if}