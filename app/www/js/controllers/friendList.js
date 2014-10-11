angular.module('beermetender')
	.controller('friendListCtrl', ['$scope', '$ionicModal', 'Beers', 'friends', 'beers', 'facebook', function($scope, $ionicModal, beerStorage, friends, beers, facebook) {

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

        $scope.oweYouBeer = function(number) {
            if (!$scope.selectedFriend.beer) {
                $scope.selectedFriend.beer = {
                    user : { id : $scope.selectedFriend.id },
                    balance: 0
                };
            }

            if ($scope.selectedFriend.beer.user.id != facebook.getUserID()) {
                $scope.selectedFriend.beer.balance += number;
            } else {
                $scope.selectedFriend.beer.balance -= number;
            }

            if ($scope.selectedFriend.beer.id) {
                beerStorage.update($scope.selectedFriend.beer);
            } else {
                beerStorage.create($scope.selectedFriend.beer);
            }
            $scope.modal.hide();
        }

        $scope.closeModal = function() {
            $scope.modal.hide();
        }

        $scope.$on('$destroy', function onDestroyFriendListCtrl() {
            $scope.modal.remove();
        });

	}]);

