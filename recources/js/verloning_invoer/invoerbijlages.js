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
		$(document).on('fileuploaded', '#upload-bijlages', function(event, previewId, index, fileId){
			$('#upload-bijlages').fileinput('clear');
		});
	},
	
	buildBijlagesTab()
	{
		//data toevoegen aan uploader
		$('#upload-bijlages').fileinput('refresh', {uploadExtraData: data});
		
		//bestanden ophalen
		invoerbijlages.loadFiles();
	},
	
	//-- load files ----------------------------------------------------------------------------------------------------------------------------
	loadFiles(){

		$tabel = $('.table-vi-bijlages');
		
		xhr.url = base_url + 'ureninvoer/ajax/getBijlages';
		xhr.data = data;
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
			
				for( let file of Object.values(json.files) )
				{
					let htmlTr = replaceVars(tplBijlageTr, file);
					$row = $(htmlTr).appendTo($tabel.find('tbody'));
				}
				
			});
		}
	}
	
};
