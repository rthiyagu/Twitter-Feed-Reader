// Tweets Collection
// initialize this variable as global
var TweetsList = '';

$(function() {
    TweetsList = Backbone.Collection.extend({
        model : Tweet,
        // configure the url which returns latest feeds
        url : '/get_feeds.php',
        // this method filters the tweets based on user input
        filterTweets : function(query) {
            return this.filter(function(tweet) {
                return tweet.get('text').toLowerCase().indexOf(query) != -1;
            });
        }
    });
});
