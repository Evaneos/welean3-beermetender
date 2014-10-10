// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('beermetender', ['ionic', 'ngResource'])

.run(['$ionicPlatform', function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if(window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if(window.StatusBar) {
      StatusBar.styleDefault();
    }
  });
}])
.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', function($stateProvider, $urlRouterProvider, $httpProvider) {

  $stateProvider
    .state('facebook-connect', {
      url: '/',
      templateUrl: 'templates/facebook/connect.html',
      controller: 'facebookLoginCtrl'
    })
    .state('beer-list', {
      url: '/beers',
      templateUrl: 'templates/beers/list.html',
      controller: 'beerListCtrl',
      resolve: {
        beers: ['Beers', function(beers) {
          return beers.getList().then(function(response) {
            return response.data;
          });
        }]
      }
    })
    .state('friend-list', {
      url: '/friends',
      templateUrl: 'templates/friends/list.html',
      controller: 'friendListCtrl',
      resolve: {
        friends: ['facebook', function(facebook) {
          return facebook.getFriendList();
        }]
      }
    });

  $urlRouterProvider.otherwise("/");

  $httpProvider.defaults.headers.common['Authorization'] = 'Basic ' + 'charles:test';
  $httpProvider.defaults.useXDomain = true;
  $httpProvider.defaults.withCredentials = true;
  delete $httpProvider.defaults.headers.common['X-Requested-With'];
}]);
