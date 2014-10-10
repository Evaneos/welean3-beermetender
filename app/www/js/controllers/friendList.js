angular.module('beermetender')
	.controller('friendListCtrl', ['$scope', 'friends', function($scope, friends) {

        $scope.friends = friends;

	}]);

