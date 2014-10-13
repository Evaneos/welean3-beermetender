angular.module('beermetender')
	.service('authentication', ['$resource', '$http', 'facebook', 'configuration', function($resource, $http, facebook, configuration) {
	
		function Authentication() {

			var res = $resource(configuration.api.url+'/api/v1/authenticate', {}, {post: {method: 'POST'}});

			var token = null;

			this.getToken = function() {
				return token;
			}

			this.authenticate = function() {
				var fb_token = facebook.getAccessToken();
				return res.post({token : fb_token}).$promise.then(function(session){
					token = session.data.token;
				});
			}
		};

		return new Authentication();
	}]);