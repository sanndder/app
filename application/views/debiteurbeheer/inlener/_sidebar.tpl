	<!-------------------------------------------------- bedrijfsgegevens -------------------------------------------------------------->
	<div class="card" style="position: sticky; top: 65px">
		<div class="card-body">
			<span style="font-size: 16px" class="font-weight-semibold text-primary">{$inlener.bedrijfsnaam}</span>
			<table class="mt-3">
				<tr>
					<td class="pr-2"><i class="icon-phone2"></i></td>
					<td class="copy-text">{$inlener.telefoon}</td>
				</tr>
				<tr>
					<td class="pr-2 pt-2"><i class="icon-envelop2"></i></td>
					<td class="pt-2 copy-text">{$emailadressen.standaard}</td>
				</tr>
                {if $emailadressen.facturatie !== NULL}
					<tr>
						<td class="pr-2 pt-2"><i class="icon-envelop2"></i></td>
						<td class="pt-2 copy-text"> {$emailadressen.standaard}</td>
					</tr>
                {/if}
                {if $emailadressen.administratie !== NULL}
					<tr>
						<td class="pr-2 pt-2"><i class="icon-envelop2"></i></td>
						<td class="pt-2 copy-text">{$emailadressen.administratie}</td>
					</tr>
                {/if}
			</table>

		</div>
	</div><!---- /bedrijfsgegevens -->

	<!-------------------------------------------------- menu -------------------------------------------------------------->
	<div class="card sidebar sidebar-light sidebar-main sidebar-content" style="position: sticky; top: 65px; overflow: auto">
		<div class="card-body p-0">
			<ul class="nav nav-sidebar" data-nav-type="accordion">

				<!-- li Overzicht -->
				<li class="nav-item">
					<a href="debiteurbeheer/inlener/index/{$inlener_id}" class="nav-link {if $active == 'overzicht'}active{/if}">
							<i class="icon-home5 mr-2"></i>Overzicht
					</a>
				</li>
				<li class="nav-item">
					<a href="debiteurbeheer/inlener/notities/{$inlener_id}" class="nav-link {if $active == 'notities'}active{/if}">
						<i class="icon-pencil mr-2"></i>Notities
					</a>
				</li>
				<li class="nav-item">
					<a href="debiteurbeheer/inlener/facturen/{$inlener_id}" class="nav-link {if $active == 'facturen'}active{/if}">
						<i class="icon-files-empty mr-2"></i>Facturen
					</a>
				</li>
				<li class="nav-item">
					<a href="debiteurbeheer/inlener/betalingen/{$inlener_id}" class="nav-link {if $active == 'betalingen'}active{/if}">
						<i class="icon-coin-euro mr-2"></i>Betalingen
					</a>
				</li>
			</ul>
		</div>
	</div><!---- /menu -->
