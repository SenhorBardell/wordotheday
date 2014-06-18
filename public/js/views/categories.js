App.Views.CategoriesApp = Backbone.View.extend({

	initialize: function() {
		vent.on('category:edit', this.editCategory, this);

		var AllCategoriesView = new App.Views.Categories({ collection: App.categories }).render();
		$('.content').empty().append(window.App.JST['category/layout']);
			// .append('<h2 class="content-header">Категории<a href="/category/add">+</a></h2>')
			// .append('<table><thead><tr><td>ID</td><td>Name</td><td>Words</td><td>Edit</td><td>Words</td></tr></thead>')
		AllCategoriesView.$el.insertAfter('.content thead');	
		// $('.content thead').append(AllCategoriesView.el).append('</table>');
	},

	editCategory: function(category) {
		var editCategoryView = new App.Views.EditCategory({ model: category });
		editCategoryView.$el.insertAfter('.content-header');
	},

	onClose: function() {
		vent.off('category:edit', this.editCard, this);
	}
});

App.Views.Categories = Backbone.View.extend({
	tagName: 'tbody',

	initialize: function() {
		this.collection.on('add', this.addOne, this);
		this.collection.comparator = function(category) {
			return -1;
		}
	},

	render: function() {
		this.collection.each(this.addOne, this);
		return this;
	},

	addOne: function(category) {
		var categoryView = new App.Views.Category({ model: category });
		this.$el.prepend(categoryView.render().el);
	},

	remove: function() {
		this.remove();
		this.unbind();
	}
});

App.Views.CategoriesDropdown = Backbone.View.extend({

	tagName: 'select',
	id: 'category_id',

	initialize: function() {
		this.collection.on('add', this.addOne, this);
		this.collection.comparator = function(category) {
			return -1;
		}
	},

	render: function() {
		this.$el.attr('name', 'category_id');
		this.collection.each(this.addOne, this);
		return this;
	},

	addOne: function(category) {
		var categoryDropdown = new App.Views.CategoryDropdown({ model: category });
		this.$el.prepend(categoryDropdown.render().el);
	}
});

App.Views.CategoryDropdown = Backbone.View.extend({
	tagName: 'option',


	template: _.template('<%= name %>'),

	render: function() {
		this.$el.attr('value', this.model.get('id'));
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
})

App.Views.Category = Backbone.View.extend({
	tagName: 'tr',

	template: window.App.JST['category/single'],

	initialize: function() {
		this.model.on('destroy', this.unrender, this);
		this.model.on('change', this.render, this);
	},

	events: {
		'click a.delete': 'deleteCategory',
		'click a.edit': 'editCategory'
	},

	editCategory: function() {
		vent.trigger('category:edit', this.model);
	},

	deleteCategory: function() {
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


App.Views.AddCategory = Backbone.View.extend({

	initialize: function() {
		this.render();
		this.$el.insertAfter('.content-header');

		this.name = $('#name');
		this.subscription_price = $('#subscription_price');
		this.test_price = $('#test_price');
	},

	events: {
		submit: 'addCategory',
		'click .close': 'close'
	},

	addCategory: function(e) {
		e.preventDefault(e);

		this.collection.create({
			name: this.name.val(),
			subscription_price: this.subscription_price.val(),
			test_price: this.test_price.val()
		}, {wait: true });

		this.clearForm();
	},

	render: function() {
		var html = window.App.JST['category/add'];
		this.$el.html(html);
		return this;
	},

	close: function(e) {
		e.preventDefault(e);
		this.remove();
	},

	clearForm: function() {
		this.name.val('');
		this.subscription_price.val('');
		this.test_price.val('');

	}
});

App.Views.EditCategory = Backbone.View.extend({
	template: window.App.JST['category/edit'],

	initialize: function() {
		this.render();

		this.form = this.$('#editCategory');
		this.name = this.form.find('#edit_name');
		this.subscription_price = this.form.find('#edit_subscription_price');
		this.test_price = this.form.find('#edit_test_price');
	},

	events: {
		'submit form': 'submit',
		'click .close': 'cancel'
	},

	submit: function(e) {
		e.preventDefault();

		this.model.save({
			name: this.name.val(),
			subscription_price: this.subscription_price.val(),
			test_price: this.test_price.val()
		}, {wait: true});

		this.remove();
	},

	render: function() {
		var html = this.template(this.model.toJSON());
		this.$el.html(html);
		return this;
	},

	cancel: function(e) {
		e.preventDefault();
		this.remove();
	}
});