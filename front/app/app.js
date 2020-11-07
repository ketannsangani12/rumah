// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('app', ['ionic', 'ui.router', 'app.controllers', 'app.routes', 'app.directives', 'angularValidator', 'app.services', 'ngTouch', 'angularMoment', 'rzSlider', 'google.places'])
.run(function ($ionicPlatform, $rootScope, $window, $ionicPopup, $state, Web, Auth) {
  $rootScope.screen = {height: $window.innerHeight, width: $window.innerWidth};
  $rootScope.platform = 'ios';
  $rootScope.currentRoute = '';
  $ionicPlatform.ready(function () {

  });

  $rootScope.loader = {
      display: false,
      show: function () {
          $rootScope.loader.display = true;
      },
      hide: function () {
          $rootScope.loader.display = false;
      }
  };

  $rootScope.goBack = function () {
    history.go(-1);
  };

  $rootScope.profilePicThumbChar = function (str) {
    var strArr = str.split(' ');
    if(strArr.length > 1){
      return strArr[0].charAt(0)+''+strArr[1].charAt(0).toUpperCase();
    }else{
      return str.charAt(0).toUpperCase();
    }
  };

  $rootScope.getFormattedDate = function (date,formate) {
    return moment(date).format(formate);
  };

  $rootScope.getAddress = function(latitude, longitude) {
      return new Promise(function (resolve, reject) {
          var request = new XMLHttpRequest();
          var method = 'GET';
          var url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA5SFXFicitmopPrhZL74XC4UV2GJYSpFs&latlng=' + latitude + ',' + longitude + '&sensor=true';
          var async = true;
          request.open(method, url, async);
          request.onreadystatechange = function () {
              if (request.readyState == 4) {
                  if (request.status == 200) {
                      var data = JSON.parse(request.responseText);
                      var address = data.results[0];
                      var locationData = JSON.parse(window.localStorage.locationData);
                      locationData.place = address['address_components'][2]['short_name'];
                      window.localStorage.locationData = JSON.stringify(locationData);
                      resolve(address);
                  } else {
                      reject(request.status);
                  }
              }
          };
          request.send();
      });
  };

  $rootScope.checkPinLength = function (val, event) {
    if(val && val == 6 && event.keyCode != 8){
      event.preventDefault();
    }
  };

  var onSuccess = function (position) {
      window.localStorage.locationData = JSON.stringify({
          'latitude': position.coords.latitude,
          'longitude': position.coords.longitude,
          'altitude': position.coords.altitude,
          'accuracy': position.coords.accuracy,
          'altitude_accuracy': position.coords.altitudeAccuracy,
          'heading': position.coords.heading,
          'speed': position.coords.speed,
          'timestamp': position.timestamp
      });
    $rootScope.getAddress(position.coords.latitude, position.coords.longitude);
  };

  var onError = function (error) {
      console.log('code: ' + error.code + '\n' +
              'message: ' + error.message + '\n');
  };

  $rootScope.apiurl = 'https://www.rumah-iapp.com';

  $rootScope.autoCompleteOption = {
    componentRestrictions: {country: "my"}
  };

});
APIURL = 'https://www.rumah-iapp.com';
