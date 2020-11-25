<div class="container">

	<br/>
	<br/>

	<table class="margeweekoverzicht">
		<thead>
			<tr>
				<th class="text-left bt2">Inlener</th>
				<th colspan="3" class="bl br bt2">Uren</th>
				<th colspan="3" class="bl br bt2">Bedrag verkoopfactuur</th>
				<th colspan="3" class="bl br bt2">Bedrag kostenoverzicht</th>
				<th colspan="2" class="bl bt2">Marge</th>
			</tr>
			<tr>
				<th style="padding-left: 20px" class="text-left bb2">Werknemer</th>
				<th class="text-right bl bb2">Inlener</th>
				<th class="text-right bb2">Uitzender</th>
				<th class="text-right br bb2">Totaal</th>
				<th class="text-right bl bb2">Uren (€)</th>
				<th class="text-right bb2">Vergoeding (€)</th>
				<th class="text-right br bb2">Totaal (€)</th>
				<th class="text-right bl bb2">Uren (€)</th>
				<th class="text-right bb2">Vergoeding (€)</th>
				<th class="text-right br bb2">Totaal (€)</th>
				<th class="text-right bl bb2">Bedrag (€)</th>
				<th class="text-right bb2">Percentage</th>
			</tr>
		</thead>
		<tbody>
			{foreach $data as $inlener}
				{if is_numeric($inlener@key)}
				<tr class="tr-header">
					<td colspan="12" class="">{$inlener.inlener}</td>
				</tr>
					{foreach $inlener.werknemers as $werknemer}
                        {if is_numeric($werknemer@key)}
	                        <tr>
		                        <td style="padding-left: 20px">{$werknemer.naam}</td>
		                        <td class="text-right bl">{$werknemer.uren_aantal_verkoop}</td>
		                        <td class="text-right">{$werknemer.uren_aantal_verkoop - $werknemer.uren_aantal_kosten}</td>
		                        <td class="text-right br">{$werknemer.uren_aantal_verkoop + ($werknemer.uren_aantal_verkoop - $werknemer.uren_aantal_kosten)}</td>
		                        <td class="text-right">{$werknemer.uren_bedrag_verkoop|number_format:2:',':'.'}</td>
		                        <td class="text-right">{$werknemer.vergoedingen_bedrag_verkoop|number_format:2:',':'.'}</td>
		                        <td class="text-right br">{$werknemer.totaal_bedrag_verkoop|number_format:2:',':'.'}</td>
		                        <td class="text-right">{$werknemer.uren_bedrag_kosten|number_format:2:',':'.'}</td>
		                        <td class="text-right">{$werknemer.vergoedingen_bedrag_kosten|number_format:2:',':'.'}</td>
		                        <td class="text-right br">{$werknemer.totaal_bedrag_kosten|number_format:2:',':'.'}</td>
		                        <td class="text-right bl">{$werknemer.totaal_bedrag_marge|number_format:2:',':'.'}</td>
		                        <td class="text-right">{$werknemer.percentage_marge|number_format:2:',':'.'} %</td>
	                        </tr>
                        {/if}
						{if $werknemer@last}
							<tr>
								<td class="text-right bold bt2 bold" style="padding-right: 20px">Totaal</td>
								<td class="text-right bl bt2 bold">{$inlener.werknemers.totaal.uren_aantal_verkoop}</td>
								<td class="text-right bt2 bold">{$inlener.werknemers.totaal.uren_aantal_verkoop - $inlener.werknemers.totaal.uren_aantal_kosten}</td>
								<td class="text-right br bt2 bold">{$inlener.werknemers.totaal.uren_aantal_verkoop + ($inlener.werknemers.totaal.uren_aantal_verkoop - $inlener.werknemers.totaal.uren_aantal_kosten)}</td>
								<td class="text-right bt2 bold">{$inlener.werknemers.totaal.uren_bedrag_verkoop|number_format:2:',':'.'}</td>
								<td class="text-right bt2 bold ">{$inlener.werknemers.totaal.vergoedingen_bedrag_verkoop|number_format:2:',':'.'}</td>
								<td class="text-right br bt2 bold">{$inlener.werknemers.totaal.totaal_bedrag_verkoop|number_format:2:',':'.'}</td>
								<td class="text-right bt2 bold">{$inlener.werknemers.totaal.uren_bedrag_kosten|number_format:2:',':'.'}</td>
								<td class="text-right bt2 bold ">{$inlener.werknemers.totaal.vergoedingen_bedrag_kosten|number_format:2:',':'.'}</td>
								<td class="text-right bt2 br bold">{$inlener.werknemers.totaal.totaal_bedrag_kosten|number_format:2:',':'.'}</td>
								<td class="text-right bl bt2 bold">{$inlener.werknemers.totaal.totaal_bedrag_marge|number_format:2:',':'.'}</td>
								<td class="text-right bt2 bold">{$inlener.werknemers.totaal.percentage_marge|number_format:2:',':'.'} %</td>
							</tr>
							<tr>
								<td colspan="12" style="height: 25px"></td>
							</tr>
						{/if}
					{/foreach}
                {/if}
			{/foreach}
		</tbody>
	</table>


</div>