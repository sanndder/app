let _werknemer = "werknemer";
let _werknemers = "werknemers";

if( werkgever == 'bemiddeling' )
{
	_werknemer = "ZZP'er";
	_werknemers = "ZZP'ers";
}

//lege inlener lijst
let tplInlenersListEmpty = '<li class="nav-item"><a href="javascript:void(0)" class="nav-link vi-list-item"> <span class="font-italic">Geen inleners gevonden</span> </a></li>';

//inlener lijst laden
let tplInlenersListLoad = '<li class="nav-item"><a href="javascript:void(0)" class="nav-link vi-list-item"> <span class="font-italic"><i class="icon-spinner2 spinner mr-1"></i> Inleners zoeken...</span> </a></li>';

//inlener lijst
let tplInlenersTitle = '<li class="nav-item-header font-weight-bolder mt-0 mb-0 pb-1"> <div class="text-uppercase font-size-xs line-height-xs">Inleners {frequentie}</div></li>';
let tplInlenersLi = '<span data-id="{key}" data-inlener="{inlener}" class="nav-link d-flex justify-content-between" data-vi-action="setInlener"><span>{inlener}</span><i class="vi-icon-file mr-0 {icon}"></i></span>';

//periode dropdown
let tplPeriodeList = '<a href="javascript:void(0)" class="dropdown-item" data-value="{key}" data-vi-action="setPeriode">{value}</a>';

//overzicht laden
let tplOverzichtTabLoad = '<div class="p-4 font-italic"><i class="icon-spinner2 spinner mr-1"></i> Overzicht laden...</li></div>';
let tplOverzichtTabEmpty = '<div class="p-4 font-italic"><i class="icon-exclamation mr-1"></i> Geen ' + _werknemers + ' gevonden</li></div>';

//overzicht tabel tr
let tplTrOverzicht = `<tr data-vi-overzicht-row="{werknemer_id}">
						<td class="pr-4" style="width: 400px">
							<h6 class="media-title font-weight-semibold" style="font-size: 14px">
								<a href="javascript:void(0)" data-id="{werknemer_id}" data-vi-action="gotoWerknemerInvoer">{werknemer_id} -  {naam}</a>
							</h6>
							<ul class="list-inline list-inline-dotted text-danger mb-2">
								<li class="list-inline-item">{msg}</li>
							</ul>
						</td>
						<td class="vi-td-overzicht"> <table class="vi-table-werknemer-overzicht-detail vi-table-werknemer-overzicht-detail-uren"><tr><th colspan="2">Uren</th></tr></table> </td>
						<td class="vi-td-overzicht"> <table class="vi-table-werknemer-overzicht-detail vi-table-werknemer-overzicht-detail-kilometers"> <tr> <th colspan="2">Kilometers</th></tr> </table></td>
						<td class="vi-td-overzicht"> <table class="vi-table-werknemer-overzicht-detail vi-table-werknemer-overzicht-detail-vergoedingen"> <tr> <th colspan="2">Vergoedingen</th></tr> </table></td>
						<td class="vi-td-overzicht"> <table class="vi-table-werknemer-overzicht-detail vi-table-werknemer-overzicht-detail-reserveringen"> <tr> <th colspan="2">Reserveringen</th></tr> </table></td>
						<td></td>
					</tr>`;


//werknemerlijst laden
let tplUreninvoerTabLoadList = '<i class="icon-spinner2 spinner mr-1"></i> ' + _werknemers + ' laden...';
let tplUreninvoerWerknemerLi = '<li class="vi-list-item text-muted" data-id="{werknemer_id}" data-vi-action="setWerknemer"><span>{werknemer_id} -  {naam}</span></li>';

//wait voor alle invoerschermen
let tplInvoerLoad = '<div class="mt-1 wait-div"><i class="icon-spinner2 spinner mr-1"></i> Invoer laden...</div>';

//select met urentypes
let tplProjectSelect = '<option value="{id}">{label}</option>';

//select met urentypes
let tplUrenTypesSelect = '<option value="{id}">{label}</option>';

//ureninvoer tabel row
let tplUrenInvoerTr = `<tr class="{class}" data-id="{invoer_id}">
							<td><i class="icon-add icon-add-row mr-2" data-vi-action="addUrenInvoerRow" data-title="Regel invoegen" title="Regel invoegen" data-popup="tooltip" data-placement="left"></i></td>
							<td>{week}</td>
							<td>{dag}</td>
							<td class="td-datum">{datum}</td>
							<td>
								<select name="urentype_id" class="vi-select-urentype form-control" data-vi-action="saveUrenRow">
									{select_uren}
								</select>
							</td>
							<td><input autocomplete="off" value="{aantal}" name="aantal" type="text" class="form-control text-right" placeholder="0:00" style="width: 40px" data-vi-action="saveUrenRow"></td>
							<td>
								<select name="project_id" class="vi-select-project form-control" data-vi-action="saveUrenRow">
									{select_projecten}
								</select>
								<input value="{project_tekst}" name="project_tekst" type="text" class="form-control" data-vi-action="saveUrenRow">
							</td>
							<td><input value="{locatie_tekst}" name="locatie_tekst" type="text" class="form-control" data-vi-action="saveUrenRow"></td>
						</tr>`;

