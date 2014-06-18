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