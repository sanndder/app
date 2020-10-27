
<div class="footer" style="background-color: #FFF">

	<img src="recources/img/footer.jpg" style="width: 100%; margin-bottom: -75px; height: 100px">

	<table style="font-size: 11px; color:#002E65; width: 100%; margin-left: 25px; margin-right: 25px; margin-top: 5px; margin-bottom: 7px;">
		<tr>
			<td style="width: 24%;">
				<b>{$bedrijfsgegevens.bedrijfsnaam|default:''}</b>
			</td>
			<td style="width: 24%;">
                {$bedrijfsgegevens.telefoon|default:''}
			</td>
			<td style="width: 24%;">
                KvK nr.: {$bedrijfsgegevens.kvknr|default:''}
			</td>
			<td style="width: 28%;" rowspan="3"></td>
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
			</td>>
		</tr>

	</table>
</div>
