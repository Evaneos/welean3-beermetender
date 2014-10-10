angular.module('beermetender')
	.service('facebook', ['$window', '$q', 'configuration', function($window, $q, configuration) {

		if(!$window.cordova) {
      		facebookConnectPlugin.browserInit(configuration.facebook.appKey, configuration.facebook.version);
    	}

    	function Facebook() {
    		var permissions = ["public_profile", "email", "user_friends"];
    		var userID = null;

    		this.login = function() {
    			var defered = $q.defer();

    			facebookConnectPlugin.login(permissions,
    				function(response) {
    					userID = response.authResponse.userId;
    					defered.resolve(response);
    				},
    				function(response) {
    					defered.reject(response);
    				});

    			return defered.promise;
    		};

    		this.getFriendList = function() {

				var defered = $q.defer();

    			facebookConnectPlugin.api('/me/friends', permissions,
    				function(response) {
    					var newResponse = response.data;

    					defered.resolve(newResponse);
    				},
    				function(response) {
    					defered.reject(response);
    				});

    			return defered.promise;
    		};
    	}

		return new Facebook();
	}]);