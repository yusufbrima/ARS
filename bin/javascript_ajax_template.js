function ajax_call(){
            var simpleXmlHttp = new XMLHttpRequest();
            var response =  document.getElementById('result');
            var inputJobTitle =  document.getElementById('inputNaturalLanguageSearch').value;
            var inputQualification =  document.getElementById('inputQualification').value;
            var inputCareerField =  document.getElementById('inputCareerField').value;
            simpleXmlHttp.open('GET','include/ajax_job_search.php?inputNaturalLanguageSearch='+inputNaturalLanguageSearch+'&inputQualification='+inputQualification+'&inputCareerField='+inputCareerField,true);
            simpleXmlHttp.send();
            simpleXmlHttp.onreadystatechange =function(){
              if(simpleXmlHttp.readyState==4 && simpleXmlHttp.status==200){
                $('#all_jobs').css('visibility','hidden');
                response.innerHTML = simpleXmlHttp.responseText;
              }
            }
            //alert(simpleXmlHttp);
          }