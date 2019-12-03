// ---------------------------------------------------------------------------------------------------------------------
// cao data module
// ---------------------------------------------------------------------------------------------------------------------
let cao = {
     //set cao ID
    setCaoID: cao_id => data.cao_id = cao_id,
    setLeeftijd: leeftijd => data.leeftijd = leeftijd,
    setLoontabelID: loontabel_id => data.loontabel_id = loontabel_id,
    setFunctieID: job_id => data.job_id = job_id,
    setSchaalID: schaal_id => data.schaal_id = schaal_id,
    setPeriodiekID: periodiek_id => data.periodiek_id = periodiek_id,

    getCaoData(){
        xhr.url = base_url + 'cao/ajax/caodata';
        xhr.data = data;

        return xhr.call();
    }
};