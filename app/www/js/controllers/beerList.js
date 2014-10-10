angular.module('beermetender')
	.controller('beerListCtrl', ['$scope', '$ionicModal', 'Beers', 'beers', function($scope, $ionicModal, beerStorage, beers) {

        $scope.beers = beers;

        $ionicModal.fromTemplateUrl('templates/beers/updatebeer.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });

        $scope.updateBeer = function(beer) {
            $scope.selectedBeer = beer;
            $scope.modal.show();
        };

        $scope.saveBeer = function() {
           beerStorage.update($scope.selectedBeer);
        };

        $scope.$on('$destroy', function() {
            $scope.modal.remove();
        });

	}]);
