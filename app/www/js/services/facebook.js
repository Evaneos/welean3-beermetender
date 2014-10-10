angular.module('beermetender')
	.service('facebook', ['$window', '$q', '$http', 'configuration', function($window, $q, $http, configuration) {

		if(!$window.cordova) {
      		facebookConnectPlugin.browserInit(configuration.facebook.appKey, configuration.facebook.version);
    	}

    	function Facebook() {
    		var permissions = ["public_profile", "email", "user_friends"];
    		var userID = null;

            this.getUserID = function() {
                return userID;
            };

    		this.login = function() {
    			var defered = $q.defer();

    			facebookConnectPlugin.login(permissions,
    				function(response) {
                        console.log(response);
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
    					defered.resolve(transformFbResponse(response));
    				},
    				function(response) {
    					defered.reject(response);
    				});

    			return defered.promise;
    		};
    	}

    	function transformFbResponse(response) {
    		var newResponse = response.data;

    		if(response.paging && response.paging.next) {
    			newResponse.getNext = function() {
    				var defered = $q.defer();

    				$http.get(response.paging.next).success(function(data) {
    					defered.resolve(transformFbResponse(data));
    				});

    				return defered.promise;
    			};
    			
    		}

    		return newResponse;
    	}

		return new Facebook();
	}]);