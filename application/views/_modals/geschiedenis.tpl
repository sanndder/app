<!-- load custom javascript -->
<script src="recources/js/modals/history.js" type="text/javascript"></script>

<!-- Contactpersonen form -->
<div id="modal_history" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-xxl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Overzicht wijzigingen</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="ajax-wait mt-4 mb-4" style="text-align: center">
				<i class="icon-spinner2 spinner mr-1"></i>
				Gegevens worden geladen....
			</div>

			<div class="modal-body" style="display: none">

				<table class="table table-xs table-striped" style="font-size: 12px;">
					<thead></thead>
					<tbody></tbody>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal"><i class="icon-cross2 mr-1"></i>Sluiten</button>
			</div>

		</div>
	</div>
</div>
<!-- /horizontal form modal -->