<div class="footer" style="background-color: #002E65">
	<table style="font-size: 11px; color:#fff; width: 100%; margin-left: 25px; margin-right: 25px; margin-top: 5px; margin-bottom: 7px;">

		<tr>
			<td style="width: 40%;">
				<b>{$bedrijfsgegevens.bedrijfsnaam|default:''}</b>
			</td>
			<td style="width: 35%;">
                {$bedrijfsgegevens.telefoon|default:''}
			</td>
			<td style="width: 25%;">
                KvK nr.: {$bedrijfsgegevens.kvknr|default:''}
			</td>
		</tr>

		<tr>
			<td>
				{$bedrijfsgegevens.straat|default:''} {$bedrijfsgegevens.huisnummer|default:''}
			</td>
			<td>
                {$bedrijfsgegevens.email|default:''}
			</td>
			<td>
				BTW nr.: {$bedrijfsgegevens.btwnr|default:''}
			</td>
		</tr>

		<tr>
			<td>
                {$bedrijfsgegevens.postcode} {$bedrijfsgegevens.plaats|default:''}
			</td>
			<td>
                {$bedrijfsgegevens.website|default:''}
			</td>
			<td>
				IBAN:
				{if isset($iban_factoring) && $iban_factoring != NULL && $iban_factoring != ''}
					{$iban_factoring}
				{else}
                    {$bedrijfsgegevens.iban|default:''}
				{/if}

			</td>
		</tr>

	</table>
</div>
