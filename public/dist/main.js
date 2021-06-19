// Module
var moviesWatchedApp = angular.module('moviesWatchedApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

// Controller
moviesWatchedApp.controller('moviesWatchedController', ['$scope', '$http', '$window', 
function ($scope, $http, $window) {
    $scope.homePage = true;
    $scope.addMoviePage = false;
    $scope.viewMoviesPage = false;
    $scope.editMoviePage = false;
    $scope.manageDirectorsPage = false;
    $scope.movie = {};
    $scope.sortType = 'date_watched';
    $scope.sortReverse = true;
    $scope.directorSortType = 'last_name';
    $scope.directorSortReverse = false;
    $scope.addDirectorStatus = false;
    $scope.goToHomePage = function() {
        $window.location.href = '/';
    };

    $scope.goToAddMoviePage = function() {
        $http.get('/getDirectors.php').then(
            function(response) {
                $scope.directors = response.data;
                window.scroll(0, 0);
                $scope.homePage = false;
                $scope.addMoviePage = true;
                $scope.viewMoviesPage = false;
                $scope.editMoviePage = false;
                $scope.manageDirectorsPage = false;
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.goToViewMoviesPage = function() {
        window.scroll(0, 0);
        $scope.homePage = false;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = true;
        $scope.editMoviePage = false;
        $scope.manageDirectorsPage = false;
        $http.get('/getMovies.php').then(
            function(response) {
                $scope.movies = response.data;
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.goToEditMoviePage = function(movie) {
        window.scroll(0, 0);
        $scope.movie = movie;
        $scope.movie.year_released = parseInt($scope.movie.year_released);

        // Get the list of directors.
        $http.get('/getDirectors.php').then(
            function(response) {
                $scope.directors = response.data;
                window.scroll(0, 0);
                $scope.homePage = false;
                $scope.addMoviePage = false;
                $scope.viewMoviesPage = false;
                $scope.editMoviePage = true;        
                $scope.manageDirectorsPage = false;

                // Change format of date string from "yyyy-mm-dd" to "mm/dd/yyyy".
                var dateArray = $scope.movie.date_watched.split("-");
                $scope.movie.date_watched = dateArray[1] + "/" + dateArray[2] + "/" + dateArray[0];
            }, function() {
                console.log('Error callback');
            }
        );   
    };
 
    $scope.goToManageDirectorsPage = function() {
        // Get the list of directors.
        $http.get('/getDirectors.php').then(
            function(response) {
                $scope.directors = response.data;
                window.scroll(0, 0);
                $scope.homePage = false;
                $scope.addMoviePage = false;
                $scope.viewMoviesPage = false;
                $scope.editMoviePage = false;
                $scope.manageDirectorsPage = true;
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.openDeleteMovieModal = function(movie) {
        $scope.currentMovie = movie;
        $('#deleteMovieModal').modal('show');
    };

    $scope.deleteMovie = function() {
        //var postData = {
        //    movieId: $scope.currentMovie.id,
        //    test: 'testing'
        //}
        var postData = 'movieId=' + $scope.currentMovie.id;
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }
        $http.post('/deleteMovie.php', postData, config).then(
            function(response) {
                $('#deleteMovieModal').modal('hide');
                $('#movieDeletedModal').modal('show');
            }, function(response) {
                $scope.errorMessage = response.data;
                $('#deleteMovieModal').modal('hide');
                $('#movieDeleteFailedModal').modal('show');
            }
        );
    };

    $scope.getRandomMovies = function() {
        $http.get('/getRandomMovies.php').then(
            function(response) {
                $scope.randomMovieHashes = response.data;
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.openAddDirectorModal = function() {
        $('#addDirectorModal').modal('show');
    };

    $scope.addDirector = function() {
        var postData = {
            first_name: $scope.director.first_name,
            middle_name: $scope.director.middle_name,
            last_name: $scope.director.last_name
        }
        $http.post('/addDirector.php', postData).then(
            function(response) {
                // Add director that was just saved to the array of directors.
                var newDirector = response.data;
                var index = $scope.directors.length;  
                $scope.directors[index] = {
                    id: newDirector[0].id,
                    first_name: newDirector[0].first_name,
                    middle_name: newDirector[0].middle_name,
                    last_name: newDirector[0].last_name
                };

                // Reset new director for form.
                $scope.director.first_name = '';
                $scope.director.middle_name = '';
                $scope.director.last_name = '';
                
                $('#addDirectorModal').modal('hide');
            }, function(response) {
                console.log('Error callback');
                $scope.errorMessage = response.data;
                console.log('$scope.errorMessage', $scope.errorMessage);

            }
        );  
    };

    $scope.checkDirector = function(directorId) {
        // Return true to select the checkbox of current director in list of directors on edit page.     
        if ($scope.movie.directors.length == 1) {
            if (directorId == $scope.movie.directors[0].id) {
                return true;
            }

            return false;
        }
        
        // Loop through list of movie's directors.
        // Return true if current director in director list is a director of movie.
        // Returning true will select the director checkbox.
        for (var i = 0; i < $scope.movie.directors.length; i++) {
            if (directorId == $scope.movie.directors[i].id) {
                return true;
            }
        }

        return false;
    };

    $scope.openDeleteDirectorModal = function(director) {
        $scope.currentDirector = director;
        $('#deleteDirectorModal').modal('show');
    };

    $scope.deleteDirector = function() {
        var postData = 'directorId=' + $scope.currentDirector.id;
        console.log('postData: ', postData);
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }
        $http.post('/deleteDirector.php', postData, config).then(
            function(response) {
                $('#deleteDirectorModal').modal('hide');
                $('#directorDeletedModal').modal('show');
            }, function(response) {
                console.log('Error callback');
                $scope.errorMessage = response.data;
                console.log('$scope.errorMessage', $scope.errorMessage);

                $('#deleteDirectorModal').modal('hide');
                $('#directorDeleteFailedModal').modal('show');
            }
        );
    };

    $scope.openEditDirectorModal = function(director) {
        $scope.currentDirector = director;
        $('#editDirectorModal').modal('show');
    };

    $scope.editDirector = function() {
        if (!$scope.currentDirector.middle_name) {
            $scope.currentDirector.middle_name = '';
        }
        var postData = 'id=' + $scope.currentDirector.id + '&first_name=' + $scope.currentDirector.first_name 
                        + '&middle_name=' + $scope.currentDirector.middle_name + '&last_name=' + $scope.currentDirector.last_name;
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        };
        $http.post('/editDirector.php', postData, config).then(
            function(response) {
                $('#editDirectorModal').modal('hide');
                $('#directorEditedModal').modal('show');
            }, function(response) {
                console.log('Error callback');
                $scope.errorMessage = response.data;
                console.log('$scope.errorMessage', $scope.errorMessage);

                $('#editDirectorModal').modal('hide');
                $('#directorEditFailedModal').modal('show');
            }
        );  
    };

    $scope.getAddMoviePhotoFile = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Update img tag to have src attribute use selected image file.
                $('#add-movie-photo').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    $scope.updateEditMoviePhotoFile = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Update img tag to have src attribute use selected image file.
                angular.element('#edit-movie-photo').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    $scope.updateEditMoviePhotoText = function() {
        // Update input tag color propery so that file name is shown. 
        // By default the color property is set to transparent to not show "No file Chosen" next to input file tag.
        angular.element('#edit-movie-photo-file').css('color', 'black');
    };

 }]);