
<div class="div-logo">
	<img class="logo-top" src="{$logo}">
</div>

<div class="gegevens">
	<table>
		<tr>
			<td>Verloningstijdvak</td>
			<td></td>
		</tr>
		<tr>
			<td>Werknemersnummer</td>
			<td></td>
		</tr>
		<tr>
			<td>BurgerServiceNummer</td>
			<td></td>
		</tr>
		<tr>
			<td>Geboortedatum</td>
			<td>01-01-{$post.geboortejaar}</td>
		</tr>
		<tr>
			<td>Indienstdatum</td>
			<td></td>
		</tr>
		<tr>
			<td>Loonbelastingtabel</td>
			<td>Witte tabel</td>
		</tr>
		<tr>
			<td>Loonheffingskorting</td>
			<td>{if $post.loonheffingskorting == 1}toegepast{else}niet toegepast{/if}
			</td>
		</tr>
		<tr>
			<td>% LH Bijz.Beloningen</td>
			<td></td>
		</tr>
		<tr>
			<td>Beroep</td>
			<td></td>
		</tr>
		<tr>
			<td>Wettelijk minimumloon</td>
			<td></td>
		</tr>
		<tr>
			<td>Uurloon</td>
			<td>{$post.bruto|number_format:2:',':'.'}</td>
		</tr>
	</table>
</div>


