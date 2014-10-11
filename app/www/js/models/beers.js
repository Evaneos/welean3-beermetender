angular.module('beermetender')
	.service('Beers', ['$resource', 'facebook', 'authentication', function($resource, facebook, authentication) {
	
		function Beers() {

			this.getList = function() {
				var res = $resource('http://www.beer-me-tender.local/api/v1/beers/:beerId', {beerId:'@id', token: authentication.getToken()}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

				return res.query().$promise;
			};

			this.shareWithUser = function(user, balance)
			{
				var res = $resource('http://www.beer-me-tender.local/api/v1/beers/:beerId', {beerId:'@id', token: authentication.getToken()}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

				var beer = new res({
					user_from_id: facebook.getUserID(),
					user_to_id: user.id,
					balance: balance
				});
				
				return beer.$save();
			};

			this.update = function(beer) {
				var res = $resource('http://www.beer-me-tender.local/api/v1/beers/:beerId', {beerId:'@id', token: authentication.getToken()}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

				return res.update({beerId: beer.id}, beer);
			};

		};

		return new Beers();
	}]);