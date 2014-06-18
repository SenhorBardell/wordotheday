App.Collections.ModeratedCarts = Backbone.Collection.extend({
    model: App.Models.ModeratedCart,
    url: '/api/moderate/words'
});