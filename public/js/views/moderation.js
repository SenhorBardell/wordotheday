App.Views.ModerationApp = Backbone.View.extend({

	initialize: function(options) {
		this.options = options;
		var AllModerationWordsView = new App.Views.ModerationWords({ collection: App.moderation, categories: this.options.categories }).render();
	
		$('.content').empty().append(window.App.JST['moderation/layout']);
		AllModerationWordsView.$el.insertAfter('.content thead');

		vent.on('card:edit', this.editCard, this);
	},

	editCard: function(card) {
		var editCardView = new App.Views.EditMCard({ model: card });
		editCardView.$el.insertAfter('.content-header');
		var AllCategoriesViewDropDown = new App.Views.CategoriesDropdown({ collection: App.categories }).render();
		AllCategoriesViewDropDown.$el.insertAfter($('#cat_id_label'));
	}, 

	onClose: function() {
		vent.off('card:edit', this.editCard, this);
	}

});

App.Views.ModerationWords = Backbone.View.extend({
	tagName: 'tbody',

	initialize: function(options) {
		this.options = options;
		this.collection.on('add', this.addOne, this);
		this.collection.comparator = function(word) {
			return -1;
		}
	},

	render: function() {
		this.collection.each(this.addOne, this);
		return this;
	},

	addOne: function(word) {
		var moderatedWordsView = new App.Views.ModerationWord({ model: word, categories: this.options.categories });
		this.$el.prepend(moderatedWordsView.render().el);
	},

	remove: function() {
		this.remove();
	}
});

App.Views.ModerationWord = Backbone.View.extend({
	tagName: 'tr',

	template: window.App.JST['moderation/single'],

	initialize: function(options) {
		this.options = options;
		this.model.on('destroy', this.unrender, this);
		this.model.on('change', this.render, this);
	},

	events: {
		'click a.accept': 'accept',
		'click a.reject': 'reject',
		'click a.edit': 'edit'
	},

	accept: function(e) {
		var id = this.model.get('id');
		var category = $(e.target).parent().parent().parent().find('#category_id').val();

		var that = this
		$.ajax({
			type: 'POST',
			url: '/api/moderate/words/'+ id + '/changestatus',
			data: { category: category, status: 'accepted'}
		}).done(function() {
			// that.model.fetch();
			that.remove();
		}).fail(function() {
			console.log('Failed aceppting the word');
		});
	},

	edit: function() {
		vent.trigger('card:edit', this.model);
	},

	reject: function() {
		this.model.destroy();
	},

	render: function() {
		//console.log(this.options.categories.toJSON());
		this.$el.html(this.template({ card: this.model.toJSON(), categories: this.options.categories.toJSON()}));
		return this;
	},

	unrender: function() {
		this.remove();
	}
});