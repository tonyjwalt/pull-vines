(function ( $ ) {
 
  $.fn.getVines = function( options ) {
    var self = this,
      settings = $.extend({
      // These are the defaults.
      searchType: 'user', // use 'user' or 'term' to define search type based on user or term
      username: '', // name of user's timeline to pull
      searchTerm: '', // term to search on
      tweetNum: 0, // tweets to pull - check when I have a net connection, but I believe zero is all
      cardWidth: 300, // width of the vine cards
      cardHeight: 300  // height of the vine cards
    }, options );

    var term = (settings.searchType == 'user') ? settings.username : settings.searchTerm;

    $.ajax('get-tweets.php', {
      type: "GET",
      data: "searchType="+settings.searchType+"&term="+term+"&tweetNum="+settings.tweetNum+"&cardWidth="+settings.cardWidth+"&cardHeight="+settings.cardHeight,
      success: function (tweetlist) {
        self.html(tweetlist);
      },
      error: function (e) {
        var msg = 'There was an error fetching the tweets.';
        //may move to a switch statement to accomidate more error types        
        if (e.status == 404) {
          msg += ' The script responsible for gathering the tweets could not be located.'
        }
        self.html(msg);
      }
    });

    return this;
 
  };
 
}( jQuery ));