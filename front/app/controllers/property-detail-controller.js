angular.module('app.controllers').controller('propertyDetailCtrl', ['$rootScope', '$stateParams', '$scope', '$state', '$timeout', '$ionicPopup', 'Auth', 'Data', 'Web', function ($rootScope, $stateParams, $scope, $state, $timeout, $ionicPopup, Auth, Data, Web) {

  $scope.imgIndex = 1;
  $scope.userData = Auth.getuserData();
  $scope.reportForm = {};
  $scope.changeImgNo = function ($type) {
    if ($type == 'left' && $scope.propertyDetail.images.length > $scope.imgIndex) {
      $scope.imgIndex++;
    } else if ($type == 'right' && $scope.imgIndex > 1) {
      $scope.imgIndex--;
    }
  };

  $scope.init = function () {
    if($stateParams.propertyid == 'preview'){

    } else {
      Web.propertydetails({property_id: $stateParams.propertyid}, function (response) {
        $scope.propertyDetail = response.data.propertydata;
        $scope.similarProperties = response.data.similarproperties;
        $scope.propertyDetail.amenities = $scope.propertyDetail.amenities.split(',');
        $scope.propertyDetail.commute = $scope.propertyDetail.commute.split(',');
        $scope.reportForm.property_id = $stateParams.propertyid;
      }, function (error) {
        console.log(error);
      });
    }
  };

  $scope.openDetail = function (id) {
    $stateParams.propertyid = id;
    $state.reload();
  };

  $scope.getSimplifyLocation = function (location) {
    location = location.split(',');
    if(location.length > 2) {
      location = location.slice(Math.max(location.length - 2, 1));
    }
    location = location.toString();
    return location;
  };

  $scope.getAvailability = function (date) {
    return moment(date).format('MMM, YYYY');
  };

  $scope.openChat = function () {
    console.log($scope.propertyDetail.user);
    var data = angular.copy($scope.propertyDetail.user);
    data.property_id = $scope.propertyDetail.id;
    data.property_title = $scope.propertyDetail.title;
    data.is_tanent = true;
    window.localStorage.chatWith = JSON.stringify(data);
    $state.go('chat');
  };

  $scope.reportProperty = function () {
    $ionicPopup.show({
      template: '<form name="ssForm" id="ssForm" novalidate angular-validator autocomplete="off">' +
      '    <div class="input-box mrt-15">' +
      '      <div style="position: relative;">' +
      '        <textarea autofocus id="input1" placeholder="Type description" class="white-text-box width-100 pdl-10" name="pin" validate-on="dirty" ng-model="reportForm.message" required></textarea>' +
      '      </div>' +
      '    </div>' +
      '</form>',
      title: 'Report',
      scope: $scope,
      buttons: [
        {text: 'Cancel'},
        {
          text: '<b>Submit</b>',
          type: 'button-positive',
          onTap: function (e) {
            if (!$scope.reportForm.message) {
              e.preventDefault();
            } else {
              Web.reportproperty($scope.reportForm, function (response) {
                $ionicPopup.alert({
                  title: 'Success!',
                  template: 'Data submitted successfully!'
                });
              }, function (error) {
                console.log(error);
              });
            }
          }
        }
      ]
    });
  };

  $scope.init();

  if(typeof FCMPlugin !== 'undefined') {
    FCMPlugin.onNotification(function (data) {
      if(data.notification_type != 'chat') {
        if($state.current.name != 'notification') {
          Data.setNotificationData({
            title: data.title,
            body: data.body,
            dateTime: moment().format('YYYY-MM-DD hh:mm a')
          });
          if (data.wasTapped) {
            $state.go('notification');
          }
        }
      }else{
        $state.go('chat-list');
      }
    });
  }

}]);
