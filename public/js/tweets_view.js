// Tweets View
// initialize these variables as global
var TweetItemView = '';
var TweetNoItemView = '';
var TweetsListView = '';
const TWEET_REFRESH_CYCLE = 60000;

$(function() {
    // view for each feed item
    // configure the tags to be used
    // configure the template id
    TweetItemView = Backbone.View.extend({
        tagName : "li",
        template : _.template($('#feed-item-template').html()),
        initialize : function() {
            _.bindAll(this, 'render');
        },
        render : function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    // view for no tweets found while searching
    // configure the tags to be used
    // configure the template id
    TweetNoItemView = Backbone.View.extend({
        tagName : "li",
        template : _.template($('#no-feed-template').html()),
        initialize : function() {
            _.bindAll(this, 'render');
        },
        render : function() {
            this.$el.html(this.template());
            return this;
        }
    });

    // view for Tweets List
    TweetsListView = Backbone.View.extend({
        el : $("#main"),
        // this event is added to support filter functionality
        events : {
            "keyup #filter_tweet" : "filterTweetsList"
        },
        initialize : function() {
            // bind all the methods where this is used
            _.bindAll(this, 'addItem', 'render', 'filterTweetsList');
            this.input = this.$("#filter_tweet");
            this.update_notification = this.$(".update_notification");
            this.tweets = new TweetsList();
            // On any update to this.tweets collection, the View will be rendered
            this.tweets.bind('update', this.render);
            // update the tweets collection
            // this will trigger the update event
            this.tweets.fetch();
            that = this;
            // update the tweets collection for every 60 seconds
            setInterval(function() {
                that.update_notification.show();
                that.tweets.fetch();
            }, TWEET_REFRESH_CYCLE);
        },
        addItem : function(tweet) {
            var view = new TweetItemView({
                model : tweet
            });
            this.$("#tweets_list").append(view.render().el);
        },
        render : function() {
            this.update_notification.hide();
            this.$("#tweets_list").empty();
            that = this;
            this.tweets.each(function(tweet) {
                that.addItem(tweet);
            });
        },
        filterTweetsList : function(e) {
            if (!this.input.val()) {
                return;
            }
            var query = this.input.val();
            // get the filtered list by passing user input to filterTweets method
            var filteredList = this.tweets.filterTweets(query.toLowerCase());
            // clear the current list
            this.$("#tweets_list").empty();
            that = this;
            if (filteredList.length > 0) {
                // update the view with filtered list
                _(filteredList).each(function(tweet) {
                    that.addItem(tweet);
                });
            } else {
                // update the view with No tweets found message
                var view = new TweetNoItemView();
                this.$("#tweets_list").append(view.render().el);
            }
        }
    });

    var tweetsListView = new TweetsListView;
});
