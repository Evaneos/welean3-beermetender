angular.module('beermetender')
	.controller('facebookLoginCtrl', ['$scope', '$state', 'facebook', function($scope, $state, facebook) {

		$scope.login = function login() {

			facebook.login().then(function() {
				$state.go('friend-list');
			});

		};

	}]);

