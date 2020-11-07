angular.module('app.services', [])

.factory('BlankFactory', [function(){

}])

.service('Auth', [ '$q', function($q){

    var s = {};

    s.checkUserLogin = function () {
        var flg = false
        if(window.localStorage.userData && window.localStorage.token){
            flg = true;
        }
        return flg;
    };

    s.getuserData = function () {
        var id = false;
        if(window.localStorage.userData){
            id = JSON.parse(window.localStorage.userData);
        }
        return id;
    }

    s.getToken = function () {
        var token = false;
        if(window.localStorage.token){
            token = window.localStorage.token;
        }
        return token;
    }

    return s;
}])

.service('Helper', [ '$q', function($q){

    var s = {};

    s.isJson = function(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    };

    s.validEmail = function(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    };

    s.numberWithCommas = function (x) {
        if(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }

    s.toObject = function(arr) {
        var rv = {};
        for (var i = 0; i < arr.length; ++i)
            rv[i] = arr[i];
        return rv;
    }

    Math.easeInOutQuad = function (t, b, c, d) {
        t /= d/2;
        if (t < 1) return c/2*t*t + b;
        t--;
        return -c/2 * (t*(t-2) - 1) + b;
    };

    s.scrollTo = function(element, to, duration) {
        var start = element.scrollTop,
            change = to - start,
            currentTime = 0,
            increment = 20;

        var animateScroll = function(){
            currentTime += increment;
            var val = Math.easeInOutQuad(currentTime, start, change, duration);
            element.scrollTop = val;
            if(currentTime < duration) {
                setTimeout(animateScroll, increment);
            }
        };
        animateScroll();
    }

    return s;
}]);
