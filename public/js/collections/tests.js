App.Collections.Tests = Backbone.Collection.extend({
	model: App.Models.Test,
	url: '/api/tests'
});