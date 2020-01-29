// ---------------------------------------------------------------------------------------------------------------------
//bing api calls
// ---------------------------------------------------------------------------------------------------------------------
let bing = {
	bind(){
		/*$('[data-bing="location"]').autocomplete({
			source:function(request, response){
				$.ajax({
					url:base_url + 'api/bing/suggestlocations',
					dataType:"json",
					data:{
						q:$('[data-bing="location"]').val()
					},
					success:function(data){
						response(data);
					}
				});
			},
			minLength:2,
			select:function(event, ui){
			}
		});*/
		
		$('[data-bing="location"]').autocomplete({
			source: base_url + 'api/bing/suggestlocations'
		});
	},
	

};

document.addEventListener('DOMContentLoaded', function(){
	bing.bind();
});