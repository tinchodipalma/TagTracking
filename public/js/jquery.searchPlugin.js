(function ( $ ) {
    
    
    // Retrieve data from api
    $.fn.getSocialData = function () {

        var $this = this;

        var query = window.location.pathname.split('/').pop();

        var apiUrl = 'http://' + window.location.host + '/api/' + query;

        $('.progressBar').fadeIn(500);

        console.log("Busco: " + query);

        jQuery.ajax({
                url : apiUrl,
                async : true,
                dataType : "json"
            })
            .done(
                function(data) {
                    console.log("SI");
                    $this.displayStatuses('#search', data);

                    setTimeout(function(){
                            $('#search').getSocialData();
                        },
                        59000
                    );

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

        var htmlCol1 = "";
        var htmlCol2 = ""; 

        // json = $.parseJSON(json);

        var i = 1;
        $.each(json, 
            function (index, value) {
                if (value !== "undefined") {
                    if (i == 1) {
                        htmlCol1 += $(this).getView(value);
                        i++;
                    } else {
                        htmlCol2 += $(this).getView(value);
                        i = 1;
                    }
                }

            }
            
        );

        $('.progressBar').fadeOut(500);
        $(element).html("<div class='col'>" + htmlCol1 + "</div><div class='col'>" + htmlCol2 + "</div><div class='clearBoth'></div>").fadeIn(1500);

    };

    $.fn.getView = function (json) {

        var htmlDOM = "<div class='status {{SOURCE}}'><div class='content'><div class='userPicture'><img src='{{USERPICTURE}}' alt='{{USERNAME}} photo' /></div><div class='statusInfo'><div class='user'><div class='fullName'>{{USERFULLNAME}}</div><div class='username'>{{USERNAME}}</div></div><div class='date'>{{DATE}}</div><div class='message'>{{MESSAGE}}</div></div></div></div>";

        var date = new Date(json.date*1000);

        var hours = (date.getHours() < 10 ? "0" : "") +  date.getHours();
        var minutes = (date.getMinutes() < 10 ? "0" : "") +  date.getMinutes();
        var seconds = (date.getSeconds() < 10 ? "0" : "") +  date.getSeconds();

        var dateString = hours + ":" + minutes + ":" + seconds;

        var query = window.location.pathname.split('/').pop();

        htmlDOM = htmlDOM.replace('{{SOURCE}}', json.source.toLowerCase());
        htmlDOM = htmlDOM.replace('{{USERPICTURE}}', json.user.picture);
        htmlDOM = htmlDOM.replace('{{USERFULLNAME}}', json.user.name);
        htmlDOM = htmlDOM.replace('{{USERNAME}}', json.user.username);
        htmlDOM = htmlDOM.replace('{{USERNAME}}', json.user.username);
        htmlDOM = htmlDOM.replace('{{MESSAGE}}', json.message);
        htmlDOM = htmlDOM.replace('{{DATE}}', dateString);
        htmlDOM = htmlDOM.replace(query, "<b>" + query + "</b>");
        htmlDOM = htmlDOM.replace("#<b>" + query + "</b>", "<span class='hashtag'>#" + query + "</span>");

        return htmlDOM;

    };

}( jQuery ));