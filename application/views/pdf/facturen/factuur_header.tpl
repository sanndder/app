<table style="background-color: #002E65; width: 100%">
	<tr>
		<td style="width: 55px;">
			<img src="recources/img/logo-wit.png" style="max-height: 40px; margin-top: 7px; margin-left: 15px; margin-bottom: 10px" />
		</td>
		<td style="font-size: 30px; color:#fff; padding-top: 2px; padding-left: 15px; vertical-align: middle">
            {$bedrijfsgegevens.bedrijfsnaam}
		</td>
		<td style="text-align: right; vertical-align: text-top; padding-top: 8px;">

            {if $type == 'verkoop' || $type == 'marge'}
			<table style="color: #FFF; margin-right: 25px;">
				<tr>
					<td>Factuurnummer: </td>
					<td style="font-weight: bold">
						{$factuur_nr|default:'[CONCEPT]'}
					</td>
				</tr>
				<tr>
					<td>Relatienummer: </td>
					<td style="font-weight: bold">
						{if isset($relatie_gegevens.inlener_id)}{$relatie_gegevens.inlener_id}{/if}
						{if isset($relatie_gegevens.uitzender_id)}{$relatie_gegevens.uitzender_id}{/if}
					</td>
				</tr>
			</table>
			{/if}

            {if $type == 'zzp'}
				<table style="color: #FFF; margin-right: 25px;">
					<tr>
						<td>Factuurnummer: </td>
						<td style="font-weight: bold">
                            {$factuur_nr|default:'[CONCEPT]'}
						</td>
					</tr>
				</table>
            {/if}

		</td>
	</tr>
</table>
