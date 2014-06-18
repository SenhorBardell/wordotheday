App.Router = Backbone.Router.extend({
	initialize: function() {
		App.session = new App.Models.Session;
		// $('#menu a').click({router: this, }, navigate);

		// function navigate(e) {
		// 	e.data.router.navigate($(this).attr('href'));
		// }
		
	},

	routes: {
		'': 'categories',
		'api/usage':'index',
		'users': 'users',
		'user/add': 'addUser',
		// 'words': 'words',
		// 'word/add': 'addWord',
		'categories': 'categories',
		'category/add': 'addCategory',
		'category/:category_id/word/add': 'addCategoryWord',
		'category/:category_id/words': 'categoryWords',
		'moderation': 'moderation',
		'login': 'login',
		'logout': 'logout',
		'settings': 'settings',
	},

	settings: function() {
		$('#layout').empty();
		if(App.current && App.current.close) App.current.close();
		if (App.session.authenticated()) {
			$('#layout').append(window.App.JST['layout']);
			App.settings = new App.Collections.Settings;
			App.settings.fetch().then(function() {
				App.current = new App.Views.SettingsApp({ collection: App.settings });
				console.log(App.settings.toJSON());
			});
		} else {
			this.navigate('/login', {trigger: true});
		}
	},

	index: function() {
		$('#layout').empty();
		if(App.current && App.current.close) App.current.close();
		if (App.session.authenticated()) {
			$('#layout').append(window.App.JST['layout']);
			$('.content').append('<h2>Функции апи</h2>');
			$('.content').append(window.App.JST['api']);
		} else {
			this.navigate('/login', {trigger: true});
		}
	},

	login: function() {
		$('#layout').empty().append(window.App.JST['auth']);
		
		$('#log-in').click({router: this}, check);

		function check(e) {
			e.preventDefault(e);
			e.data.router.navigate('/words');
			if (App.session.authenticated()) {
				e.data.router.navigate('/words', {trigger: true});
			} else {
				App.session.save({
					'user_id': $('#user').val(),
					'access_token': $('#password').val()
				});
				e.data.router.navigate('/categories', {trigger: true});
			} 
		};

	},

	logout: function() {
		$('.content').empty();
		App.session.unload();
		this.navigate('/login', {trigger: true});
	},

	moderation: function() {
		$('#layout').empty();
		if(App.current && App.current.close) App.current.close();
		if (App.session.authenticated()) {
			$('#layout').append(window.App.JST['layout']);
			App.moderation = new App.Collections.ModeratedCarts;
			App.categories = new App.Collections.Categories;

			App.moderation.fetch().then(function() {
				App.current = new App.Views.ModerationApp({ collection: App.moderation });
				App.categories.fetch().then(function() {
					AllCategoriesViewDropdown = new App.Views.CategoriesDropdown({ collection: App.categories }).render();
					$('.categories-dropdown').html(AllCategoriesViewDropdown.el);
				});
			});
		} else {
			this.navigate('/login', {trigger: true});
		}
	},

	users: function() {
		$('#layout').empty();
		if(App.current && App.current.close) App.current.close();
		if (App.session.authenticated()) {
			$('#layout').append(window.App.JST['layout']);
			App.users = new App.Collections.Users;

			App.users.fetch().then(function() {
				App.current = new App.Views.UsersApp({ collection: App.users });
			});
		} else {
			this.navigate('/login', {trigger: true});
		}
	},

	words: function() {
		$('#layout').empty()
		if(App.current && App.current.close) App.current.close();
		if (App.session.authenticated()) {
			$('#layout').append(window.App.JST['layout']);
			$('.content').append('<h2 class="content-header">Карточки слов<a href="/word/add">+</a></h2>');
			App.cards = new App.Collections.Cards;
			App.categories = new App.Collections.Categories;

			App.cards.fetch().then(function() {
				App.current = new App.Views.CardsApp({ collection: App.cards });
				App.categories.fetch().then(function() {
					new App.Views.CategoriesDropdown({ collection: App.cards });
				});
			});
		} else {
			this.navigate('/login', {trigger: true});
		}
	},

	addCategoryWord: function(category_id) {
		var addCardView = new App.Views.AddCard({ collection: App.cards, category_id: category_id });
		addCardView.$el.insertAfter('.content-header');
	},

	addWord: function(category_id) {
		// var addCardView = new App.Views.AddCard({ collection: App.cards, category_id: category_id });
		// addCardView.$el.insertAfter('.content-header');
	},

	addUser: function() {
		var addUserView = new App.Views.AddUser({ collection: App.users });
		addUserView.$el.insertAfter('.content-header');
	},

	categories: function() {
		$('#layout').empty();
		if(App.current && App.current.close) App.current.close();
		if (App.session.authenticated()) {
			$('#layout').append(window.App.JST['layout']);
			App.categories = new App.Collections.Categories;
			App.categories.fetch().then(function() {
				App.current = new App.Views.CategoriesApp({ collection: App.categories })
			});
		} else {
			this.navigate('/login', {trigger: true});
		}
	},

	addCategory: function() {
		// $('.content').append('<h2 class="content-header"><a href="/categories" class="back">&larr;</a>Добавить категорию<a href="/category/add">+</a></h2>')
		var addCategoryView = new App.Views.AddCategory({ collection: App.categories });
		addCategoryView.$el.insertAfter('.content-header');
	},

	categoryWords: function(category_id) {
		if(App.current && App.current.close) App.current.close();

		if (App.session.authenticated()) {

			App.Collections.CategoryCards = Backbone.Collection.extend({
				model: App.Models.Card,
				url: '/api/categories/'+category_id+'/words'
			});

			App.categoryCards = new App.Collections.CategoryCards;
			$('.content').empty();
			// $('.content').empty().append('<h2 class="content-header"><a href="/categories">&larr;</a>Карточки слов from cat<a href="/category/' + category_id + '/word/add">+</a></h2>');

			App.categoryCards.fetch().then(function() {
				App.cards = new App.Collections.Cards;
				App.cards.reset(App.categoryCards.toJSON());
				App.current = new App.Views.CardsApp({ collection: App.cards, category_id: category_id });
			});

		} else {
			this.navigate('/login', {trigger: true});
		}
	}


});