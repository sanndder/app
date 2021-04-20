{if $werkgever_type == 'uitzenden'}
	<div class="balk" style="width: 100%; height: 5px; background-color: #2DA4DC"></div>
{/if}
{if $werkgever_type == 'bemiddeling'}
	<div class="balk" style="width: 100%; height: 5px; background-color: #22AF8F"></div>
{/if}

<div class="footer">
	<table style="font-size: 11px; color:#555; width: 100%; margin-left: 10px; margin-right: 10px; margin-top: 6px; margin-bottom: 11px; text-align: center">

		<tr>
			<td style="width: 100%;">
				<b>{$bedrijfsgegevens.bedrijfsnaam|default:''}</b>
				<span style="color: #2DA4DC; font-weight: bold">|</span>
                {$bedrijfsgegevens.straat|default:''} {$bedrijfsgegevens.huisnummer|default:''}, {$bedrijfsgegevens.postcode} {$bedrijfsgegevens.plaats|default:''}
				<span style="color: #2DA4DC; font-weight: bold">|</span>
                {$bedrijfsgegevens.telefoon|default:''}
				<span style="color: #2DA4DC; font-weight: bold">|</span>
                {$bedrijfsgegevens.email|default:''}
				<span style="color: #2DA4DC; font-weight: bold">|</span>
				KvK nr.: {$bedrijfsgegevens.kvknr|default:''}
				<span style="color: #2DA4DC; font-weight: bold">|</span>
				BTW nr.: {$bedrijfsgegevens.btwnr|default:''}
				<span style="color: #2DA4DC; font-weight: bold">|</span>
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
