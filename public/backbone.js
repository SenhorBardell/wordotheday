(function () {
	window.App = {
		Models: {},
		Views: {},
		Collections: {},
		JST: {}
	};

	window.vent = _.extend({}, Backbone.Events);

	window.template = function(id) {
		return _.template($('#' + id).html());
	}

	Backbone.View.prototype.close = function() {
		this.remove();
		this.unbind();
		if (this.onClose) {
			this.onClose();
		}
	}

})();
this["window"] = this["window"] || {};
this["window"]["App"] = this["window"]["App"] || {};
this["window"]["App"]["JST"] = this["window"]["App"]["JST"] || {};

this["window"]["App"]["JST"]["api"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<article>\n\n\t<p>Апи находится по адресу <a href="http://wordoftheday.herokuapp.com/api"></a></p>\n\n\t<h2>Доступные команды</h2>\n\n\t<h3>Аутентификация</h3>\n\n\t<dl>\n\t\t<dt>POST <strong>/api</strong></dt>\n\t\t\t<dd>Тестовая аутефикация по токену. Принимает пост запрос с параметром auth. Тестовый запрос будет ввиде auth=hashed_password.</dd>\n\t\t<dt>GET <strong>/api</strong></dt>\n\t\t\t<dd>Список апи</dd>\n\t</dl>\n\n\t<h3>Слова</h3>\n\n\t<dl>\n\t\t<dt>GET <strong>/api/words</strong></dt>\n\t\t\t<dd>Получить все слова. Возвращает json {answer, category_id, id, word}</dd>\n\n\t\t<dt>GET <strong>/api/words/{:word}</strong></dt>\n\t\t\t<dd>Слово по id. Возвращает json {answer, category_id, id, word}</dd>\n\n\t\t<dt>POST <strong>/api/words</strong></dt>\n\t\t\t<dd>Создать новое слово. Принимает word=string, answer=string, test_id=integer</dd>\n\n\t\t<dt>DELETE <strong>/api/words/{:word}</strong></dt>\n\t\t\t<dd>Удалить слово по id</dd>\n\t</dl>\n\n\t<h3>Пользователи</h3>\n\n\t<dl>\n\t\t<dt>GET <strong>/api/users</strong></dt>\n\t\t\t<dd>Получить всех пользователей</dd>\n\n\t\t<dt>GET <strong>/api/users/{:user_id}</strong></dt>\n\t\t\t<dd>Получить пользователя по id</dd>\n\n\t\t<dt>PUT <strong>/api/users/{:user_id}</strong></dt>\n\t\t\t<dd>Отредактировать пользователя по id. Принимает username=string, password=string, balance=integer</dd>\n\n\t\t<dt>DELETE <strong>/api/users/{:user_id}</strong></dt>\n\t\t\t<dd>Удалить пользователя по id</dd>\n\n\t\t<dt>GET <strong>/api/user/{:user_id}/words</strong></dt>\n\t\t\t<dd>Показать все слова добавленные пользователем на модерацию</dd>\n\n\t\t<dt>POST <strong>/api/user/{:user_id}/addword</strong></dt>\n\t\t\t<dd>Добавить слово на модерацию. Принимает word=string, answer=string</dd>\n\n\t\t<dt>POST <strong>/api/user/{:word}/removeword</strong></dt>\n\t\t\t<dd>Удалить слово из слов пользователя.</dd>\n\t</dl>\n\n\t<h3>Слова на модерацию</h3>\n\n\t<dl>\n\t\t<dt>GET <strong>/api/moderate/words</strong></dt>\n\t\t\t<dd>Получить все слова на модерацию</dd>\n\n\t\t<dt>GET <strong>/api/moderate/words/{:word}</strong></dt>\n\t\t\t<dd>Получить слово по id</dd>\n\n\t\t<dt>DELETE <strong>/api/moderate/word{:word}</strong></dt>\n\t\t\t<dd>Пометить слово как "отвергнутое". Изменяет статус слова на rejected</dd>\n\n\t\t<dt>POST <strong>/api/moderate/words/{:word}/changestatus</strong></dt>\n\t\t\t<dd>Изменить статус слова. Принимает status=accepted/rejected/waiting</dd>\n\t\t\t<dd>При указании статуса как accepted создает копию в базу слов</dd>\n\t</dl>\n\n\t<h3>Подписки</h3>\n\tАутентификация auth=hashed_password\n\n\t<dl>\n\t\t<dt>POST <strong>/api/user/{:user_id}/subscribe</strong></dt>\n\t\t\t<dd>Подписать пользователя на категорию. Принимает category_id=integer</dd>\n\n\t\t<dt>GET <strong>/api/user/{:user_id}/subscriptions</strong></dt>\n\t\t\t<dd>Получить список подписанных категорий</dd>\n\n\t\t<dt>POST <strong>/api/user/{:user_id}/unsubscribe</strong></dt>\n\t\t\t<dd>Отписать пользователя от категории category_id=integer</dd>\n\t</dl>\n\n\t<h3>Устройства</h3>\n\tНа данный момент каждый пользователь привязывается к одному устройству.\n\n\t<dl>\n\t\t<dt>GET <strong>/api/user/{:user_id}/devices</strong></dt>\n\t\t\t<dd>Получить все устройства пользователя</dd>\n\n\t\t<dt>POST <strong>/api/user/{:user_id}/add_device</strong></dt>\n\t\t\t<dd>Добавить устройство к пользователю. Принимает device_id</dd>\n\n\t\t<dt>POST <strong>/api/user/{:user_id}/remove_device</strong></dt>\n\t\t\t<dd>Удалить устройство у пользователя. Принимает device_id=integer</dd>\n\t</dl>\n\n\t<h3>Категории</h3>\n\n\t<dl>\n\t\t<dt>GET <strong>/api/categories</strong></dt>\n\t\t\t<dd>Возвращает все категории [{id, name, subscription_price, test_price, words}, {...}]</dd>\n\n\t\t<dt>GET <strong>/api/categories/{:category}</strong></dt>\n\t\t\t<dd>Возвращает категорию по id, в формате {id, name, subscription_price, test_price, words}</dd>\n\n\t\t<dt>DELETE <strong>/api/category/{:category}</strong></dt>\n\t\t\t<dd>Удаляет категорию по id</dd>\n\t</dl>\n\n\t<h3>Тест</h3>\n\n\t<dl>\n\t\t<dt>POST <strong>/api/test/start</strong></dt>\n\t\t\t<dd>Начать тест, принимает category=id user=id.</dd>\n\t\t\t<dd>Возвращает, balance, cards{answer, category_id, id, word}</dd>\n\n\t\t<dt>POST <strong>/api/user/{user_id}/addlife</strong></dt>\n\t\t\t<dd>Принимает user=id</dd>\n\t\t\t<dd>Возвращает пользователя {balance, id, max_result, overal_standing, username}</dd>\n\n\t\t<dt>GET <strong>/api/randomwords</strong></dt>\n\t\t\t<dd>Получить рандомно 20 слов из каждой категории</dd>\n\t\t\t<dd>Считает максимально возможное количество слов на категорию и рандомно берет слова из неё. По возрастанию, пока не наберет максимальное количество</dd>\n\t</dl>\n\n\t<h3>Настройки</h3>\n\n\t<dl>\n\t\t<dt>GET <strong>/api/settings</strong></dt>\n\t\t\t<dd>Получить настройки. Возвращает json {answer_time, daily_bonus, general_cost, test_cost, top_bonus, word_cost}</dd>\n\n\t\t<dt>POST <strong>/api/settings</strong></dt>\n\t\t\t<dd>Редактировать настройки. Отправить новые настройки. Принимает answer_time=integer, daily_bonus=interger, general_cost=integer, top_bonus=integer, word_cost=integer</dd>\n\t</dl>\n\n</article>';

}
return __p
};

