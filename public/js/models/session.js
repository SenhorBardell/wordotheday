App.Models.Session = Backbone.Model.extend({
	defaults: {
		'access_token': null,
		'user_id': null
	},

	initialize: function() {
		this.load();
	},

	authenticated: function() {
		return Boolean(this.get('access_token'));
	},

	save: function(auth_hash) {
		this.set('user_id', auth_hash.id);
		this.set('access_token', auth_hash.access_token);
		// $.cookie('user_id', auth_hash.id);
		// $.cookie('access_token', auth_hash.access_token);
	},

	load: function() {
		this.set('user_id', $.cookie('user_id'));
		this.set('access_token', $.cookie('access_token'));
	},
	unload: function() {
		// $.cookie('user_id', '');
		// $.cookie('access_token', '');
		this.set('user_id', '');
		this.set('access_token', '');
	}
});