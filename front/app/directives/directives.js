angular.module('app.directives', [])

        .directive('blankDirective', [function () {

            }])

        .directive('disableTap', function ($timeout) {
            return {
                link: function () {

                    $timeout(function () {
                        document.querySelector('.pac-container').setAttribute('data-tap-disabled', 'true')
                    }, 500);
                }
            };
        })

        .directive('scrollIf', function () {
            return function (scope, element, attributes) {
                setTimeout(function () {
                    if (scope.$eval(attributes.scrollIf)) {
                        element[0].parentElement.parentElement.scrollLeft = element[0].offsetLeft - 20;
                    }
                });
            }
        })

        .directive('scrollSet', function () {
            return function (scope, element, attributes) {
                setTimeout(function () {
                    if (scope.$eval(attributes.scrollSet)) {
                        element[0].parentElement.scrollTo(0, element[0].offsetTop);
                    }
                });
            }
        })
        .directive('onErrorSrc', function () {
            return {
                link: function (scope, element, attrs) {
                    element.bind('error', function () {
                        if (attrs.src != attrs.onErrorSrc) {
                            attrs.$set('src', attrs.onErrorSrc);
                        }
                    });
                }
            }
        });