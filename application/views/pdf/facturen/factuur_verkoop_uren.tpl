<table style="margin-left: 15px; width: 100%">
	<tr>
		<td style="font-size: 40px; color:#002E65">
            {if !isset($type) || $type == 'verkoop' || $type == 'zzp'} FACTUUR {/if}
            {if $type == 'kosten'} KOSTENOVERZICHT {/if}
		</td>
		<td rowspan="2" style="text-align: right; padding-right: 25px; padding-top: 6px;">

            {if $type == 'verkoop' || $type == 'zzp'}
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
			<table style="{if $type == 'kosten' }margin-top:0px;{else}margin-top: -35px;{/if} color:#002E65; font-size: 16px; font-weight: bold; font-style: italic">
				<tr>
					<td>
                        {if $factuur.tijdvak == 'w'}Week {$periode} - {$jaar}{/if}
                        {if $factuur.tijdvak == '4w'}Periode {$periode} - {$jaar}{/if}
					</td>
				</tr>
                {if $type == 'kosten'}
					<tr>
						<td> {$relatie_gegevens.bedrijfsnaam|default:''} ({$relatie_gegevens.inlener_id|default:''})</td>
					</tr>
                {/if}
                {if isset($factuur.project) && $factuur.project !== NULL}
					<tr>
						<td>{$factuur.project}</td>
					</tr>
                {/if}
			</table>
		</td>
	</tr>
</table>

<br/>

