angular.module('app.services')
    .service('Data', ['$state', '$http', '$timeout', 'Web', 'Helper', function($state, $http, $timeout, Web, Helper){

        var s = {};

        s.setNotificationData = function (data) {
          var notifications = [];
          if(window.localStorage.notifications){
            notifications = JSON.parse(window.localStorage.notifications);
          }
          if(notifications.length == 20){
            notifications.splice(0, 1);
          }
          notifications.push(data);
          window.localStorage.notifications = JSON.stringify(notifications);
        };

        s.getNotificationData = function () {
          var notifications = [];
          if(window.localStorage.notifications){
            notifications = JSON.parse(window.localStorage.notifications);
          }
          return notifications;
        };

        return s;

    }]);
