angular.module('app.routes', ['ngAnimate'])
  .config(function ($stateProvider, $urlRouterProvider) {
    $stateProvider
      .state('search', {
          url: '/search',
          templateUrl: 'front/pages/search.html',
          controller: 'searchCtrl'
      })
      .state('property-detail', {
        url: '/property-detail/:propertyid',
        templateUrl: 'front/pages/property-detail.html',
        controller: 'propertyDetailCtrl'
      });
    $urlRouterProvider.otherwise('/search');
  });
