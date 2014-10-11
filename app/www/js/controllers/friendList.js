angular.module('beermetender')
	.controller('friendListCtrl', ['$scope', '$ionicModal', 'Beers', 'friends', 'beers', function($scope, $ionicModal, beerStorage, friends, beers) {

        $scope.getNextBatchOfFriends = friends.getNext;
        $scope.friends = friends;
        bindBeersWithFriends();

        $scope.loadMore = function loadMore() {
            $scope.getNextBatchOfFriends().then(function(friends) {
                $scope.friends.concat(friends);
                $scope.getNextBatchOfFriends = friends.getNext;
                bindBeersWithFriends();
            });
        };

        function bindBeersWithFriends() {
            angular.forEach(friends, function(friend){

                angular.forEach(beers, function(beer) {

                    if(beer.user.id === friend.id) {
                        friend.beer = beer;
                    }

                });
            });
        }

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

        $scope.goGoGadgetAuBiere = function() {
            if($scope.selectedFriend.beer && $scope.selectedFriend.beer.id) {
                beerStorage.update($scope.selectedFriend.beer);
            }
            else {
                beerStorage.shareWithUser($scope.selectedFriend);
            }
        };

        $scope.$on('$destroy', function onDestroyFriendListCtrl() {
            $scope.modal.remove();
        });

	}]);

