angular.module('app.directives', [])
  .factory('BackButtonOverride', function ($rootScope, $ionicPlatform) {
  var results = {};

  function _setup($scope, customBackFunction) {

    var oldSoftBack = $rootScope.$ionicGoBack;

    $rootScope.$ionicGoBack = function () {
      customBackFunction();
    };
    var deregisterSoftBack = function () {
      $rootScope.$ionicGoBack = oldSoftBack;
    };
    var deregisterHardBack = $ionicPlatform.registerBackButtonAction(
      customBackFunction, 101
    );
    $scope.$on('$destroy', function () {
      deregisterHardBack();
      deregisterSoftBack();
    });
  }
  results.setup = _setup;
  return results;
});
