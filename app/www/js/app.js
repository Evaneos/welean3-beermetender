// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('beermetender', ['ionic'])

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
.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

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
        friends: ['$q','facebook', function($q, facebook) {
          return facebook.getFriendList();
        }]
      }
    });

  $urlRouterProvider.otherwise("/");
}]);