this["window"]["App"]["JST"]["auth"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="header"><h1>Панель управления</h1></div>\n\t<div class="content">\n\t<form id="auth" class="pure-form">\n\t\t<div>\n\t\t\t<label for="user">Пользователь</label>\n\t\t\t<input type="text" id="user" autofocus>\n\t\t</div>\n\n\t\t<div>\n\t\t\t<label for="password">Пароль</label>\n\t\t\t<input type="password" id="password">\n\t\t</div>\n\n\t\t<div>\n\t\t\t<span="#" id="log-in" class="pure-button pure-button-primary">Войти</span>\n\t\t</div>\n</form></div>';

}
return __p
};

this["window"]["App"]["JST"]["card/add"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form class="pure-form" id="addWord">\n\n\t<div>\n\t\t<label for="word">Слово</label>\n\t\t <input type="text" maxlength="20" id="word" name="word">\n\t\t <span>Максимальная длинна 20 символов</span>\n\t</div>\n\n\t<div>\n\t\t<label for="answer">Описание</label>\n\t\t<textarea type="answer" id="answer" name="answer" maxlength="125"></textarea>\n\t\t<span>Максимальная длинна 125 символов</span>\n\t</div>\n\t\n\t<div>\n\t\t<label id="cat_id_label" for="test_id">Категория</label>\n\t</div>\n\t\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Добавить">\n\t\t<span class="pure-button close">Закрыть</span>\n\t</div>\n\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["card/all"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<td>' +
((__t = ( word )) == null ? '' : __t) +
'</td>\n<td><a href="/words/' +
((__t = ( id )) == null ? '' : __t) +
'/edit" class="edit"><img src="/img/edit.png"></a></td>\n<td><a href="/words/' +
((__t = ( id )) == null ? '' : __t) +
'" class="delete"><img src="/img/delete.png"></a></td>';

}
return __p
};

