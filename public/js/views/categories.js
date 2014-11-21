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
		this.collection.comparator = function() {
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
		// ok = confirm('Вы действительно хотите удалить выбранную категорию?');
		// if (ok == true) {
			this.remove();
			this.unbind();
		// }
	}
});

App.Views.CategoriesDropdown = Backbone.View.extend({

	// tagName: 'select',
	// id: 'category_id',

	template: _.template([
		"<select id='category_id'>",
			"<% categories.each(function(category) { %>",
				"<%= categoryTemplate(category, cards) %>",
			"<% }); %>",
		"</select>"
	].join(',')),

	// categoryTemplate: _.template('<option id="<%= id %>"><%= name %></option>'),

	categoryTemplate: function(category, cards) {
		//if (category.get('id') == 211)
			return '<option selected value=' + category.get('id') + '>' + category.get('name') + '</option>';
		//else
		//	return '<option value=' + category.get('id') + '>' + category.get('name') + '</option>';
	},

	initialize: function(options) {
		this.options = options;
		this.collection.on('add', this.addOne, this);
		this.collection.comparator = function() {
			return -1;
		}
	},

	render: function() {

		var html = this.template({
			categories: this.collection,
			categoryTemplate: this.categoryTemplate,
			cards: this.options.cards
		});

		this.$el.append(html);

		return this;
	}

});

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
		var ok = confirm('Вы действительно хотите удалить выбранную категорию?');
		if (ok == true) this.model.destroy();
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
		this.ua_parameter = $('#ua_parameter');
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
			test_price: this.test_price.val(),
			ua_parameter: this.ua_parameter.val()
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
		this.ua_parameter.val('');

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
		this.ua_parameter = this.form.find('#edit_ua_parameter');
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
			test_price: this.test_price.val(),
			ua_parameter: this.ua_parameter.val()
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