<div class="blok2">
	<table>
		<tr>
			<th style="width: 180px;"></th>
			<th style="width: 70px;" class="right">Betaalbaar</th>
			<th style="width: 60px;" class="right">Tabel</th>
			<th style="width: 90px;" class="right">Bijz.Bel.</th>
			<th style="width: 25px"></th>
			<th style="text-align: left">Specificatie</th>
		</tr>
		<tr>
			<td>Salaris</td>
			<td class="right">{$data.uurloon.resultaat|number_format:2:',':'.'}</td>
			<td class="right">{$data.uurloon.resultaat|number_format:2:',':'.'}</td>
			<td class="right"></td>
			<td></td>
			<td>
				{$data.uurloon.factor_2|number_format:2:',':'.'} uren x {$data.uurloon.factor_1|number_format:2:',':'.'}
			</td>
		</tr>
		{if isset($et_regeling) && $et_regeling == 1}
			<tr>
				<td>Uitruil bruto loon</td>
				<td class="right">-{$et_bedrag|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>
					{$data.uurloon.factor_2|number_format:2:',':'.'} uren x {$data.uurloon.factor_1|number_format:2:',':'.'}
				</td>
			</tr>
		{/if}
		{if isset($data.prestatietoeslag.resultaat) && $data.prestatietoeslag.resultaat > 0}
			<tr>
				<td>Prestatietoeslag</td>
				<td class="right">{$data.prestatietoeslag.resultaat|number_format:2:',':'.'}</td>
				<td class="right">{$data.prestatietoeslag.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td></td>
				<td>
					{$data.prestatietoeslag.factor_2|number_format:2:',':'.'} uren x {$data.prestatietoeslag.factor_1|number_format:2:',':'.'}
				</td>
			</tr>
		{/if}
		{if isset($data.wachtdagcompensatie.resultaat) && $data.wachtdagcompensatie.resultaat > 0}
			<tr>
				<td>Wachtdagcompensatie</td>
				<td class="right">{$data.wachtdagcompensatie.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td></td>
			</tr>
		{/if}
		<tr>
			<td>Brutoloon</td>
			<td class="right bt">{$data.bruto_loon.resultaat|number_format:2:',':'.'}</td>
			<td class="right"></td>
			<td class="right"></td>
			<td></td>
			<td></td>
		</tr>
		{if $smarty.post.vakantieuren_direct == 1}
			<tr>
				<td>Vakantieuren</td>
				<td class="right">{$data.vakantieuren.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.vakantieuren.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		{if $smarty.post.vakantiegeld_direct == 1}
			<tr>
				<td>Vakantiegeld</td>
				<td class="right">{$data.vakantiegeld.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.vakantiegeld.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		{if $smarty.post.atv_direct == 1}
			<tr>
				<td>ATV-uren</td>
				<td class="right">{$data.atv.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.atv.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}

		{if isset($data.pensioen.resultaat) && $data.pensioen.resultaat != 0}
			<tr>
				<td>Loonafhankelijk Pensioen</td>
				<td class="right">{$data.pensioen.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.pensioen.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		{if isset($data.pensioen_AOP.resultaat) && $data.pensioen_AOP.resultaat != 0}
			<tr>
				<td>Arbeidsongeschiktheidspensioen</td>
				<td class="right">{$data.pensioen_AOP.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.pensioen_AOP.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		{if isset($data.pensioen_55_min.resultaat) && $data.pensioen_55_min.resultaat != 0}
			<tr>
				<td>Pensioen 55-</td>
				<td class="right">{$data.pensioen_55_min.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.pensioen_55_min.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		{if isset($data.pensioen_55_plus.resultaat) && $data.pensioen_55_plus.resultaat != 0}
			<tr>
				<td>Aanvulling 55min Bouwloon</td>
				<td class="right">{$data.pensioen_55_plus.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.pensioen_55_plus.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		{if isset($data.aanv_zw.resultaat) && $data.aanv_zw.resultaat != 0}
			<tr>
				<td>Aanvullingsfonds Zw</td>
				<td class="right">{$data.aanv_zw.resultaat|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.aanv_zw.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		<tr>
			<td>Loon voor Loonheffingen</td>
			<td class="right bt">{$data.heffingsloon.resultaat|number_format:2:',':'.'}</td>
			<td class="right"></td>
			<td class="right"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Loonheffing</td>
			<td class="right">{$data.loonheffing.netto|number_format:2:',':'.'}</td>
			<td class="right"></td>
			<td class="right"></td>
			<td></td>
			<td></td>
		</tr>
		{if isset($data.inhouding_WAO_WGA.netto) && $data.inhouding_WAO_WGA.netto != 0}
			<tr>
				<td>Premie WGA</td>
				<td class="right">{$data.inhouding_WAO_WGA.netto|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>{$data.inhouding_WAO_WGA.percentage|number_format:3:',':'.'} procent</td>
			</tr>
		{/if}
		<tr>
			<td>Nettoloon</td>
			<td class="right bt">{$data.netto.netto|number_format:2:',':'.'}</td>
			<td class="right"></td>
			<td class="right"></td>
			<td></td>
			<td></td>
		</tr>
		{if isset($et_regeling) && $et_regeling == 1}
			<tr>
				<td>Vrije vergoeding uitruil</td>
				<td class="right">{$et_bedrag|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>
					{$data.uurloon.factor_2|number_format:2:',':'.'} uren x {$data.uurloon.factor_1|number_format:2:',':'.'}
				</td>
			</tr>
		{/if}
		{if isset($et_regeling) && $et_regeling == 1 && $data.huisvesting > 0}
			<tr>
				<td>Inhouding huisvesting</td>
				<td class="right">- {$data.huisvesting|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>
				</td>
			</tr>
		{/if}
		{if isset($data.kilometers.netto) && $data.kilometers.netto != 0}
			<tr>
				<td>Kilometervergoeding</td>
				<td class="right">{$data.kilometers.netto|number_format:2:',':'.'}</td>
				<td class="right"></td>
				<td class="right"></td>
				<td></td>
				<td>
					{$post.km|number_format:2:',':'.'} x € 0,19
				</td>
			</tr>
		{/if}
		<tr>
			<td>Uitbetaald</td>
			<td class="right bt">{$data.uitbetaald.netto|number_format:2:',':'.'}</td>
			<td class="right"></td>
			<td class="right"></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</div>

<div class="blok3">
	<table>
		<tr>
			<th style="width: 90px; text-align: left">Reserveringen</th>
			<th style="width: 80px;" class="right"></th>
			<th style="width: 80px;" class="right"></th>
		</tr>
		<tr>
			<td>Vakantieuren</td>
			<td class="right">{$data.vakantieuren.percentage|number_format:3:',':'.'} %</td>
			<td class="right">€ {$data.vakantieuren.resultaat|number_format:2:',':'.'}</td>
		</tr>
		<tr>
			<td>Kort verzuim</td>
			<td class="right">{$data.kort_verzuim.percentage|number_format:3:',':'.'} %</td>
			<td class="right">€ {$data.kort_verzuim.resultaat|number_format:2:',':'.'}</td>
		</tr>
		<tr>
			<td>Feestdagen</td>
			<td class="right">{$data.feestdagen.percentage|number_format:3:',':'.'} %</td>
			<td class="right">€ {$data.feestdagen.resultaat|number_format:2:',':'.'}</td>
		</tr>
		{if $post.cao == 'bouw'}
			<tr>
				<td>ATV-dagen</td>
				<td class="right">{$data.atv.percentage|number_format:3:',':'.'} %</td>
				<td class="right">€ {$data.atv.resultaat|number_format:2:',':'.'}</td>
			</tr>
		{/if}
		<tr>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td>Vakantiegeld</td>
			<td class="right">{$data.vakantiegeld.percentage|number_format:3:',':'.'} %</td>
			<td class="right">€ {$data.vakantiegeld.resultaat|number_format:2:',':'.'}</td>
		</tr>
	</table>
</div>

{if isset($et_regeling) && $et_regeling == 1}
<div class="blok3" style="margin-top: 15px;">
	<table>
		<tr>
			<th style="width: 190px; text-align: left">Verantwoording ET-regling</th>
			<th style="width: 100px;" class="right"></th>
			<th style="width: 100px;" class="right"></th>
		</tr>
		<tr>
			<td>Huisvesting</td>
			<td class="right">€ {$et_huisvesting|number_format:2:',':'.'}</td>
			<td></td>
		</tr>
		<tr>
			<td>Kilometers</td>
			<td class="right">€ {$et_km_bedrag|number_format:2:',':'.'}</td>
			<td class="right"> ({$post.et_km} x € 0.19)</td>
		</tr>
		<tr>
			<td>Verschil levensstandaard</td>
			<td class="right">€ {$et_verschil_leven|number_format:2:',':'.'}</td>
			<td></td>
		</tr>
	</table>
</div>
{/if}


<div class="disclaimer">
	<div>
	LET OP! DIT IS EEN PROFORMA-LOONSTROOK. De berekeningen op deze loonstrook zijn zo precies mogelijk gemaakt maar kunnen afwijken van de werkelijke loonstrook. Aan de berekeningen op deze loonstrook kunnen
	<u>geen</u> rechten worden ontleend.</div>
</div>