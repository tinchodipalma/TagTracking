(function ( $ ) {
    
    
    // Retrieve data from api
    $.fn.getSocialData = function () {

        var $this = this;

        var query = window.location.pathname.split('/').pop();

        var apiUrl = 'http://' + window.location.host + '/api/' + query;

        console.log("Busco: " + query);

        jQuery.ajax({
                url : apiUrl,
                async : false
            })
            .done(
                function(data) {
                    console.log("SI");
                    $this.displayStatuses($this, data);
                }
            )
            .fail(
                function(data) {
                    console.log("NO");
                    return false;
                }
            );
    };


    $.fn.displayStatuses = function (element, json) {

            var generatedHtml = ""; 

            json = $.parseJSON(json);

            $.each(json, 
                function (index, value) {
                    generatedHtml += "<div class='status'><div class='userPicture'><img src='" + value.user.picture + "'></div><div class='userName'>" + value.user.name + "</div><div class='message'>" + value.message + "</div></div>";
                }
                
            );

            element.html(generatedHtml);

    };


}( jQuery ));