//km status
let tplKmInvoerStatusSave = '<i class="icon-spinner2 spinner mr-1"></i>opslaan....';
let tplKmInvoerStatusRoute = '<i class="icon-spinner2 spinner mr-1"></i>afstand laden....';
let tplKmInvoerStatusSuccess = '<span class="text-success"><i class="icon-checkmark-circle mr-1"></i>opgeslagen</span>';
let tplKmInvoerStatusError = '<span class="text-danger"><i class="icon-warning22 mr-1"></i>niet opgeslagen!</span>';


//kilometer invoer tabel row
let tplKmInvoerTr = `<tr data-id="{invoer_id}">
						<td><input value="{aantal}" type="text" class="form-control text-right" name="aantal" data-vi-action="saveKmRow" /></td>
						<td><input value="{opmerking_tekst}" type="text" class="form-control" name="opmerking_tekst" data-vi-action="saveKmRow" /></td>
						<td>
							<select name="doorbelasten" class="form-control" data-vi-action="saveKmRow">
								<option></option>
								<option value="inlener">Inlener</option>
								<option value="uitzender">Uitzender</option>
							</select>
						</td>
						<td class="td-projecten">
							<select name="project_id" class="vi-select-project form-control" data-vi-action="saveKmRow">
								{select_projecten}
							</select>
						</td>
						<td class="td-uitkeren">
							<div class="form-check form-check-inline ml-2">
								<label class="form-check-label mr-2">
									<span class="checked">
										<input value="1" type="radio" class="form-input-styled vi-uitkeren-ckecked" name="uitkeren[{invoer_id}]" data-vi-action="saveKmRow" checked>
									</span>
									<span style="margin: 7px 0 0 3px">Ja</span>
								</label>
								<label class="form-check-label">
									<span>
										<input value="0" type="radio" class="form-input-styled vi-uitkeren-unckecked" name="uitkeren[{invoer_id}]" data-vi-action="saveKmRow">
									</span>
									<span style="margin: 7px 0 0 3px">Nee</span>
								</label>
							</div>
						</td>
						<td class="td-actions pt-1">
						</td>
						<td class="td-status pt-1"></td>
					</tr>`;

//vergoeding invoer tabel row
let tplVergoedingVastTr = `<tr data-id="{invoer_id}">
								<td class="td-vergoeding">{naam}</td>
								<td class="td-euro">€</td>
								<td style="width: 75px;" class="td-input">{bedrag}</td>
								<td>
									<select name="doorbelasten"  class="form-control" data-vi-action="setVergoedingDoorbelasten">
										<option class="keuze">Maak een keuze</option>
										<option value="inlener">Inlener</option>
										<option value="uitzender">Uitzender</option>
									</select>
								</td>
								<td>
									<select name="project_id" class="vi-select-project form-control" data-vi-action="setVergoedingProject">
										{select_projecten}
									</select>
								</td>
							</tr>`;


let tplVergoedingVariabelTr = `<tr data-id="{invoer_id}" data-werknemer-vergoeding-id="{id}">
								<td class="td-vergoeding">{naam}</td>
								<td class="td-euro">€</td>
								<td  style="width: 75px;" class="td-input">
									<input data-vi-action="setVergoedingBedrag" style="width: 75px;" type="text" class="form-control" value="{bedrag}">
								</td>
								<td>
									<select name="doorbelasten" class="form-control" data-vi-action="setVergoedingDoorbelasten">
										<option class="keuze">Maak een keuze</option>
										<option value="inlener">Inlener</option>
										<option value="uitzender">Uitzender</option>
									</select>
								</td>
								<td>
									<select name="project_id" class="vi-select-project form-control" data-vi-action="setVergoedingProject">
										{select_projecten}
									</select>
								</td>
							</tr>`;


//bijlages tabel
let tplBijlageTr = `<tr data-id="{file_id}">
						<td class="pr-3"> <i class="icon-radio-unchecked text-grey-200"></i></td>
						<td><img class="file-icon" src="recources/img/icons/{icon}"/></td>
						<td><a href="ureninvoer/bijlage/{file_id}" target="_blank">{file_name_display}</a></td>
						<td class="td-projecten">
							<select name="project_id" class="vi-select-project form-control" data-vi-action="setBijlageProject">
								{select_projecten}
							</select>
						</td>
						<td class="text-right">{file_size}</td>
						<td>{timestamp}</td>
						<td>{user}</td>
						<td><span class="text-warning" style="font-size: 11px; cursor:pointer;" data-vi-action="delBijlage"> <i class="icon-trash mr-1"></i>verwijderen</span></td>
						</tr>`;


//template voor aangenomenwerk
let tplAangenomenwerkLegend = ` <fieldset class="mb-4"  data-id="{project_id}">
									<legend class="text-primary text-uppercase font-size-sm font-weight-bold mb-1">{project}</legend>
									<table class="vi-aangenomenwerk-regels">
										<thead><tr><th>Omschrijving</th><th>Bedrag (€)</th><th></th></tr></thead>
										<tbody></tbody>
								</fieldset>`;

let tplAangenomenwerkInvoerTr = `<tr data-id="{invoer_id}">
									<td class="td-omschrijving">
										<input name="omschrijving" type="text" class="form-control" value="{omschrijving}">
									</td>
									<td class="td-bedrag">
										<input name="bedrag" type="text" class="form-control text-right" value="{bedrag}">
									</td>
									<td>
										<button type="button" class="btn btn-success btn-sm" data-vi-action="setAangenomenwerkProjectData"><i class="icon-checkmark2 mr-1"></i></button>
									</td>
								</tr>`;