<table class="regels">
	<thead>
		<tr>
			<th class="text-left">omschrijving</th>
            {if $aangenomenwerk == 0}
				<th class="text-right" style="width: 80px">uren</th>
				<th class="text-right" style="width: 80px">aantal</th>
				<th class="text-right" style="width: 80px">tarief</th>
				<th class="text-right" style="width: 90px">factor</th>
				<th class="text-right" style="width: 90px">percentage</th>
            {else}
				<th colspan="5"></th>
            {/if}
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
                {* bij afgesprokenwerk geen werknemers *}
                {if ($r.row_afgesprokenwerk == 1 && $aangenomenwerk == 1) || ($r.row_afgesprokenwerk == 0 && $aangenomenwerk == 0) }

                    {* correcties alleen op verkoop *}
                    {if  $r.row_correctie == 0 || ($type == 'verkoop' && $r.row_correctie == 1)}

                        {* start regel met naam *}
                        {if $r.row_start != NULL && $type != 'zzp'}
							<tr>
								<td style="padding-top: 5px;">
									<b>{$r.omschrijving}</b>
								</td>
							</tr>
                        {/if}

                        {* regels met invoer *}
                        {if $r.row_start == NULL && $r.row_end == NULL}
                            {if ($type == 'verkoop' && $r.doorbelasten_aan != 'uitzender') || ( $r.bemiddelingskosten == 1 && $type == 'kosten' )  || ($type == 'kosten' && $r.uitkeren_werknemer == 1)  || ($type == 'zzp' && $r.uitkeren_werknemer == 1) || (isset($relatie_factuurgegevens.verkoop_kosten_gelijk) && $relatie_factuurgegevens.verkoop_kosten_gelijk == 1) }
								<tr>
									<td style="padding-left: 18px">{$r.omschrijving}</td>
                                    {if $aangenomenwerk == 0}
										<td class="text-right">
                                            {if $r.uren_aantal != NULL}{$r.uren_aantal}{/if}
										</td>
										<td class="text-right">
                                            {if $r.uren_decimaal != NULL}{$r.uren_decimaal|number_format:2:',':'.'}{/if}
										</td>
										<td class="text-right">
                                            {if $type == 'verkoop' && $relatie_factuurgegevens.verkoop_kosten_gelijk == 0}&euro; {$r.verkooptarief|number_format:2:',':'.'} {/if}
                                            {if $type == 'verkoop' && $relatie_factuurgegevens.verkoop_kosten_gelijk == 1}&euro; {$r.bruto_uurloon|number_format:2:',':'.'} {/if}
                                            {if $type == 'kosten'}&euro; {$r.bruto_uurloon|number_format:2:',':'.'} {/if}
                                            {if $type == 'zzp'}&euro; {$r.bruto_uurloon|number_format:2:',':'.'} {/if}
										</td>
										<td class="text-right">
                                            {* uren bij verkoop altijd factor 1 *}
                                            {if $r.uren_aantal != NULL}
                                                {if $type == 'verkoop' && $relatie_factuurgegevens.verkoop_kosten_gelijk != 1}1,000{else}{$r.factor|number_format:3:',':'.'}{/if}
                                            {else}
                                                {$r.factor|number_format:3:',':'.'}
                                            {/if}
										</td>
										<td class="text-right">
                                            {if $type == 'verkoop' && $relatie_factuurgegevens.verkoop_kosten_gelijk == 0}100%{/if}
                                            {if $type == 'verkoop' && $relatie_factuurgegevens.verkoop_kosten_gelijk == 1}{$r.percentage|number_format:2:',':'.'}%{/if}
                                            {if $type == 'kosten'}{$r.percentage|number_format:2:',':'.'}%{/if}
                                            {if $type == 'zzp'}100%{/if}
										</td>
                                    {else}
										<td colspan="5"></td>
                                    {/if}
									<td class="text-right">
                                        {if $type == 'verkoop'}&euro; {$r.subtotaal_verkoop|number_format:2:',':'.'} {/if}
                                        {if $type == 'kosten'}&euro; {$r.subtotaal_kosten|number_format:2:',':'.'} {/if}
                                        {if $type == 'zzp'}&euro; {$r.subtotaal_kosten|number_format:2:',':'.'} {/if}
									</td>
								</tr>
                            {/if}
                        {/if}
                    {/if}

                    {* regel met subtotaal *}
                    {if $r.row_end != NULL}
						<tr class="tr-end">
							<td colspan="5"></td>
							<td class="bold text-right">subtotaal</td>
							<td class="bold text-right">
                                {if $type == 'verkoop'}&euro; {$r.subtotaal_verkoop|number_format:2:',':'.'}{/if}
                                {if $type == 'kosten' || $type == 'zzp'}&euro; {$r.subtotaal_kosten|number_format:2:',':'.'}{/if}
							</td>
						</tr>
                    {/if}
                {/if}
            {/foreach}
        {/if}

        {* factuur totalen *}
		<tr>
			<td colspan="7" style="height: 25px;"></td>
		</tr>
        {if isset($factuur.kosten_korting) && $factuur.kosten_korting != NULL && $factuur.kosten_korting > 0 && isset($korting_uitzender) && $korting_uitzender != NULL}
			<tr class="totaal">
				<td colspan="6" class="text-right">korting ({$korting_uitzender.korting_percentage|number_format:2:',':'.'}%)</td>
				<td class="text-right">&euro; -{$factuur.kosten_korting|number_format:2:',':'.'}
				</td>
			</tr>
        {/if}
		<tr class="totaal">
			<td colspan="6" class="text-right">totaal excl. BTW</td>
			<td class="text-right">&euro;
                {if $type == 'verkoop'}{$factuur.bedrag_excl|number_format:2:',':'.'}{/if}
                {if $type == 'kosten'}{$factuur.kosten_excl|number_format:2:',':'.'}{/if}
                {if $type == 'zzp'}{$factuur.bedrag_excl|number_format:2:',':'.'}{/if}
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
                    {if $type == 'zzp'}{$factuur.bedrag_btw|number_format:2:',':'.'}{/if}
                {/if}

			</td>
		</tr>
		<tr class="totaal">
			<td colspan="6" class="text-right">totaal</td>
			<td class="text-right">
				&euro;
                {if $type == 'verkoop'}{$factuur.bedrag_incl|number_format:2:',':'.'}{/if}
                {if $type == 'kosten'}{$factuur.kosten_incl|number_format:2:',':'.'}{/if}
                {if $type == 'zzp'}{$factuur.bedrag_incl|number_format:2:',':'.'}{/if}
			</td>
		</tr>

	</tbody>
</table>

{if $type == 'verkoop'}
    {if isset($grekening_bedrag) && $grekening_bedrag != NULL && $grekening_bedrag != '' && $grekening_bedrag != 0}
		<div class="grekening">
			Er moet {$grekening_percentage|number_format:0:',':'.'}% (&euro; {$grekening_bedrag|number_format:2:',':'.'}) naar de G-rekening op IBAN: NL 93 INGB 0990 3336 20
            {if isset($factoring) && $factoring == false}
				<br/>
				Het resterende bedrag (&euro; {($factuur.bedrag_incl - $grekening_bedrag)|number_format:2:',':'.'}) naar IBAN: {$bedrijfsgegevens.iban|default:''}
            {/if}
		</div>
    {/if}

    {if !isset($factoring) || $factoring == true}
        {if isset($cessie_tekst) && $cessie_tekst != NULL && $cessie_tekst != ''}
			<div class="cessie_tekst">
                {$cessie_tekst}
			</div>
        {/if}
    {/if}
{/if}