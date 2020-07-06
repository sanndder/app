// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoeraangenomenwerk = {
	//properties aanmaken
	init()
	{
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events()
	{
		
		//project titel wel/niet meegeven
		$(document).on('change', '.vi-input-no-project', function()
		{
			if( $(this).prop('checked') )
			{
				data.use_project = 0;
				$('.vi-input-project').prop('disabled', true);
			}
			else
			{
				data.use_project = 1;
				$('.vi-input-project').prop('disabled', false);
			}
			
			invoeraangenomenwerk.saveProjectTitel();
		});
		
		//opmerking instellen
		$(document).on('keyup', '.vi-input-project', $.debounce(750, function(e)
		{
			invoeraangenomenwerk.saveProjectTitel(this);
		}));
		
		//opslaan
		$(document).on('click', '[data-vi-action="setAangenomenwerkProjectData"]', function()
		{
			invoeraangenomenwerk.saveAangenomenwerkProjectData(this);
		});
		
	},
	
	buildAangenomenwerkTab()
	{
		$('.vi-aangenomenwerk-body').html('');
		
		xhr.url = base_url + 'ureninvoer/ajax/getAangenomenwerkData';
		xhr.data = data;
		
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				//er gata iets mis
				if( json.status == 'error' )
				{
					alert('Fout bij laden aangenomenwerk invoer');
				}
				//success
				else
				{
					log(json);
					for( let project of Object.values(json) )
					{
						let htmlProject = tplAangenomenwerkLegend;
						htmlProject = htmlProject.replace('{project}', project.project);
						htmlProject = htmlProject.replace('{project_id}', project.project_id);
						
						$project = $(htmlProject).appendTo($('.vi-aangenomenwerk-body'));
						
						//leeg
						if( project.rijen.length === 0 )
						{
							let htmlTr = tplAangenomenwerkInvoerTr;
							htmlTr = htmlTr.replace('{invoer_id}', '');
							htmlTr = htmlTr.replace('{omschrijving}', '');
							htmlTr = htmlTr.replace('{bedrag}', '');
							$tr = $(htmlTr).appendTo( $project.find('tbody') );
						}
						else
						{
							for( let rij of Object.values(project.rijen) )
							{
								let htmlTr = tplAangenomenwerkInvoerTr;
								htmlTr = htmlTr.replace('{invoer_id}', rij.invoer_id);
								htmlTr = htmlTr.replace('{omschrijving}', rij.omschrijving);
								htmlTr = htmlTr.replace('{bedrag}', parseFloat(rij.bedrag).toFixed(2).replace('.',',') );
								$tr = $(htmlTr).appendTo( $project.find('tbody') );
							}
						}
					}
					
				}
				
			});
			
		}
		
	},
	
	// project opslaan
	saveAangenomenwerkProjectData( obj )
	{
		$input = $(obj);
		
		$i = $input.find('i');
		$i.removeClass('icon-checkmark2').addClass('spinner icon-spinner3');
		
		$fieldset = $input.closest('fieldset');
		$tr = $input.closest('tr');
		
		data.project_id = $fieldset.data('id');
		data.invoer_id = $tr.data('id');
		data.omschrijving = $tr.find('[name="omschrijving"]').val();
		data.bedrag = $tr.find('[name="bedrag"]').val();
		
		xhr.url = base_url + 'ureninvoer/ajax/saveAangenomenwerkProjectData';
		xhr.data = data;
		
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				//er gata iets mis
				if( json.status == 'error' )
				{
					alert('Fout bij opslaan aangenomenwerk invoer');
				}
				//success
				else
				{
					$fieldset.data('id', json.invoer_id );
				}
				
				$i.removeClass('spinner icon-spinner3').addClass('icon-checkmark2');
				
			});
			
		}
		
	},
	
	// project opslaan bij factuur
	saveProjectTitel()
	{
		
		data.project_titel = $('.vi-input-project').val();
		
		xhr.url = base_url + 'ureninvoer/ajax/saveAangenomenwerkProject';
		xhr.data = data;
		
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				//er gata iets mis
				if( json.status == 'error' )
				{
				}
				//success
				else
				{
				}
			});
			
		}
		
	},
	
};
