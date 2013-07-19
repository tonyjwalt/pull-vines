(function ( $ ) {
 
    $.fn.getVines = function( options ) {
        var self = this;
        var settings = $.extend({
            // These are the defaults.
            username: "",
            tweetNum: 0,
            cardWidth: 300,
            cardHeight: 300
        }, options );

        $.ajax('get-tweets.php', {
			type: "GET",
			data: "username="+settings.username+"&tweetNum="+settings.tweetNum+"&cardWidth="+settings.cardWidth+"&cardHeight="+settings.cardHeight,
			success: function (tweetlist) {
				self.html(tweetlist);
			},
			error: function () {
				self.html('There was an error fetching the tweets.');
			}
		});

        return this;
 
    };
 
}( jQuery ));