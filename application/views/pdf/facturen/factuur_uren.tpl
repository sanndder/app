
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
						{if $relatie_type == 'inlener'}
							{$inlener_bedrijfsgegevens.bedrijfsnaam}
						{/if}
					</td>
				</tr>
				<tr>
					<td class="relatie">adres</td>
					<td class="relatie-info">
						{if $relatie_type == 'inlener'}
                            {$inlener_bedrijfsgegevens.straat}  {$inlener_bedrijfsgegevens.huisnummer}
                        {/if}
					</td>
				</tr>
				<tr>
					<td class="relatie">plaats</td>
					<td class="relatie-info">
                        {if $relatie_type == 'inlener'}
                            {$inlener_bedrijfsgegevens.postcode}  {$inlener_bedrijfsgegevens.plaats}
                        {/if}
					</td>
				</tr>
				<tr>
					<td class="relatie">btw nr</td>
					<td class="relatie-info">
                        {if $relatie_type == 'inlener'}
                            {$inlener_bedrijfsgegevens.btwnr}
                        {/if}
					</td>
				</tr>
				<tr>
					<td class="relatie">factuurdatum</td></td>
					<td class="relatie-info">
						{$factuurdatum|date_format: '%d-%m-%Y'}
					</td>
				</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td>
			<table style="margin-top: -55px; color:#002E65; font-size: 16px; font-weight: bold; font-style: italic"><tr><td>Week {$periode} - {$jaar}</td></tr></table>
		</td>
	</tr>
</table>

<br />

<table class="regels">
	<thead>
		<tr>
			<th class="text-left">omschrijving</th>
			<th class="text-right">uren</th>
			<th class="text-right">aantal</th>
			<th class="text-right">tarief</th>
			<th class="text-right">factor</th>
			<th class="text-right">bedrag</th>
		</tr>
	</thead>
	<tbody>
        {assign "totaal" 0}
		{foreach $array as $werknemer}
			{if isset($werknemer.uren_totaal) || (isset($werknemer.km) && is_array($werknemer.km) && count($werknemer.km) > 0) || (isset($werknemer.vergoedingen) && is_array($werknemer.vergoedingen) && count($werknemer.vergoedingen) > 0)  }
				{assign "werknemer_totaal" 0}
				<tr>
					<td>{$werknemer.werknemer.naam}</td>
					<td colspan="5"></td>
				</tr>
				{if isset($werknemer.uren_totaal)}
					{foreach $werknemer.uren_totaal as $urenrow}
						{if isset($urenrow.naam)}
						<tr>
							<td>{$urenrow.naam}</td>
							<td class="text-right">{$urenrow.aantal}</td>
							<td class="text-right">{$urenrow.aantal|number_format:2:',':'.'}</td>
							<td class="text-right">&euro; {$urenrow.verkooptarief|number_format:2:',':'.'}</td>
							<td class="text-right">1,000</td>
							<td class="text-right">&euro; {($urenrow.aantal * $urenrow.verkooptarief)|number_format:2:',':'.'}</td>
						</tr>
                        {/if}
						{$werknemer_totaal = $werknemer_totaal + ($urenrow.aantal * $urenrow.verkooptarief)}
                    {/foreach}
                {/if}
                {if count($werknemer.vergoedingen) > 0}
                    {foreach $werknemer.vergoedingen as $vergoeding}
						<tr>
							<td>{$vergoeding.naam}</td>
							<td class="text-right"></td>
							<td class="text-right"></td>
							<td class="text-right">&euro; {$vergoeding.bedrag|number_format:2:',':'.'}</td>
							<td class="text-right">{if $vergoeding.belast == 1}1,550{else}1,000{/if}</td>
							<td class="text-right">&euro; {$vergoeding.bedrag|number_format:2:',':'.'}</td>
						</tr>
                        {$werknemer_totaal = $werknemer_totaal + ($vergoeding.bedrag)}
                    {/foreach}
                {/if}
                {if is_array($werknemer.km) > 0}
                    {foreach $werknemer.km as $km}
						<tr>
							<td>kilometers</td>
							<td class="text-right"></td>
							<td class="text-right">{$km.aantal}</td>
							<td class="text-right">&euro; 0,19</td>
							<td class="text-right">1,000</td>
							<td class="text-right">&euro; {($km.aantal*0.19)|number_format:2:',':'.'}</td>
						</tr>
                        {$werknemer_totaal = $werknemer_totaal + ($km.aantal*0.19)}
                    {/foreach}
                {/if}

				{if $werknemer_totaal > 0}
					<tr class="totaal">
						<td colspan="5" class="text-right">subtotaal</td>
						<td class="text-right">&euro; {$werknemer_totaal|number_format:2:',':'.'}</td>
					</tr>
                    {$totaal = $totaal + $werknemer_totaal}
				{/if}
				<tr>
					<td colspan="6" style="height: 25px;"></td>
				</tr>
				<tr class="totaal">
					<td colspan="5" class="text-right">totaal excl. BTW</td>
					<td class="text-right">&euro; {$totaal|number_format:2:',':'.'}</td>
				</tr>
				<tr class="totaal">
					<td colspan="5" class="text-right">BTW</td>
					<td class="text-right">&euro; {($totaal*0.21)|number_format:2:',':'.'}</td>
				</tr>
				<tr class="totaal">
					<td colspan="5" class="text-right">totaal</td>
					<td class="text-right">&euro; {($totaal*0.21 + $totaal)|number_format:2:',':'.'}</td>
				</tr>
			{/if}
        {/foreach}
	</tbody>
</table>