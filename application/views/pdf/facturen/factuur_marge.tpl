<table style="margin-left: 15px; width: 100%">
	<tr>
		<td style="text-align: left; padding-right: 25px; padding-top: 9px;">

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
			</table>

		</td>

		<td style="text-align: right; padding-right: 25px; padding-top: 9px;">

			<table style="font-size: 12px;">
				<tr>
					<td class="relatie">relatienummer</td>
					<td class="relatie-info">
                        {if isset($relatie_gegevens.uitzender_id)}{$relatie_gegevens.uitzender_id}{/if}
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
			</table>

		</td>
	</tr>
</table>

<br/>

{if $werkgever_type == 'uitzenden'}
	<div class="balk" style="width: 100%; height: 5px; background-color: #2DA4DC"></div>
{/if}
{if $werkgever_type == 'bemiddeling'}
	<div class="balk" style="width: 100%; height: 5px; background-color: #22AF8F"></div>
{/if}


<br/>
<br/>
<br/>

<table class="regels">
	<thead>
		<tr>
			<th class="text-left">omschrijving</th>
			<th class="text-right" style="width: 110px">bedrag</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td></td>
		</tr>
        {* werknemers en uren *}
        {if isset($regels) && is_array($regels) && count($regels) > 0}
            {foreach $regels as $r}
	            <tr>
					<td>{$r.omschrijving}</td>
					<td class="text-right">&euro; {$r.subtotaal_verkoop}</td>
	            </tr>
            {/foreach}
        {/if}

        {* factuur totalen *}
		<tr>
			<td colspan="2" style="height: 25px;"></td>
		</tr>
		<tr class="totaal">
			<td class="text-right">totaal excl. BTW</td>
			<td class="text-right">
				&euro; {$factuur.bedrag_excl|number_format:2:',':'.'}
			</td>
		</tr>
		<tr class="totaal">
			<td class="text-right">BTW</td>
			<td class="text-right">&euro; {$factuur.bedrag_btw|number_format:2:',':'.'}</td>
		</tr>
		<tr class="totaal">
			<td class="text-right">totaal</td>
			<td class="text-right">&euro; {$factuur.bedrag_incl|number_format:2:',':'.'}</td>
		</tr>

	</tbody>
</table>
