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
                    $this.displayStatuses('#search', data);
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

            var htmlCol1,
                htmlCol2,
                htmlDOM = ""; 

            htmlDOM = "<div class='status'><div class='userPicture'><img style='width: 50px; height: 50px;' src='{USERPICTURE}'></div><div class='userName'>{USERNAME}</div><div class='message'>{MESSAGE}</div></div>";

            json = $.parseJSON(json);

            var i = 1;
            $.each(json, 
                function (index, value) {
                    if (i == 1) {
                        htmlCol1 += "<div class='status'><div class='userPicture'><img style='width: 50px; height: 50px;' src='" + value.user.picture + "'></div><div class='userName'>" + value.user.name + "</div><div class='message'>" + value.message + "</div></div>";
                        i++;
                    } else {
                        htmlCol2 += "<div class='status'><div class='userPicture'><img style='width: 50px; height: 50px;' src='" + value.user.picture + "'></div><div class='userName'>" + value.user.name + "</div><div class='message'>" + value.message + "</div></div>";
                        i = 1;
                    }


                }
                
            );

            $(element + ' .col:nth-child(1)').html(htmlCol1);
            $(element + ' .col:nth-child(2)').html(htmlCol2);

    };


}( jQuery ));