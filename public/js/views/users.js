App.Views.UsersApp = Backbone.View.extend({

	initialize: function() {
		vent.on('user:edit', this.editUser, this);

		var AllUsersView = new App.Views.Users({ collection: App.users }).render();
		$('.content').empty().append(window.App.JST['user/layout']);
		AllUsersView.$el.insertAfter('.content thead');
		// $('.content').empty().append('<h2>Пользователи(Coming next)</h2>').append(AllUsersView.el);
	},

	editUser: function(user) {
		var editUser = new App.Views.EditUser({ model: user });
		editUser.$el.insertAfter('.content-header');
	},

	onClose: function() {
		vent.off('user:edit', this.editUser, this);
	}
});

App.Views.Users = Backbone.View.extend({

	tagName: 'tbody',

	initialize: function() {
		this.collection.on('add', this.addOne, this);
	},

	render: function() {
		this.collection.each(this.addOne, this);
		return this;
	},

	addOne: function(user) {
		var userView = new App.Views.User({ model: user });
		this.$el.append(userView.render().el);
	}
});

App.Views.User = Backbone.View.extend({

	tagName: 'tr',

	template: window.App.JST['user/single'],

	initialize: function() {
		this.model.on('destroy', this.unrender, this);
		this.model.on('change', this.render, this);
	},

	events: {
		'click a.delete': 'deleteUser',
		'click a.edit': 'editUser'
	},

	editUser: function() {
		vent.trigger('user:edit', this.model);
	},

	deleteUser: function() {
		this.model.destroy();
	},

	unrender: function() {
		this.remove();
	},

	render: function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

App.Views.AddUser = Backbone.View.extend({
	initialize: function() {
		this.render();
		this.$el.insertAfter('.content-header');

		this.username = $('#username');
		this.password = $('#password');
	},

	events: {
		submit: 'addUser',
		'click .close': 'close'
	},

	addUser: function(e) {
		e.preventDefault(e);

		this.collection.create({
			username: this.username.val(),
			password: this.password.val(),
		}, {wait: true });

		this.clearForm();
	},

	render: function() {
		var html = window.App.JST['user/add'];
		this.$el.html(html);
		return this;
	}, 

	close: function(e) {
		e.preventDefault();
		this.remove();
	},

	clearForm: function() {
		this.username.val('');
		this.password.val('');
	}
});

App.Views.EditUser = Backbone.View.extend({
	template: window.App.JST['user/edit'],

	initialize: function() {
		this.render();

		this.form = this.$('#editUser');
		console.log(this.form.find('#edit_username'));
		this.username = this.form.find('#edit_username');
		this.password = this.form.find('#edit_password');
		this.balance = this.form.find('#edit_balance');
		this.max_result = this.form.find('#edit_max_result');
		this.overal_standing = this.form.find('#edit_overal_standing');
	},

	events: {
		'submit form': 'submit',
		'click .close': 'cancel'
	},

	submit: function(e) {
		e.preventDefault();

		this.model.save({
			username: this.username.val(),
			password: this.password.val(),
			balance: this.balance.val(),
			max_result: this.max_result.val(),
			overal_standing: this.overal_standing.val()
		}, {wait: true});

		this.remove();
	},

	render: function() {
		var html = this.template(this.model.toJSON());
		console.log(html);
		this.$el.html(html);
		return this;
	},

	cancel: function(e) {
		e.preventDefault();
		this.remove();
	}
});