this["window"]["App"]["JST"]["card/edit"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form id="editWordForm">\n\t<div>\n\t\t<label for="word">Слово</label>\n\t\t<input type="text" id="edit_word" name="word" value="' +
((__t = ( word )) == null ? '' : __t) +
'">\n\t\t<span>Максимальная длинна 20 символов</span>\n\t</div>\n\t\n\t<div>\n\t\t<label for="answer">Описание</label>\n\t\t<textarea type="answer" id="edit_answer" name="answer">' +
((__t = ( answer )) == null ? '' : __t) +
'</textarea>\n\t\t<span>Максимальная длинна 125 символов</span>\n\t</div>\n\n\t<div>\n\t\t<label id="cat_id_label" for="category_id">Категория</label>\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Редактировать">\n\t\t<span class="pure-button close">Закрыть</span>\n\t</div>\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["card/layout"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<h2 class="content-header"><a href="categories" class="back"><img src="/img/left.png"></a>Карточки слов<a href="category/' +
((__t = ( category_id )) == null ? '' : __t) +
'/word/add"><img src="/img/add.png"></a></h2>\n\n<table>\n\t<thead>\n\t\t<tr>\n\t\t\t<td>слово</td>\n\t\t\t<td colspan="2" class="options">Опции</td>\n\t\t</tr>\n\t</thead>\n</table>';

}
return __p
};

this["window"]["App"]["JST"]["card/medit"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form id="editWordForm">\n\t<div>\n\t\t<label for="word">Слово</label>\n\t\t<input type="text" id="edit_word" name="word" value="' +
((__t = ( word )) == null ? '' : __t) +
'" maxlength="20">\n\t</div>\n\t\n\t<div>\n\t\t<label for="answer">Описание</label>\n\t\t<textarea type="answer" id="edit_answer" name="answer" maxlength="125">' +
((__t = ( answer )) == null ? '' : __t) +
'</textarea>\n\t</div>\n\n\t<div>\n\t\t<label id="cat_id_label" for="category_id">Категория</label>\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Добавить">\n\t\t<span class="pure-button reject2">Удалить</span>\n\t</div>\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["card/single"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<td class="id">' +
((__t = ( id )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( name )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( words )) == null ? '' : __t) +
'</td>\n<td class="edit-c"><a href="/category/' +
((__t = ( id )) == null ? '' : __t) +
'/edit" class="edit"><img src="/img/edit.png"></a></td>\n<td class="delete-c"><a href="/category/' +
((__t = ( id )) == null ? '' : __t) +
'" class="delete"><img src="/img/delete.png"></a></td>\n<td class="words-c"><a href="/category/' +
((__t = ( id )) == null ? '' : __t) +
'/words" class="words"><img src="/img/right.png"></a></td>';

}
return __p
};

this["window"]["App"]["JST"]["category/add"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form class="pure-form" id="addCategory">\n\t<div>\n\t\t<label for="name">Название</label>\n\t\t<input type="text" id="name" name="name">\n\t</div>\n\n\t<div>\n\t\t<label for="subscription_price">Цена подписки</label>\n\t\t<input type="text" id="subscription_price" name="subscription_price">\n\t</div>\n\n\t<div>\n\t\t<label for="test_price">Цена теста</label>\n\t\t<input type="text" id="test_price" name="test_price">\n\t</div>\n\n\t<div>\n\t\t<label for="ua_parameter">UA Parameter</label>\n\t\t<input type="text" id="ua_parameter" name="ua_parameter">\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Добавить">\n\t\t<span class="pure-button close">Закрыть</span>\n\t</div>\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["category/all"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '';

}
return __p
};

