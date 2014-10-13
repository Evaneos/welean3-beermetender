angular.module('beermetender')
	.service('Beers', ['$resource', 'facebook', 'authentication', 'configuration', function($resource, facebook, authentication, configuration) {
	
		function Beers() {

			this.getList = function() {
				var res = $resource(configuration.api.url+'/api/v1/beers/:beerId', {beerId:'@id', token: authentication.getToken()}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

				return res.query().$promise;
			};

			this.create = function(beer)
			{
				var res = $resource(configuration.api.url+'/api/v1/beers/:beerId', {token: authentication.getToken()}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

				var beer = new res({
					user_from_id: facebook.getUserID(),
					user_to_id: beer.user.id,
					balance: beer.balance
				});

				console.log(beer);
				
				return beer.$save();
			};

			this.update = function(beer) {
				var res = $resource(configuration.api.url+'/api/v1/beers/:beerId', {beerId:'@id', token: authentication.getToken()}, {query: {method: 'GET'}, 'update': { method:'PUT' }});

				return res.update({beerId: beer.id}, beer);
			};

		};

		return new Beers();
	}]);