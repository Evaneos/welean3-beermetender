angular.module('beermetender')
	.controller('facebookLoginCtrl', ['$scope', '$ionicModal', '$state', 'facebook', 'authentication', function($scope, $ionicModal, $state, facebook, authentication) {

		$scope.userID = facebook.getUserID;

		$ionicModal.fromTemplateUrl('templates/facebook/failure.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });

		$scope.login = function login() {

			facebook.login().then(
				function(connection) {
					authentication.authenticate().then(function() {
						$state.go('friend-list');
					});
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