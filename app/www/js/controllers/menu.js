angular.module('beermetender')
	.controller('MenuCtrl', ['$scope', '$state', 'facebook', function($scope, $state, facebook) {

		$scope.isLoggedIn = false;
		$scope.$watch(function() { return facebook.getUserID();}, function(newVal) {
			$scope.isLoggedIn = newVal != null;
		});

		$scope.logout = function logout() {
			facebook.logout().then(function() {
				$state.go('facebook-connect');
			});
		};

	}]);