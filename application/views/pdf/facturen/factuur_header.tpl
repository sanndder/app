<table style="width: 100%">
	<tr>
		<td style="width: 55px;">
            {if $werkgever_type == 'uitzenden'}
				<img src="recources/img/logo-uitzenden.jpg" style="max-height: 40px; margin-top: 8px; margin-left: 15px; margin-bottom: 8px" />
			{/if}
            {if $werkgever_type == 'bemiddeling'}
	            <img src="recources/img/logo-bemiddeling.jpg" style="max-height: 40px; margin-top: 8px; margin-left: 15px; margin-bottom: 8px" />
			{/if}
		</td>
		<td style="font-size: 30px; color:#fff; padding-top: 2px; padding-left: 15px; vertical-align: middle">
		</td>
		<td style="text-align: right; vertical-align: text-top; padding-top: 5px;">

			<table style="color: #555555; margin-right: 25px;">
				<tr>
					<td style="text-align: right; font-size: 22px">
                        {if !isset($type) || $type == 'verkoop' || $type == 'zzp'} FACTUUR <span style="font-weight: bold">{$factuur_nr|default:'[CONCEPT]'}</span> {/if}
                        {if $type == 'marge'}MARGEFACTUUR <span style="font-weight: bold">{$factuur_nr|default:'[CONCEPT]'}</span> {/if}
                        {if $type == 'kosten'} KOSTENOVERZICHT {/if}
					</td>
				</tr>
				<tr>
					<td style="padding-top: -3px">
                        {if $factuur.tijdvak == 'w'}Week {$periode} - {$jaar}{/if}
                        {if $factuur.tijdvak == '4w'}Periode {$periode} - {$jaar}{/if}
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>

{if $werkgever_type == 'uitzenden'}
	<div class="balk" style="width: 100%; height: 5px; background-color: #2DA4DC"></div>
{/if}
{if $werkgever_type == 'bemiddeling'}
	<div class="balk" style="width: 100%; height: 5px; background-color: #22AF8F"></div>
{/if}