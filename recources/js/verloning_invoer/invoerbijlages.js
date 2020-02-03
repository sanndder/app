// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoerbijlages = {
	//properties aanmaken
	init(){
		//events binden
		this.events();
		
		invoerbijlages.filecount = 0;
		invoerbijlages.filesuploaded = 0;
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		
		//bestand checken
		$(document).on('click', '.icon-radio-unchecked', function(){
			$(this).removeClass('icon-radio-unchecked text-grey-200').addClass('icon-checkmark-circle text-primary');
		});
		//bestand unchecken
		$(document).on('click', '.icon-checkmark-circle', function(){
			$(this).addClass('icon-radio-unchecked text-grey-200').removeClass('icon-checkmark-circle text-primary');
		});
		
		//alle bestanden
		$(document).on('click', '[data-vi-action="checkAllBijlages"]', function(){
			if( $(this).hasClass('icon-radio-unchecked') )
				//check wordt leeg
				$('.icon-checkmark-circle').addClass('icon-radio-unchecked text-grey-200').removeClass('icon-checkmark-circle text-primary');
			else
				//gechecked
				$('.icon-radio-unchecked').removeClass('icon-radio-unchecked text-grey-200').addClass('icon-checkmark-circle text-primary');
			
		});
		
		//clear input when done
		$(document).on('fileuploaded', '#upload-bijlages', function(){
			$('#upload-bijlages').fileinput('clear');
			
			invoerbijlages.filesuploaded++;
			if( invoerbijlages.filecount == invoerbijlages.filesuploaded){
				invoerbijlages.loadFiles();
			}
		});
		
		//count files
		$(document).on('fileselect', '#upload-bijlages', function(){
			invoerbijlages.filecount = $('#upload-bijlages').fileinput('getFilesCount');
			invoerbijlages.filesuploaded = 0;
		});
		
		//verwijder 1 bijlage
		$(document).on('click', '[data-vi-action="delBijlage"]', function(){
			invoerbijlages.delBijlage(this);
		});
		
		//verwijder meerdere bijlages
		$(document).on('click', '[data-vi-action="delSelectedBijlages"]', function(){
			invoerbijlages.delSelectedBijlages();
		});
		
	},
	
	buildBijlagesTab(){
		//data toevoegen aan uploader
		$('#upload-bijlages').fileinput('refresh', {uploadExtraData:data});
		
		//bestanden ophalen
		invoerbijlages.loadFiles();
	},
	
	
	//-- verwijder meerdere bijlages ----------------------------------------------------------------------------------------------------------------------------
	delSelectedBijlages(){

		Swal.fire({
			type:'warning',
			title:'Geselecteerde bijlages verwijderen?',
			text:'',
			showCancelButton:true,
			confirmButtonClass:'btn btn-success',
			cancelButtonClass:'btn btn-warning',
			confirmButtonText:'<i class="icon-check mr-1"></i>Verwijderen',
			cancelButtonText:'<i class="icon-cross2 mr-1"></i>Annuleren'
		}).then((result) => {
			
			$('.table-vi-bijlages tbody .icon-checkmark-circle').each(function(){
				
				invoerbijlages.callDeleleBijlage( $(this).closest('tr') );
				$('[data-vi-action="checkAllBijlages"]').addClass('icon-radio-unchecked text-grey-200').removeClass('icon-checkmark-circle text-primary');
			});
		});
	},
	
	//-- verwijder 1 bijlage ----------------------------------------------------------------------------------------------------------------------------
	delBijlage(obj){
		$tr = $(obj).closest('tr');
		
		Swal.fire({
			type:'warning',
			title:'Bijlage verwijderen?',
			text:'',
			showCancelButton:true,
			confirmButtonClass:'btn btn-success',
			cancelButtonClass:'btn btn-warning',
			confirmButtonText:'<i class="icon-check mr-1"></i>Verwijderen',
			cancelButtonText:'<i class="icon-cross2 mr-1"></i>Annuleren'
		}).then((result) => {
			
			invoerbijlages.callDeleleBijlage( $tr );
			
		})
	},
	
	//-- verwijder 1 bijlage ----------------------------------------------------------------------------------------------------------------------------
	callDeleleBijlage($tr){
		
		data.file_id = $tr.data('id');
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/delBijlage';
		xhr.data = data;
		
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				if( json.status == 'success' ){
					$tr.hide(700);
					
					setTimeout(function(){
						$tr.remove();
						invoerbijlages.checkTableIsEmpty();
					},1000);
			
				}
				else
					alert('Bestand kon niet worden verwijderd');
			});
		}
	},
	
	checkTableIsEmpty(){
		if( $('.table-vi-bijlages tbody').html() == '' ){
			$('.table-vi-bijlages-empty').show();
			$('.table-vi-bijlages-container').hide();
		}
	},
	
	//-- load files ----------------------------------------------------------------------------------------------------------------------------
	loadFiles(){
		
		$tabel = $('.table-vi-bijlages');
		$tabel.find('tbody').html('');
		
		xhr.url = base_url + 'ureninvoer/ajax/getBijlages';
		xhr.data = data;
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				
				if( Object.values(json.files).length > 0 ){
					
					$('.table-vi-bijlages-empty').hide();
					$('.table-vi-bijlages-container').show();
					
					for( let file of Object.values(json.files) ){
						let htmlTr = replaceVars(tplBijlageTr, file);
						$row = $(htmlTr).appendTo($tabel.find('tbody'));
					}
				}
				
			});
		}
	}
	
};
