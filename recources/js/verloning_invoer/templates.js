//lege inlener lijst
let tplInlenersListEmpty  = '<li class="nav-item"><a href="javascript:void(0)" class="nav-link vi-list-item"> <span class="font-italic">Geen inleners gevonden</span> </a></li>';

//inlener lijst laden
let tplInlenersListLoad  = '<li class="nav-item"><a href="javascript:void(0)" class="nav-link vi-list-item"> <span class="font-italic"><i class="icon-spinner2 spinner mr-1"></i> Inleners zoeken...</span> </a></li>';

//inlener lijst
let tplInlenersTitle  = '<li class="nav-item-header font-weight-bolder mt-0 mb-0 pb-1"> <div class="text-uppercase font-size-xs line-height-xs">Inleners {frequentie}</div></li>';
let tplInlenersLi  = '<span data-id="{key}" class="nav-link" data-vi-action="setInlener"><span>{inlener}</span></span>';

//periode dropdown
let tplPeriodeList = '<a href="javascript:void(0)" class="dropdown-item" data-value="{key}" data-vi-action="setPeriode">{value}</a>';