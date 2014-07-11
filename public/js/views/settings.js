App.Views.SettingsApp = Backbone.View.extend({

	initialize: function() {
		console.log(this.collection.models[0]);
		var Settings = new App.Views.Settings({ model: this.collection.models[0] }).render();
		$('.content').empty().append('<h2>Настройки</h2>').append(Settings.el);
	}
});

App.Views.Settings = Backbone.View.extend({
	template: window.App.JST['settings'],

	render: function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	},

	events: {
		'submit': 'submit',
		'click .close': 'cancel'
	},

	submit: function(e) {
		e.preventDefault();

		this.model.save({
			answer_time: $('#answer_time').val(),
			daily_bonus: $('#daily_bonus').val(),
			general_cost: $('#general_cost').val(),
			life_cost: $('#life_cost').val(),
			top_bonus: $('#top_bonus').val(),
			word_cost: $('#word_cost').val(),
			words_for_the_next_bonus: $('#words_for_the_next_bonus').val()
		}, {wait: true});
		$('<span> Сохранено</span>').insertAfter('.pure-button');
	}
});