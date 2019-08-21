// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------
//for checking if a ajax request is pending
const ENV = 'development';

//for checking if a ajax request is pending
var ajaxRequestPending = false;

//data object for sending
//const base_url = 'http://192.168.1.2/app';
const base_url = document.baseURI;

//data object for sending
var data = {};

//ajax events
$( document ).ajaxStart(function() {
    log( '--AJAX REQUEST START--' );
    ajaxRequestPending = true;
});
$( document ).ajaxStop(function() {
    log( '--AJAX REQUEST END--' );
});

//log function
function log(message) {
    if ( ENV === 'development' ) {
        console.log(message);
        ajaxRequestPending = false;
    }
}


//ajax object
let xhr = {
    url: null,
    data: data,
    call() {
        //one call at a time
        if( ajaxRequestPending === false ){
            return $.ajax( {
                url: this.url,
                data: this.data,
                dataType: 'json',
                method: 'POST'
            }).fail( function( request, status, error ) {
                //error to console
                //TODO: production error handling
                console.warn( '--AJAX ERROR--' );
                log( request );
                log( status );
                log( error );

                var errorData = {};

                errorData.url = this.url;
                errorData.module = 'verloning invoer';
                errorData.data = data;
                errorData.statusText = request.statusText;
                errorData.responseText = request.responseText;

                //save data to log file
                $.ajax( {
                    url: base_url + 'log/ajaxerror',
                    data: errorData,
                    dataType: 'json',
                    method: 'POST'
                });

            }).done( function( json ) {
                log( '--AJAX REQUEST DONE--' );
            });
        }else
        {
            alert('Even geduld a.u.b.');
            return false;
        }
    }
};

//nvoer main object
let invoer = {

    //tijdvak setter
    setTijdvak(tijdvak) {
        data.tijdvak = tijdvak
    },

    //jaar setter
    setJaar: jaar => data.jaar = jaar,
    //periode setter
    setPeriode: periode => data.periode = periode,

    //events aan dom binden
    events() {
        //$(document).on('click', '[data-vi-action="click"]', () =>  invoer.getInleners() );
        $(document).on('click', '[data-vi-action="setTijdvak"]', function(){invoer.setTijdvak( $(this).data('value') ) });

        //focus op de table row wanneer een veld actief word
        $(document).on('focus', '.table-vi-uren input', function(){ $('.table-vi-uren tr').removeClass('focus'); $(this).closest('tr').addClass('focus'); });
        $(document).on('click', '.table-vi-uren select', function(){ $('.table-vi-uren tr').removeClass('focus'); $(this).closest('tr').addClass('focus'); });

        //tab change triggers textfit
        //TODO verplaatsen naar laden van werknemer gegevens
        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            var el = document.getElementsByClassName('fit-text');
            //fittext voor werknemer naam, alleen als element breder is dan 0
            if( el[0].clientWidth > 0)
             textFit(document.getElementsByClassName('fit-text'),{ maxFontSize:14});
        })
    },

    //ajax get Inleners
    getInleners() {
        xhr.url = base_url + 'test/ajax';
        xhr.data = data;

        var response = xhr.call();
        if( response !== false ){
            response.done( function( json ) {
                var html = '';
                json.forEach(function(element, index) {
                    var element = tplInlenersLi.replace( '{inlener}', element );
                    var element = element.replace( '{key}', index );
                    html += element;
                });
                $(html).appendTo('.vi-list-inleners');
                log(html);
            });
        }
        //make button
        /*
        $('<button />', {
            type: 'text',
            name: 'test',
            class: 'btn btn-danger',
            html: 'Klik nog een keer',
            'data-v-action': 'click'
        }).appendTo(".append-button");*/
    },

    //properties aanmaken
    init() {
        //properties
        data.tijdvak = null;
        data.jaar = null;
        data.periode = null;
    }
};


document.addEventListener( 'DOMContentLoaded', function() {
    invoer.init();
    invoer.events();

    invoer.setTijdvak( 'w' );
    invoer.setJaar( 2019 );
    invoer.setPeriode( 30 );

    log( data );

} );