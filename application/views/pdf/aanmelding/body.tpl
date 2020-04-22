<div class="container">

	<h2 style="padding-top: 25px; margin-bottom: 0px">{$werknemer.naam}</h2>
	<h2 style="padding-top: 0px; margin-top: 0; font-weight: normal">Aanmelding & Gegevens Loonheffing</h2>

	<table class="aanmelding" style="font-size: 11pt; margin-top: 25px;">
		<tr>
			<td style="padding-right: 60px;">Naam:</td>
			<td>{$werknemer.naam}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">BSN:</td>
			<td>{$werknemer.bsn}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Straat en huisnummer:</td>
			<td>{$werknemer.straat} {$werknemer.huisnummer}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Postcode en woonplaats:</td>
			<td>{$werknemer.postcode} {$werknemer.plaats}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Woonland:</td>
			<td>{$landen[$werknemer.woonland_id]}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Geboortedatum:</td>
			<td>{$werknemer.gb_datum|date_format: '%d-%m-%Y'}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Loonheffing toepassen:</td>
			<td>Ja</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Loonheffing toepassen vanaf:</td>
			<td>{$indienst|date_format: '%d-%m-%Y'}</td>
		</tr>
		<tr>
			<td style="padding-right: 60px;">Akkoord werknemer:</td>
			<td>{$indienst|date_format: '%d-%m-%Y %R:%S'}</td>
		</tr>

	</table>

</div>