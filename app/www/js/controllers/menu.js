angular.module('beermetender')
	.controller('MenuCtrl', ['$scope', '$state', 'facebook', function($scope, $state, facebook) {

		$scope.logout = function logout() {
			facebook.logout().then(function() {
				$state.go('facebook-connect');
			});
		};

	}]);