angular.module('app.controllers').controller('searchCtrl', ['$rootScope', '$scope', '$state', '$stateParams', '$timeout', '$compile', 'Auth', 'Data', 'Web', function ($rootScope, $scope, $state, $stateParams, $timeout, $compile, Auth, Data, Web) {
  $scope.locationData = false;
  $scope.mapview = false;
  $scope.formData = {};
  $scope.popularSearch = {
    'Ampang': {lat:3.1598207,long:101.7512435},
    'Bandar Sunway': {lat:3.0696905,long:101.6000078},
    'Bandar Utama': {lat:3.1378803,long:101.5930498},
    'Bangsar': {lat:3.1300307,long:101.6686509},
    'Bukit Jalil': {lat:3.0561539,long:101.6730973}
  };
  $scope.properties = [];
  $scope.apiurl = APIURL;
  $scope.propertyTypes = ['House','Flat','Apartment','Condominium','Studio','Townhouse','Terrace','Semi-D','Bungalow'];
  $scope.roomTypes = ['Single','Medium','Master','Duplex','Entire Unit'];
  $scope.preferences = ['Mixed Gender','Male','Female'];
  $scope.amenities = ['Air-conditioning','Wifi','Washing Machine','Cooking Allowed','Individual Meter Reader', 'Mini Market', 'Swimming Pool', 'Gymnasium', '24hrs Security', 'Playground', 'Sarau'];
  $scope.commutes = ['Nearby','MRT','LRT','KTM','Monorail','Bus Station'];
  $scope.markers = [];
  $scope.setValue = function (field, value) {
    $scope.filter.price.isset = true;
    $scope.filter.distance.isset = true;
    $scope.formData[field] = value;
  };

  $scope.init = function () {
      var query = $stateParams.query;
      if(query && query != ''){
          $scope.formData = $scope.popularSearch[query];
          $scope.formData.location = query;
          $scope.search('init');
      }else{
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function (position) {
                  $scope.formData.lat = position.coords.latitude;
                  $scope.formData.long = position.coords.longitude;
                  $scope.search('init');
              });
          } else {
              $scope.formData.location = 'kuala lumpur';
              $scope.formData.lat = 3.1554724;
              $scope.formData.long = 101.655401;
              $scope.search('init');
          }

          $timeout(function () {
              if (!$scope.formData.lat) {
                  $scope.formData.location = 'kuala lumpur';
                  $scope.formData.lat = 3.1554724;
                  $scope.formData.long = 101.655401;
                  $scope.search('init');
              }
          }, 500);
      }
  };

  $scope.addCommute = function (value) {
    $scope.filter.price.isset = true;
    $scope.filter.distance.isset = true;
    if(!$scope.formData.commute){
      $scope.formData.commute = [];
    }
    if($scope.formData.commute.includes(value)){
      $scope.formData.commute = $scope.formData.commute.filter(function(e) { return e !== value });
    } else {
      $scope.formData.commute.push(value);
    }
  };

  $scope.addAmenities = function (value) {
    $scope.filter.price.isset = true;
    $scope.filter.distance.isset = true;
    if(!$scope.formData.amenities){
      $scope.formData.amenities = [];
    }
    if($scope.formData.amenities.includes(value)){
      $scope.formData.amenities = $scope.formData.amenities.filter(function(e) { return e !== value });
    } else {
      $scope.formData.amenities.push(value);
    }
  };

  $scope.clearSelection = function (input) {
    delete $scope.formData[input];
  };

  $scope.search = function(type){
    //if($scope.formData.search && $scope.formData.search.length > 1) {
      if($scope.locationData){
        $scope.formData.lat = $scope.locationData.latitude;
        $scope.formData.long = $scope.locationData.longitude;
      }
      var data = angular.copy($scope.formData);
      if(data.commute && data.commute.length > 0){
        data.commute = data.commute.toString();
      }
      if(data.amenities && data.amenities.length > 0){
        data.amenities = data.amenities.toString();
      }
      if($scope.filter.price.isset) {
        data.price = $scope.filter.price.minValue + ',' + $scope.filter.price.maxValue;
      }
      if($scope.filter.distance.isset) {
        data.distance = $scope.filter.distance.value;
      }
      if(data.location && data.location.formatted_address) {
        data.lat = $scope.formData.location.geometry.location.lat();
        data.long = $scope.formData.location.geometry.location.lng();
        data.location = $scope.formData.location.vicinity;
      }
      $scope.searching = true;
      Web.search(data, function (response) {
        $scope.properties = response.data;
        if(type === 'init'){
            $scope.changeView();
        }
        if($scope.mapview){
            $scope.initMapData();
        }
      }, function (e) {
        console.log(e);
      });
    //}
  };

  $scope.useCrtLocation = function () {
    $scope.userCrtLocation = true;
    $scope.search();
  };

  $scope.reloadMarker = function (id) {

  };

  $scope.filter = {
    display: false,
    show: function () {
      this.display = true;
    },
    hide: function () {
      this.display = false;
    },
    price: {
      isset: false,
      minValue: 1000,
      maxValue: 4000,
      options: {
        floor: 500,
        ceil: 10000,
        step: 500,
        translate: function(value) {
          return 'RM' + value;
        }
      }
    },
    distance: {
      isset: false,
      value: 30,
      options: {
        floor: 0,
        ceil: 50,
        step: 1,
        precision: 1,
        showSelectionBar: true,
        translate: function(value) {
          return 'km ' + value;
        }
      }
    },
    apply: function () {
      this.hide();
      $scope.search();
    },
    reset: function () {
      this.distance.value = 30;
      this.price.minValue = 1000;
      this.price.maxValue = 4000;
      var formData = angular.copy($scope.formData);
      $scope.formData = {};
      $scope.filter.price.isset = false;
      $scope.filter.distance.isset = false;
      $scope.formData.search = formData.search;
      $scope.formData.lat = formData.lat;
      $scope.formData.long = formData.long;
    }
  };
  var infoWindows=[];
  $scope.changeView = function () {
    $scope.mapview = !$scope.mapview;
    if($scope.mapview){
      $scope.initMapData();
    }
  };

  $scope.getAvailability = function (date) {
    return moment(date).format('MMM, YYYY');
  };

  $scope.getSimplifyLocation = function (location) {
    location = location.split(',');
    if(location.length > 2) {
      location = location.slice(Math.max(location.length - 2, 1));
    }
    location = location.toString();
    return location;
  };

  /* ------ map code ------- */

  $scope.goToBack = function () {
    if($scope.mapview){
      $scope.mapview = false;
    }else{
      $rootScope.goBack();
    }
  };

  $scope.initMapData = function () {
    var mapOptions = {
      zoom: 12,
      center: new google.maps.LatLng($scope.formData.lat, $scope.formData.long),
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    angular.forEach($scope.properties, function (v,k) {
      var marker = new google.maps.Marker({
        map:map,
        position: new google.maps.LatLng(v.latitude, v.longitude),
        icon : {	url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(getSvg({amount:parseInt(v.price)})), scaledSize: new google.maps.Size(70, 35) },
        slug: v.id
      });

      v.location = $scope.getSimplifyLocation(v.location);

      if(v.title.length > 12){
        v.title = v.title.substr(0,10)+"..";
      }

      var content = '<div class="flex" style="width: 250px;margin-left: -10px;position: relative;">';

      if(v.pictures && v.pictures.image) {
        content += '<img src="'+v.pictures.image+'" class="width-50" style="height: 146px;" ng-click="openDetail(' + v.id + ')">';
      }else{
        content += '<img src="img/sample/' + (k + 1) + '.png" class="width-50" style="height: 146px;" ng-click="openDetail(' + v.id + ')">';
      }

      content += '<div class="flex flex-dc pdt-15 pdl-10 pdr-5" ng-click="openDetail('+v.id+')">' +
            '<label class="font-16 fw-600">'+v.title+'</label>' +
            '<span class="opacity-50">'+v.location+'</span>' +
            '<label class="opacity-60 mrt-5 font-12">'+v.bedroomBR+' | '+ v.bathroom+'BA</label>' +
            '<label class="opacity-60">'+parseInt(v.size_of_area)+' Sq. ft.</label>' +
            '<label class="fw-600 font-16 mrt-5">RM '+v.price+'</label>' +
          '</div>' +
        '</div>';

      content = $compile(content)($scope);

      var infowindow = new google.maps.InfoWindow({ zIndex: 1000});
      infoWindows.push(infowindow);
      $scope.markers.push(marker);
      google.maps.event.addListener(marker, 'click', (function(marker, content, infowindow){
        return function(){
          for(x=0;x<infoWindows.length;x++){	infoWindows[x].close(); }
          infowindow.setContent(content[0]);
          infowindow.open(map,marker);
        };
      })(marker, content,infowindow));
    });
  };

  $scope.openDetail = function (id) {
    $state.go('property-detail',{propertyid: id});
  };

  function getSvg(data) {
    return '<?xml version="1.0" encoding="UTF-8"?>\n' +
      '<svg width="53px" height="24px" viewBox="0 0 53 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n' +
      '    <!-- Generator: Sketch 51.3 (57544) - http://www.bohemiancoding.com/sketch -->\n' +
      '    <title>pin1</title>\n' +
      '    <desc>Created with Sketch.</desc>\n' +
      '    <defs></defs>\n' +
      '    <g id="Vendor" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\n' +
      '        <g id="Map-View" transform="translate(-145.000000, -666.000000)">\n' +
      '            <g id="pin1" transform="translate(146.000000, 667.000000)">\n' +
      '                <path d="M30.1439826,17.6813303 L26.5956061,22.1919601 L22.818459,17.6813303 L3,17.6813303 C1.06700338,17.6813303 -0.5,16.1143269 -0.5,14.1813303 L-0.5,3 C-0.5,1.06700338 1.06700338,-0.5 3,-0.5 L48,-0.5 C49.9329966,-0.5 51.5,1.06700338 51.5,3 L51.5,14.1813303 C51.5,16.1143269 49.9329966,17.6813303 48,17.6813303 L30.1439826,17.6813303 Z" id="Combined-Shape" stroke="#FFFFFF" fill="#BF1E2E"></path>\n' +
      '                <text id="RM1000" font-family="Helvetica" font-size="10" font-weight="normal" fill="#FFFFFF">\n' +
      '                    <tspan x="6.60107422" y="12">RM'+data.amount+'</tspan>\n' +
      '                </text>\n' +
      '            </g>\n' +
      '        </g>\n' +
      '    </g>\n' +
      '</svg>';
  }

  $scope.changeMarkerFavourite = function (key, favvalue) {
    $scope.properties[key].favourite = favvalue;
    $scope.clearOverlays();
    $scope.initMapData();
  };

  $scope.clearOverlays = function() {
    for (var i = 0; i < $scope.markers.length; i++ ) {
      $scope.markers[i].setMap(null);
    }
    $scope.markers.length = 0;
  };

    $scope.init();

  /* ----------------------------- */

}]);
