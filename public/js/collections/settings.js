App.Collections.Settings = Backbone.Collection.extend({
	model: App.Models.Settings,
	url: 'api/settings'
});