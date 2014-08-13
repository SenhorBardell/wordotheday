App.Views.CategoryCardsApp = Backbone.View.extend({

	initialize: function(options) {
		this.options = options || {};
		vent.on('card:edit', this.editCard, this);

		$('.content').empty().append(window.App.JST['card/layout']);

		var AllCardsView = new App.Views.Cards({ collection: App.Cards, category_id: this.options.category_id }).render();
		AllCardsView.$el.insertAfter('.content thead');
			// .append('<h2 class="content-header"><a href="/categories">&larr;</a> Карточки слов<a href="category/' + this.options.category_id + '/word/add">+</a></h2>')
			// .append(AllCardsView.el);
	},

	editCard: function(card) {
		var editCardView = new App.Views.EditCard({ model: card });
		editCardView.$el.insertAfter('.content-header');
		var AllCategoriesViewDropdown = new App.Views.CategoriesDropdown({ collection: App.categories}).render();
		AllCategoriesViewDropdown.$el.insertAfter($('#cat_id_label'));
	},

	onClose: function() {
		vent.off('card:edit', this.editCard, this);
	}
});

App.Views.CardsApp = Backbone.View.extend({

	initialize: function(options) {

		this.options = options || {};

		vent.on('card:edit', this.editCard, this);

		var AllCardsView = new App.Views.Cards({ collection: App.cards }).render();
		$('.content').empty().append(window.App.JST['card/layout'](this.options));
		AllCardsView.$el.insertAfter('.content thead');
		// $('.content')
			// .append('<h2 class="content-header"><a href="/categories" class="back">&larr;</a>Карточки слов<a href="word/add">+</a></h2>')
			// .append(AllCardsView.el);
	},

	editCard: function(card) {
		var editCardView = new App.Views.EditCard({ model: card });
		editCardView.$el.insertAfter('.content-header');
		var AllCategoriesViewDropdown = new App.Views.CategoriesDropdown({ collection: App.categories}).render();
		AllCategoriesViewDropdown.$el.insertAfter($('#cat_id_label'));

		$('#category_id').val(card.get('category_id'));
	},

	onClose: function() {
		vent.off('card:edit', this.editCard, this);
	}
});

App.Views.Cards = Backbone.View.extend({
	tagName: 'tbody',

	initialize: function() {
		this.collection.on('add', this.addOne, this);
		this.collection.comparator = function(word) {
			return -1;
		}
	},

	render: function() {
		this.collection.each(this.addOne, this);
		return this;
	},

	addOne: function(card) {
		var cardView = new App.Views.Card({ model: card });
		this.$el.prepend(cardView.render().el);
	}
});

App.Views.Card = Backbone.View.extend({
	tagName: 'tr',

	template: window.App.JST['card/all'],

	initialize: function() {
		this.model.on('destroy', this.unrender, this);
		this.model.on('change', this.render, this);
	},

	events: {
		'click a.delete': 'deleteCard',
		'click a.edit': 'editCard'
	},

	editCard: function() {
		vent.trigger('card:edit', this.model);
	},

	deleteCard: function() {
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

App.Views.AddCard = Backbone.View.extend({

	initialize: function(options) {
		this.options = options
		this.render();
		this.$el.insertAfter('.content-header');

		var AllCategoriesViewDropdown = new App.Views.CategoriesDropdown({ collection: App.categories}).render();
		AllCategoriesViewDropdown.$el.insertAfter($('#cat_id_label'))
		$('#category_id').val(this.options.category_id);

		this.word = $('#word');
		this.answer = $('#answer');
		this.category_id = $('#category_id');
	},

	events: {
		'submit': 'submit',
		'click .close': 'cancel',
	},

	submit: function(e) {
		e.preventDefault();

		if (this.word.val().length <= 125) {
			this.collection.create({
				word: this.word.val(),
				answer: this.answer.val(),
				category_id: this.category_id.val()
			}, { wait: true });

			this.clearForm();
		}
	},

	render: function() {
		var html = window.App.JST['card/add'];
		this.$el.html(html);
		return this;
	},

	cancel: function(e) {
		e.preventDefault(e);
		this.remove();
		history.back();
	},

	clearForm: function() {
		this.word.val('');
		this.answer.val('');
	}
});

App.Views.EditMCard = Backbone.View.extend({
	template: window.App.JST['card/medit'],

	initialize: function() {
		this.render();

		this.word = $('#word');
		this.answer = $('#answer');
		this.category_id = $('#category_id');

		this.model.on('destroy', this.unrender, this);
	},

	events: {
		'submit form': 'submit',
		'click .reject2': 'rejectword'
	},

	submit: function(e) {
		e.preventDefault();

		id = this.model.get('id');

		that = this
		$.ajax({
			type: 'POST',
			url: '/api/moderate/words/'+ id + '/acceptpush',
			data: { 
				word: $('#edit_word').val(),
				answer: $('#edit_answer').val(),
				category_id: $('#category_id').val()
			}
		}).done(function() {
			console.log('Done');
			that.remove();
			that.model.destroy();
		}).fail(function() {
			console.log('Failed aceppting the word');
		});
	},

	rejectword: function() {
		console.log('remove');
		this.remove();
		this.model.destroy();
	},

	unrender: function() {
		this.remove();
	},

	render: function() {
		var html = this.template(this.model.toJSON());
		this.$el.html(html);
		return this;
	}
});

App.Views.EditCard = Backbone.View.extend({
	template: window.App.JST['card/edit'],

	initialize: function() {
		this.render();

		// AllCategoriesViewDropdown.$el.attr('id', 'edit_category_id');
		// AllCategoriesViewDropdown.$el.insertAfter($('#editWordForm'));

		this.model.on('destroy', this.unrender, this);
	},

	events: {
		'submit form': 'submit',
		'click .close': 'cancel'
	},

	submit: function(e) {
		e.preventDefault();

		this.model.save({
			word: $('#edit_word').val(),
			answer: $('#edit_answer').val(),
			category_id: $('#category_id').val()
		}, {wait: true});

		this.unbind()
		this.remove();
	},

	cancel: function() {
		this.unbind();
		this.remove();
	},

	unrender: function() {
		this.remove();
	},

	render: function() {
		var html = this.template(this.model.toJSON());
		this.$el.html(html);
		return this;
	}
});