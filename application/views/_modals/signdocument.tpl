<!-- load custom javascript -->
<style>
	#signature-pad canvas{
		border:1px solid #000;

	}
</style>

<script src="recources/js/modals/documenten/sign_document.js?2" type="text/javascript"></script>
<script src="recources/plugins/signature-html5/signature.min.js" type="text/javascript"></script>

<!-- Contactpersonen form -->
<div id="modal_sign_document" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-xl" style="height: 93%">
		<div class="modal-content" style="height: 100%">
			<div class="modal-header bg-primary">
				<h5 class="modal-title"><i class="icon-pen6 mr-1"></i> Document ondertekenen</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body">

				<button type="button" class="btn btn-success toggle-pad">
					<i class="icon-pen6 mr-2"></i>Document tekenen
				</button>

				<div id="pdfviewer" style="height: 96%"></div>



			</div>

		</div>
	</div>
</div>
<!-- /horizontal form modal -->
