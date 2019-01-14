// Controller
moviesWatchedApp.controller('moviesWatchedController', ['$scope', '$http', '$window', 
function ($scope, $http, $window) {
    $scope.homePage = true;
    $scope.addMoviePage = false;
    $scope.viewMoviesPage = false;
    $scope.viewMoviesPagePhp = false;
    $scope.editMoviePage = false;
    $scope.movie = {};
    $scope.sortType = 'title';
    $scope.sortReverse = false;
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
        window.scroll(0, 0);
        $scope.homePage = false;
        $scope.addMoviePage = true;
        $scope.viewMoviesPage = false;
        $scope.viewMoviesPagePhp = false;
        $scope.editMoviePage = false;
    };

    $scope.goToViewMoviesPage = function() {
        window.scroll(0, 0);
        $scope.homePage = false;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = true;
        $scope.viewMoviesPagePhp = false;
        $scope.editMoviePage = false;

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

        $scope.homePage = false;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = false;
        $scope.viewMoviesPagePhp = false;
        $scope.editMoviePage = true;
    };

    $scope.goToViewMoviesPagePhp = function() {
        console.log('toToViewMoviesPage called');
        window.scroll(0, 0);
        $scope.homePage = false;
        $scope.addMoviePage = false;
        $scope.viewMoviesPage = false;
        $scope.viewMoviesPagePhp = true;
        $scope.editMoviePage = false;
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

 }]);