angular.module('app.controllers', [])
.controller('menuCtrl', ['$rootScope', '$scope', '$state', 'Data', function ($rootScope, $scope, $state, Data) {
  $rootScope.profile = Data.getProfile();
}]);

