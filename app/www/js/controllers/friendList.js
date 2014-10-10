angular.module('beermetender')
	.controller('friendListCtrl', ['$scope', '$ionicModal', 'Beers', 'friends', function($scope, $ionicModal, beers, friends) {

        $scope.getNextBatchOfFriends = friends.getNext;
        $scope.friends = friends;

        $scope.loadMore = function loadMore() {
            $scope.getNextBatchOfFriends().then(function(friends) {
                $scope.friends.concat(friends);
                $scope.getNextBatchOfFriends = friends.getNext;
            });
        };

        $ionicModal.fromTemplateUrl('templates/friends/addbeer.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });

        $scope.shareBeer = function shareBeer(friend) {
            $scope.selectedFriend = friend;
            $scope.modal.show();
        };

        $scope.createBeer = function(balance) {
            beers.shareWithUser($scope.selectedFriend, balance);
        };

        $scope.$on('$destroy', function onDestroyFriendListCtrl() {
            $scope.modal.remove();
        });

	}]);

