App.Collections.Categories = Backbone.Collection.extend({
	model: App.Models.Category,
	url: '/api/categories'
});