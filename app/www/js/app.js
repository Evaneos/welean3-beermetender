// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('beermetender', ['ionic', 'ngResource'])

.run(['$ionicPlatform', '$state', 'facebook', 'authentication', function($ionicPlatform, $state, facebook, authentication) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if(window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if(window.StatusBar) {
      StatusBar.styleDefault();
    }

    facebook.getLoginStatus().then(function(connection){
      authentication.authenticate().then(function() {
        $state.go('friend-list');
      });
    },
    function(connection){
    });

  });
}])
.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', function($stateProvider, $urlRouterProvider, $httpProvider) {

  $stateProvider
    .state('facebook-connect', {
      url: '/',
      templateUrl: 'templates/facebook/connect.html',
      controller: 'facebookLoginCtrl'
    })
    .state('friend-list', {
      url: '/friends',
      templateUrl: 'templates/friends/list.html',
      controller: 'friendListCtrl',
      resolve: {
        friends: ['facebook', function(facebook) {
          return facebook.getFriendList();
        }],
        beers: ['Beers', function(beers) {
          return beers.getList().then(function(response) {
            return response.data;
          });
        }]
      }
    });

  $urlRouterProvider.otherwise("/");

  $httpProvider.defaults.headers.common['Authorization'] = 'Basic ' + 'charles:test';
  $httpProvider.defaults.useXDomain = true;
  $httpProvider.defaults.withCredentials = true;
  delete $httpProvider.defaults.headers.common['X-Requested-With'];
}]);
