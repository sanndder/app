// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------
//nvoer main object
let plaatsing = {
    new(){
        data.werknemer_id = 1400;

        cao.setCaoID(75);
        cao.setLeeftijd(25);
        cao.setLoontabelID(8);
        cao.setFunctieID(12794);
        cao.setSchaalID('1b');
        cao.setPeriodiekID('1.00');

        response = cao.getCaoData();
        if( response !== false ){
            response.done( function( json ) {
                log(json);
            });
        }
    }
};

document.addEventListener( 'DOMContentLoaded', function() {
    plaatsing.new();
});