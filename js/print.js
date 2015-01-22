(function($, document, window) {
    $('body').prepend('<a href="#" class="2pdf">Imprimer</a>');
    $('body').append('<a href="#"  class="2pdf">Imprimer</a>');
    
    $('a.2pdf').click(function() {
        
        var serviceUrl;
        var convertParameter;

                    serviceUrl = "http://do.convertapi.com/Web2Pdf/json?";
                    
                convertParameter = "storefile=false&OutputFileName=Commande&PageNo=true";
                convertParameter = convertParameter  + "&curl=" + encodeURI($('body').data('url'));
                convertParameter = convertParameter  + "&callback=?"

alert(serviceUrl + convertParameter)
//return false;
                jQuery.getJSON(serviceUrl + convertParameter , function(data){
                    if (data.Result == true) {
/*                        document.getElementById("LabelMessage").innerHTML = '';
                        document.getElementById("HyperLinkFile").href = data.FileUrl;
                        document.getElementById("HyperLinkFile").innerHTML = data.OutputFileName + " (" + data.FileSize +" bytes)";*/
                        alert('ok')
                    } else {
                        alert('err' + data.Error)
                        //document.getElementById("LabelMessage").innerHTML = "Error:<br />" + data.Error;
                    }
                });
                
        
    })
    
})(jQuery, document, window);