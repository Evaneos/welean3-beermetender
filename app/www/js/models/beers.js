angular.module('beermetender')
	.service('Beers', ['$resource', 'facebook', function($resource, facebook) {
	
		function Beers() {

			var res = $resource('http://www.beer-me-tender.local/api/v1/beers/:beerId', {beerId:'@id'}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

			this.getList = function() {
				return res.query().$promise;
			};

			this.shareWithUser = function(user, balance)
			{
				var beer = new res({
					user_from_id: facebook.getUserID(),
					user_to_id: user.id,
					balance: balance
				});
				
				return beer.$save();
			};

			this.update = function(beer) {
				return res.update({beerId: beer.id}, beer);
			};

		};

		return new Beers();
	}]);