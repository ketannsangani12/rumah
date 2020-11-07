angular.module('app.services')
  .service('Web', ['$rootScope', '$state', '$http', '$httpParamSerializerJQLike', '$ionicPopup', 'Auth', function ($rootScope, $state, $http, $httpParamSerializerJQLike, $ionicPopup, Auth) {

    var s = {};

    s.baseUrl = APIURL;

    s.httpPost = function (url, data, successCb, failureCb, isJson) {
        var formData = data;
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        };
        /*if (Auth.getUserId()) {
         config.headers['user_id'] = Auth.getUserId();
         data['user_id'] = Auth.getUserId();
         }*/
        if(!(formData && formData.in_background == true)) {
          $rootScope.loader.show();
        }

        if (Auth.getToken()) {
            config.headers['token'] = Auth.getToken();
        }

        if (data && Auth.getuserData()) {
            data.user_id = Auth.getuserData()['id'];
        }

        var apiUrl = '';
        apiUrl = s.baseUrl +'/apiweb/'+ url;

        $http.defaults.headers.common = config.headers;

        if (!isJson) {
            data = $httpParamSerializerJQLike(data);
        }

        $http.post(apiUrl, data, config).then(function (result) {
            if (s.isSuccessResponse(result.data, formData)) {
                successCb(result.data, formData);
            } else {
                failureCb(result.data);
            }
        }, function (error) {
          $rootScope.loader.hide();
          if (error.status == -1) {
              alert(JSON.stringify(error));
              document.getElementById("noInterNet").style.display = 'flex';
          } else {
              failureCb(error);
          }
        });
    };

    s.isSuccessResponse = function (data, formData) {
        if (data.status == 1) {
          if(!(formData && formData.in_background && formData.in_background == true)) {
            $rootScope.loader.hide();
          }
          return true;
        } else {
          if(!(formData && formData.in_background == true)) {
            $rootScope.loader.hide();
          }
          $rootScope.loader.hide();
          if(!(formData && formData.in_background)) {
            if (typeof data.message == 'string') {
              $ionicPopup.alert({
                title: 'Error!',
                template: data.message
              });
            } else if (typeof data.message == 'object') {
              for (i in data.message) {
                $ionicPopup.alert({
                  title: 'Error!',
                  template: data.message[i]
                });
              }
            }
          }
          return false;
        }
    };

    s.search = function (data, successCb, failureCb) {
      s.httpPost('search', data, successCb, failureCb);
    };
    s.propertydetails = function (data, successCb, failureCb) {
      s.httpPost('propertydetails', data, successCb, failureCb);
    };

    return s;

  }]);
