// Module
var moviesWatchedApp = angular.module('moviesWatchedApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});
