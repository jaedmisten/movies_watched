// Controller
moviesWatchedApp.controller('moviesWatchedController', ['$scope', '$http', '$window', 
function ($scope, $http, $window) {
    $scope.homePage = true;
    $scope.addMoviePage = false;
    $scope.viewMoviesPage = false;
    $scope.viewMoviesPagePhp = false;
    $scope.editMoviePage = false;
    $scope.manageDirectorsPage = false;
    $scope.reportsPage = false;
    $scope.movie = {};
    $scope.sortType = 'title';
    $scope.sortReverse = false;
    $scope.directorSortType = 'last_name';
    $scope.directorSortReverse = false;
    $scope.addDirectorStatus = false;
    $scope.currentYear = (new Date()).getFullYear();
    console.log('currentYear: ', $scope.currentYear);

    console.log('moviesWatchedController called and updated');
    
    $scope.goToHomePage = function() {
        console.log('goToHomePage called')
        $window.location.href = '/';
        /*
        $scope.homePage = true;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = false;
        $scope.viewMoviesPagePhp = false;
        $scope.editMoviePage = false;
        */
    };

    $scope.goToAddMoviePage = function() {
        console.log('goToAddMoviePage called');

        $http.get('/getDirectors.php').then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);
                $scope.directors = response.data;
                console.log('$scope.directors: ', $scope.directors);
                console.log(typeof($scope.directors));

                window.scroll(0, 0);
                $scope.homePage = false;
                $scope.addMoviePage = true;
                $scope.viewMoviesPage = false;
                $scope.viewMoviesPagePhp = false;
                $scope.editMoviePage = false;
                $scope.manageDirectorsPage = false;
                $scope.reportsPage = false;
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
        $scope.viewMoviesPagePhp = false;
        $scope.editMoviePage = false;
        $scope.manageDirectorsPage = false;
        $scope.reportsPage = false;

        $http.get('/getMovies.php').then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);
                $scope.movies = response.data;
                console.log('$scope.movies: ', $scope.movies);
                console.log(typeof($scope.movies));
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.goToEditMoviePage = function(movie) {
        console.log('movie: ', movie);
        window.scroll(0, 0);
        $scope.movie = movie;
        $scope.movie.year_released = parseInt($scope.movie.year_released);
        $scope.movie.date_watched = $scope.movie.date_watched.replace(/-/g, '/');
        console.log('$scope.movie: ', $scope.movie);

        // Get the list of directors.
        $http.get('/getDirectors.php').then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);
                $scope.directors = response.data;
                console.log('$scope.directors: ', $scope.directors);
                console.log(typeof($scope.directors));

                window.scroll(0, 0);
                $scope.homePage = false;
                $scope.addMoviePage = false;
                $scope.viewMoviesPage = false;
                $scope.viewMoviesPagePhp = false;
                $scope.editMoviePage = true;        
                $scope.manageDirectorsPage = false;
                $scope.reportsPage = false;
            }, function() {
                console.log('Error callback');
            }
        );

        
    };

    $scope.goToViewMoviesPagePhp = function() {
        console.log('goToViewMoviesPage called');
        window.scroll(0, 0);
        $scope.homePage = false;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = false;
        $scope.viewMoviesPagePhp = true;
        $scope.editMoviePage = false;
        $scope.manageDirectorsPage = false;
        $scope.reportsPage = false;
    };
 
    $scope.goToManageDirectorsPage = function() {
        console.log('goToManageDirectorsPage');
        
        // Get the list of directors.
        $http.get('/getDirectors.php').then(
            function(response) {
                //console.log('Success Callback');
                //console.log('response: ', response);
                //console.log('data: ', response.data);
                $scope.directors = response.data;
                console.log('$scope.directors: ', $scope.directors);
                console.log(typeof($scope.directors));

                window.scroll(0, 0);
                $scope.homePage = false;
                $scope.addMoviePage = false;
                $scope.viewMoviesPage = false;
                $scope.viewMoviesPagePhp = false;
                $scope.editMoviePage = false;
                $scope.manageDirectorsPage = true;
                $scope.reportsPage = false;
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.goToReportsPage = function() {
        console.log('goToReportPage called');
        window.scroll(0, 0);
        $scope.homePage = false;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = false;
        $scope.viewMoviesPagePhp = false;
        $scope.editMoviePage = false;
        $scope.manageDirectorsPage = false;
        $scope.reportsPage = true;
    };

    $scope.addMovie = function() {
        console.log('movie: ', $scope.movie);
    };

    $scope.openDeleteMovieModal = function(movie) {
        console.log('openDeleteModal called');
        $scope.currentMovie = movie;
        console.log($scope.currentMovie);
        $('#deleteMovieModal').modal('show');
    };

    $scope.deleteMovie = function() {
        console.log('currentMovie: ', $scope.currentMovie);

        //var postData = {
        //    movieId: $scope.currentMovie.id,
        //    test: 'testing'
        //}
        var postData = 'movieId=' + $scope.currentMovie.id;
        console.log('postData: ', postData);
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }
        $http.post('/deleteMovie.php', postData, config).then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);

                $('#deleteMovieModal').modal('hide');
                $('#movieDeletedModal').modal('show');
            }, function(response) {
                console.log('Error callback');
                $scope.errorMessage = response.data;
                console.log('$scope.errorMessage', $scope.errorMessage);

                $('#deleteMovieModal').modal('hide');
                $('#movieDeleteFailedModal').modal('show');
            }
        );
    };

    $scope.getRandomMovies = function() {
        $http.get('/getRandomMovies.php').then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);
                $scope.randomMovieHashes = response.data;
                console.log('$scope.randomMovies: ', $scope.randomMovieHashes);
                console.log(typeof($scope.randomMovieHashes));
            }, function() {
                console.log('Error callback');
            }
        );
    };

    $scope.openAddDirectorModal = function() {
        console.log('openAddDirectorModal called');
        $('#addDirectorModal').modal('show');
    };

    $scope.addDirector = function() {
        console.log('addDirector called');

        var postData = {
            first_name: $scope.director.first_name,
            middle_name: $scope.director.middle_name,
            last_name: $scope.director.last_name
        }
        console.log('postData: ', postData);

        $http.post('/addDirector.php', postData).then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);

                // Add director that was just saved to the array of directors.
                var newDirector = response.data;
                var index = $scope.directors.length;  
                $scope.directors[index] = {
                    id: newDirector[0].id,
                    first_name: newDirector[0].first_name,
                    middle_name: newDirector[0].middle_name,
                    last_name: newDirector[0].last_name
                };
                console.log('directors: ', $scope.directors);

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
        
        // Loop through list of movie's directors
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
        console.log('openDeleteDirectorModal called');
        $scope.currentDirector = director;
        console.log($scope.currentDirector);
        $('#deleteDirectorModal').modal('show');
    };

    $scope.deleteDirector = function() {
        console.log('currentDirector: ', $scope.currentDirector);

        var postData = 'directorId=' + $scope.currentDirector.id;
        console.log('postData: ', postData);
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }
        $http.post('/deleteDirector.php', postData, config).then(
            function(response) {
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);

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
        console.log('openEditDirectorModal called');
        $scope.currentDirector = director;
        console.log('currentDirector: ', $scope.currentDirector);
        $('#editDirectorModal').modal('show');
    };

    $scope.editDirector = function() {
        console.log('editDirector called');

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
                console.log('Success Callback');
                console.log('response: ', response);
                console.log('data: ', response.data);
                
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
        console.log('getAddMoviePhotoFile called');
        console.log(input);
        console.log(input.files)

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
        console.log('updateEditMoviePhotoFile called');
        console.log(input);
        console.log(input.files)

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

    $scope.exportToCSV = function() {
        console.log('exportToCSV called');
    };


 }]);