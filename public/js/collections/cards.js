App.Collections.Cards = Backbone.Collection.extend({
    model: App.Models.Card,
    url: '/api/words'
});