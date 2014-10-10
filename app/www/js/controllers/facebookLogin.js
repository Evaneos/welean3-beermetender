angular.module('beermetender')
	.controller('facebookLoginCtrl', ['$scope', '$ionicModal', '$state', 'facebook', function($scope, $ionicModal, $state, facebook) {

		$scope.userID = facebook.getUserID();

		$ionicModal.fromTemplateUrl('templates/facebook/failure.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });

		$scope.login = function login() {

			facebook.login().then(
				function(connection) {
					$state.go('beer-list');
				},
				function(connection) {
					$scope.modal.show();
				}
			);

		};

		$scope.logout = function login() {

			facebook.logout().then(
				function(connection) {
					$scope.userID = null;
				},
				function(connection) {
					$scope.modal.show();
				}
			);

		};

		$scope.close = function() {
			$scope.modal.hide();
		};

	}]);