this["window"]["App"]["JST"]["category/edit"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form id="editCategory">\n\t<div>\n\t\t<label for="edit_name">Название</label>\n\t\t<input type="text" id="edit_name" name="name" value="' +
((__t = ( name )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="subscription_price">Цена подписки</label>\n\t\t<input type="text" id="edit_subscription_price" name="subscription_price" value="' +
((__t = ( subscription_price )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="testa_price">Цена теста</label>\n\t\t<input type="text" id="edit_test_price" name="test_price" value="' +
((__t = ( test_price )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="ua_parameter">UA Parameter</label>\n\t\t<input type="text" id="edit_ua_parameter" name="ua_parameter" value="' +
((__t = ( ua_parameter )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Редактировать">\n\t\t<span class="pure-button close">Закрыть</span>\n\t</div>\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["category/layout"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<h2 class="content-header">Категории<a href="/category/add"><img src="img/add.png"></a></h2>\n\n<table>\n\t<thead>\n\t\t<tr>\n\t\t\t<td>ID</td>\n\t\t\t<td>Название</td>\n\t\t\t<td>Слов</td>\n\t\t\t<td>UA Parameter</td>\n\t\t\t<td colspan="3" class="options">Опции</td>\n\t\t</tr>\n\t</thead>\n</table>';

}
return __p
};

this["window"]["App"]["JST"]["category/single"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<td class="id">' +
((__t = ( id )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( name )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( words )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( ua_parameter )) == null ? '' : __t) +
'</td>\n<td class="edit-c"><a href="/category/' +
((__t = ( id )) == null ? '' : __t) +
'/edit" class="edit"><img src="/img/edit.png"></a></td>\n<td class="delete-c"><a href="/category/' +
((__t = ( id )) == null ? '' : __t) +
'" class="delete"><img src="/img/delete.png"></a></td>\n<td class="words-c"><a href="/category/' +
((__t = ( id )) == null ? '' : __t) +
'/words" class="words"><img src="/img/right.png"></a></td>';

}
return __p
};

this["window"]["App"]["JST"]["layout"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<a href="#" id="menuLink" class="menu-link">\n\t<span></span>\n</a>\n\n<div id="menu">\n\t<div class="pure-menu pure-menu-open">\n\t\t<a class="pure-menu-heading" href="/">WOTD</a>\n\t\t\n\t\t<ul>\n\t\t\t<li><a href="/users">Пользователи</a></li>\n\t\t\t<li><a href="/categories">Категории</a></li>\n\t\t\t<li><a href="/moderation">Модерация</a></li>\n\t\t\t<li><a href="/settings">Настройки</a></li>\n\t\t\t<li><a href="/api/usage">Функции апи</a></li>\n\t\t\t<li><a href="/logout">Выход</a></li>\n\t\t</ul>\n\t</div>\n</div>\n\n<div id="main">\n\t<div class="header">\n\t\t<h1>Панель управления</h1>\n\t\t<h2>Слово дня</h2>\n\t</div>\n\n\t<div class="content">\n\n\t</div>\n\n</div>';

}
return __p
};

this["window"]["App"]["JST"]["moderation/layout"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<h2 class="content-header">Слова на модерацию</a></h2>\n\n<table>\n\t<thead>\n\t\t<tr>\n\t\t\t<td>ID</td>\n\t\t\t<td>Слово</td>\n\t\t\t<td>Ответ</td>\n\t\t\t<td>Поместить в</td>\n\t\t\t<td colspan="2" class="options">Опции</td>\n\t\t</tr>\n\t</thead>\n</table>';

}
return __p
};

this["window"]["App"]["JST"]["moderation/single"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<td class="id">' +
((__t = ( id )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( word )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( answer )) == null ? '' : __t) +
'</td>\n<td class="categories-dropdown">In: Category</td>\n<td class="edit1"><a href="/api/moderate/' +
((__t = ( id )) == null ? '' : __t) +
'/edit" class="edit"><img src="/img/edit.png"></a></td>\n<td class="accept"><a href="/api/moderate/' +
((__t = ( id )) == null ? '' : __t) +
'/accept" class="accept"><img src="/img/ok.png"></a></td>\n<td class="delete"><a href="/api/moderate/' +
((__t = ( id )) == null ? '' : __t) +
'/decline" class="reject"><img src="/img/delete.png"></a></td>';

}
return __p
};

this["window"]["App"]["JST"]["settings"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form class="pure-form" id="settings">\n\t<div>\n\t\t<label for="answer_time">Время ответа</label>\n\t \t<input type="number" id="answer_time" name="answer_time" value="' +
((__t = ( answer_time )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="daily_bonus">Ежедневный бонус</label>\n\t\t <input type="number" id="daily_bonus" name="daily_bonus" value="' +
((__t = ( daily_bonus )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="general_cost">Базовая стоимость</label>\n\t\t <input type="number" id="general_cost" name="general_cost" value="' +
((__t = ( general_cost )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="life_cost">Стоимость жизни</label>\n\t\t <input type="number" id="life_cost" name="life_cost" value="' +
((__t = ( life_cost )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="top_bonus">Максимальный бонус</label>\n\t\t <input type="number" id="top_bonus" name="top_bonus" value="' +
((__t = ( top_bonus )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="word_cost">Слово</label>\n\t\t<input type="number" id="word_cost" name="word_cost" value="' +
((__t = ( word_cost )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="words_for_the_next_bonus">Количество слов за следующий бонус</label>\n\t\t<input type="number" id="words_for_the_next_bonus" name="words_for_the_next_bonus" value="' +
((__t = ( words_for_the_next_bonus )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Сохранить">\n\t</div>\n\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["user/add"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form class="pure-form" id="addWord">\n\t\n\t<div>\n\t\t<label for="username">Имя пользователя</label>\n\t\t<input type="text" id="username" name="username">\n\t</div>\n\n\t<div>\n\t\t<label for="password">Пароль</label>\n\t\t<input type="text" id="password" name="password">\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Добавить">\n\t\t<span class="pure-button close">Закрыть</span>\n\t</div>\n\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["user/edit"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<form id="editUser" class="pure-form" id="addWord">\n\t\n\t<div>\n\t\t<label for="edit_username">Имя пользователя</label>\n\t\t<input type="text" id="edit_username" name="edit_username" value="' +
((__t = ( username )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="edit_password">Пароль</label>\n\t\t<input type="password" id="edit_password" name="edit_password" >\n\t</div>\n\n\t<div>\n\t\t<label for="edit_balance">Монет</label>\n\t\t<input type="text" id="edit_balance" name="edit_balance" value="' +
((__t = ( balance )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="edit_max_result">Максимальный результат</label>\n\t\t<input type="text" id="edit_max_result" name="edit_max_result" value="' +
((__t = ( max_result )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<label for="edit_overal_standing">Общий зачет</label>\n\t\t<input type="text" id="edit_overal_standing" name="edit_overal_standing" value="' +
((__t = ( overal_standing )) == null ? '' : __t) +
'">\n\t</div>\n\n\t<div>\n\t\t<input type="submit" class="pure-button pure-button-primary" value="Редактировать">\n\t\t<span class="pure-button close">Закрыть</span>\n\t</div>\n\n</form>';

}
return __p
};

this["window"]["App"]["JST"]["user/layout"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<h2 class="content-header">Пользователи<a href="/user/add"><img src="img/add.png"></a></h2>\n\n<table>\n\t<thead>\n\t\t<tr>\n\t\t\t<td>Имя</td>\n\t\t\t<td>Монеты</td>\n\t\t\t<td>Макс Результат</td>\n\t\t\t<td>Общий зачет</td>\n\t\t\t<td colspan="2" class="options">Опции</td>\n\t\t</tr>\n\t</thead>\n</table>';

}
return __p
};

this["window"]["App"]["JST"]["user/list"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p +=
((__t = ( username )) == null ? '' : __t) +
', монет: ' +
((__t = ( balance )) == null ? '' : __t) +
'\n<a href="/user/' +
((__t = ( id )) == null ? '' : __t) +
'/edit" class="edit">Редактировать</a>\n<a href="/user/' +
((__t = ( id )) == null ? '' : __t) +
'" class="delete">X</a>';

}
return __p
};

this["window"]["App"]["JST"]["user/single"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<td>' +
((__t = ( username )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( balance )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( max_result )) == null ? '' : __t) +
'</td>\n<td>' +
((__t = ( overal_standing )) == null ? '' : __t) +
'</td>\n<td><a href="/user/' +
((__t = ( id )) == null ? '' : __t) +
'/edit" class="edit"><img src="/img/edit.png"></a></td>\n<td><a href="/user/' +
((__t = ( id )) == null ? '' : __t) +
'" class="delete"><img src="/img/delete.png"></a></td>\n';

}
return __p
};
App.Models.Card = Backbone.Model.extend({

});
App.Models.Category = Backbone.Model.extend({

});
App.Models.ModeratedCart = Backbone.Model.extend({

});
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
App.Models.Settings = Backbone.Model.extend({

});
App.Models.Test = Backbone.Model.extend({

});
App.Models.User = Backbone.Model.extend({

});
App.Collections.Cards = Backbone.Collection.extend({
    model: App.Models.Card,
    url: '/api/words'
});
App.Collections.Categories = Backbone.Collection.extend({
	model: App.Models.Category,
	url: '/api/categories'
});
App.Collections.ModeratedCarts = Backbone.Collection.extend({
    model: App.Models.ModeratedCart,
    url: '/api/moderate/words'
});
App.Collections.Settings = Backbone.Collection.extend({
	model: App.Models.Settings,
	url: 'api/settings'
});
App.Collections.Tests = Backbone.Collection.extend({
	model: App.Models.Test,
	url: '/api/tests'
});
App.Collections.Users = Backbone.Collection.extend({
    model: App.Models.User,
    url: '/api/users'
});
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
				"<%= categoryTemplate(category) %>",
			"<% }); %>",
		"</select>"
	].join(',')),

	// categoryTemplate: _.template('<option id="<%= id %>"><%= name %></option>'),

	categoryTemplate: function(category) {
		if (category.get('id') == 211)
			return '<option selected value=' + category.get('id') + '>' + category.get('name') + '</option>';
		else
			return '<option value=' + category.get('id') + '>' + category.get('name') + '</option>';
	},

	initialize: function() {
		this.collection.on('add', this.addOne, this);
		this.collection.comparator = function(category) {
			return -1;
		}
	},

	render: function() {

		var html = this.template({
			categories: this.collection,
			categoryTemplate: this.categoryTemplate
		});

		this.$el.append(html);

		return this;
	},

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
		ok = confirm('Вы действительно хотите удалить выбранную категорию?');
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
App.Views.ModerationApp = Backbone.View.extend({

	initialize: function() {
		var AllModerationWordsView = new App.Views.ModerationWords({ collection: App.moderation }).render();
	
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

})

App.Views.ModerationWords = Backbone.View.extend({
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

	addOne: function(word) {
		var moderatedWordsView = new App.Views.ModerationWord({ model: word });
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
		this.model.on('destroy', this.unrender, this);
		this.model.on('change', this.render, this);
	},

	events: {
		'click a.accept': 'accept',
		'click a.reject': 'reject',
		'click a.edit': 'edit'
	},

	accept: function(e) {
		id = this.model.get('id');
		category = $(e.target).parent().parent().parent().find('#category_id').val()

		that = this
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
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	},

	unrender: function() {
		this.remove();
	}
});
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

		var router = this;
		
		$('#log-in').click(check);

		$(document).keypress(function(e) {
    		if(e.which == 13) {
        		check(e);
    		}
		});

		function check(e) {
			e.preventDefault(e);
			if (App.session.authenticated()) {
				router.navigate('/categories', {trigger: true});
			} else {
				var data = {
					user: $('#user').val(),
					password: $('#password').val()
				};

				$.post("/api/users/adminauth", data).success(function() {

					App.session.save({
						'user_id': $('#user').val(),
						'access_token': $('#password').val()
					});
					$.ajaxSetup({
						headers: {
							'Authentication': btoa($('#password').val())
						}
					});

					router.navigate('/categories', {trigger: true});

				}).fail(function(data) {
					// $(data.responseJSON.error.message).insertAfter('#log-in');
					$('#error-message').remove();
					$('#log-in').parent().append('<span id="error-message"> ' + data.responseJSON.error.message + '</span>');
					console.log(data.responseJSON.error.message);
				});
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