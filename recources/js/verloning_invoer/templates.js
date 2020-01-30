//lege inlener lijst
let tplInlenersListEmpty = '<li class="nav-item"><a href="javascript:void(0)" class="nav-link vi-list-item"> <span class="font-italic">Geen inleners gevonden</span> </a></li>';

//inlener lijst laden
let tplInlenersListLoad = '<li class="nav-item"><a href="javascript:void(0)" class="nav-link vi-list-item"> <span class="font-italic"><i class="icon-spinner2 spinner mr-1"></i> Inleners zoeken...</span> </a></li>';

//inlener lijst
let tplInlenersTitle = '<li class="nav-item-header font-weight-bolder mt-0 mb-0 pb-1"> <div class="text-uppercase font-size-xs line-height-xs">Inleners {frequentie}</div></li>';
let tplInlenersLi = '<span data-id="{key}" data-inlener="{inlener}" class="nav-link" data-vi-action="setInlener"><span>{inlener}</span></span>';

//periode dropdown
let tplPeriodeList = '<a href="javascript:void(0)" class="dropdown-item" data-value="{key}" data-vi-action="setPeriode">{value}</a>';

//overzicht laden
let tplOverzichtTabLoad = '<div class="p-4 font-italic"><i class="icon-spinner2 spinner mr-1"></i> Overzicht laden...</li></div>';
let tplOverzichtTabEmpty = '<div class="p-4 font-italic"><i class="icon-exclamation mr-1"></i> Geen werknemers gevonden</li></div>';

//overzicht tabel tr
let tplTrOverzicht = `<tr>
						<td class="pr-4" style="width: 400px">
						<h6 class="media-title font-weight-semibold" style="font-size: 14px">
							<a href="javascript:void(0)" data-id="{werknemer_id}" data-vi-action="gotoWerknemerInvoer">{werknemer_id} -  {naam}</a>
						</h6>
							<ul class="list-inline list-inline-dotted text-danger mb-2">
								<li class="list-inline-item">{msg}</li>
							</ul>
						</td>
						<td>
							<table class="vi-table-werknemer-detail"> <tr> <th colspan="2">UREN</th> </tr> </table>
						</td>
						<td>
							<table class="vi-table-werknemer-detail"> <tr> <th colspan="2">Kilometers</th> </tr> </table>
						</td>
						<td>
							<table class="vi-table-werknemer-detail"> <tr> <th colspan="2">Vergoedingen</th> </tr> </table> </td>
						<td>
							<table class="vi-table-werknemer-detail"> <tr> <th colspan="2">Reserveringen</th> </tr> </table>
						</td>
					</tr>`;


//werknemerlijst laden
let tplUreninvoerTabLoadList = '<i class="icon-spinner2 spinner mr-1"></i> Werknemers laden...';
let tplUreninvoerWerknemerLi = '<li class="vi-list-item text-muted" data-id="{werknemer_id}" data-vi-action="setWerknemer"><span>{werknemer_id} -  {naam}</span></li>';

//wait voor alle invoerschermen
let tplInvoerLoad = '<div class="mt-1 wait-div"><i class="icon-spinner2 spinner mr-1"></i> Invoer laden...</div>';


//select met urentypes
let tplUrenTypesSelect = '<option value="{id}">{label}</option>';

//ureninvoer tabel row
let tplUrenInvoerTr = `<tr class="{class}" data-id="{invoer_id}">
							<td>
								<i class="icon-add icon-add-row mr-2" data-vi-action="addUrenInvoerRow" data-title="Regel invoegen" title="Regel invoegen" data-popup="tooltip" data-placement="left"></i>
							</td>
							<td>{week}</td>
							<td>{dag}</td>
							<td class="td-datum">{datum}</td>
							<td>
								<select name="urentype_id" class="vi-select-urentype form-control" data-vi-action="saveUrenRow">
									{select_uren}
								</select>
							</td>
							<td>
								<input autocomplete="off" value="{aantal}" name="aantal" type="text" class="form-control text-right" placeholder="0:00" style="width: 40px" data-vi-action="saveUrenRow">
							</td>
							<td>
								<input value="{project_tekst}" name="project_tekst" type="text" class="form-control" data-vi-action="saveUrenRow">
							</td>
							<td>
								<input value="{locatie_tekst}" name="locatie_tekst" type="text" class="form-control" data-vi-action="saveUrenRow">
							</td>
						</tr>`;

//km status
let tplKmInvoerStatusSave = '<i class="icon-spinner2 spinner mr-1"></i>opslaan....';
let tplKmInvoerStatusRoute = '<i class="icon-spinner2 spinner mr-1"></i>afstand laden....';
let tplKmInvoerStatusSuccess = '<span class="text-success"><i class="icon-checkmark-circle mr-1"></i>opgeslagen</span>';
let tplKmInvoerStatusError = '<span class="text-danger"><i class="icon-warning22 mr-1"></i>niet opgeslagen!</span>';


//kilometer invoer tabel row
let tplKmInvoerTr = `<tr data-id="">
						<td>
							<div class="input-group">
								<input value="{datum}" type="text" class="form-control pickadate-vi-km" name="datum" data-vi-action="saveKmRow" />
							</div>
						</td>
						<td>
							<input value="{locatie_van}" type="text" class="form-control" name="locatie_van" data-bing="location" placeholder="Plaats, Straatnaam" autocomplete="off" data-vi-action="saveKmRow" />
						</td>
						<td>
							<input value="{locatie_naar}" type="text" class="form-control" name="locatie_naar" data-bing="location" placeholder="Plaats, Straatnaam" autocomplete="off" data-vi-action="saveKmRow" />
						</td>
						<td>
							<input value="{aantal}" type="text" class="form-control text-right" name="aantal" readonly data-vi-action="saveKmRow" />
						</td>
						<td>
							<input value="{opmerking_tekst}" type="text" class="form-control" name="opmerking_tekst" data-vi-action="saveKmRow" />
						</td>
						<td>
							<select name="doorbelasten" class="form-control" data-vi-action="saveKmRow">
								<option></option>
								<option value="inlener">Inlener</option>
								<option value="uitzender">Uitzender</option>
							</select>
						</td>
						<td class="td-actions pt-1">
							<a class="text-grey-200" data-vi-action="showRoute" href="" target="_blank" data-title="Route bekijken op kaart" data-popup="tooltip" title="Route bekijken op kaart" data-placement="left">
								<i class="fas fa-directions fa-2x"></i>
							</a>
						</td>
						<td class="td-status pt-1"></td>
					</tr>`;

//bijlages tabel
let tplBijlageTr = `<tr>
						<td class="pr-3"> <i class="icon-radio-unchecked text-grey-200"></i></td>
						<td> <img class="file-icon" src="recources/img/icons/{icon}"/></td>
						<td> <a href="" target="_blank">{file_name_display}</a></td>
						<td>{project_naam}</td>
						<td class="text-right">{file_size}</td>
						<td>{timestamp}</td>
						<td>{user}</td>
						<td></td>
						</tr>`;