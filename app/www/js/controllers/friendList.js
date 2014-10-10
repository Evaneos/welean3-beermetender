angular.module('beermetender')
	.controller('friendListCtrl', ['$scope', 'friends', function($scope, friends) {

        $scope.getNextBatchOfFriends = friends.getNext;
        $scope.friends = friends;

        $scope.loadMore = function() {

            if($scope.getNextBatchOfFriends) {

                $scope.getNextBatchOfFriends().then(function(friends) {
                    $scope.friends.concat(friends);
                    $scope.getNextBatchOfFriends = friends.getNext;
                });
            }
        };

	}]);

