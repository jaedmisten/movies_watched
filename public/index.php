<?php include '../config/connect.php' ?>
<?php include 'addMovie.php' ?>
<?php include 'views/header.php'; ?>

        <!-- home -->
        <div id="home" ng-if="homePage" ng-init="getRandomMovies()" style="text-align: center;">
            <button class="btn btn-default" ng-click="goToAddMoviePage()">Add Movie</button><br><br>
            <button class="btn btn-default" ng-click="goToViewMoviesPage()">View Movies Watched</button><br><br>
            <button class="btn btn-default" ng-click="goToViewMoviesPagePhp()">View Movies Watched (PHP)</button>
            <table id="random-movies-table">
                <tr ng-show="randomMovieHashes.length >= 3">
                    <td><img src="[['uploads/img/' + randomMovieHashes[0] + '.jpg']]" width="100"></td>
                    <td><img src="[['uploads/img/' + randomMovieHashes[1] + '.jpg']]" width="100"></td>
                    <td><img src="[['uploads/img/' + randomMovieHashes[2] + '.jpg']]" width="100"></td>
                </tr>
                <tr ng-show="randomMovieHashes.length >= 6">
                    <td><img src="[['uploads/img/' + randomMovieHashes[3] + '.jpg']]" width="100"></td>
                    <td><img src="[['uploads/img/' + randomMovieHashes[4] + '.jpg']]" width="100"></td>
                    <td><img src="[['uploads/img/' + randomMovieHashes[5] + '.jpg']]" width="100"></td>
                </tr>
            </table>
        </div>
        
        <!-- Add movie section. -->
        <div id="add-movie" ng-if="addMoviePage">
            <div class="page-title">ADD MOVIE</div>
            <form name="addMovieForm" class="form-horizontal" action="addMovie.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="director">Director</label>
                    <input type="text" id="director" name="director" class="form-control">
                </div>
                <div class="form-group">
                    <label for="year_released">Year Released</label>
                    <input type="number" id="year_released" name="year_released" class="form-control" min="1900" max="[[currentYear]]">
                </div>
                <div class="form-group">
                    <label for="picture">Photo</label>
                    <input type="file" id="picture" name="picture" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="date_watched">Date Watched</label>
                    <input type="text" id="date_watched" name="date_watched" class="form-control" 
                            data-provide="datepicker" data-date-end-date="0d" ng-model="date_watched" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" ng-click="addMovie()" ng-disabled="addMovieForm.$invalid">Submit</button>
                    <button class="btn btn-default" ng-click="goToHomePage()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- View movies section using php query instead of angular. -->
        <div id="view-movies-php" ng-if="viewMoviesPagePhp">
            <div class="page-title">WATCHED MOVIES LIST (PHP)</div>
            <?php 
                
                try {
                    $sql = 'SELECT * FROM movies ORDER BY date_watched DESC';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $movies = $stmt->fetchAll();
                } catch (PDOException $e) {
                    echo 'IT FAILED!!!<br><br>';
                    echo '<pre>';
                    var_dump($e);
                    echo '</pre>';
                    echo $e->getMessage();
                }
            
            ?>
            <?php foreach($movies as $movie) : ?>
            <ul style="text-align: center;">
                <li style="list-style-type: none;">
                    <b><span style="font-size:25px;"><?php echo $movie['title']; ?><span></b><br>
                    <b>Director:</b> <?php echo $movie['director']; ?><br><br>
                    <b>Description:</b> <?php echo $movie['description'] ?><br><br>
                    <b>Notes:</b> <?php echo isset($movie['notes']) ? $movie['notes'] : 'No notes were entered.'; ?><br><br>
                    <b>Date Watched:</b> <?php echo date('m-d-Y', strtotime($movie['date_watched'])); ?><br><br>
                    <img src="<?php echo "uploads/img/" . $movie['hash'] . ".jpg"; ?>" 
                            alt="<?php echo $movie['title'] . ' Picture'; ?>"  
                            title="<?php echo $movie['title'] . ' Picture'; ?>" width="160">
                    <hr>
                </li>
            </ul>
            <?php endforeach ?>
        </div>
        <!-- View movies section using  angular. -->
        <div id="view-movies" ng-if="viewMoviesPage">
            <div class="page-title">WATCHED MOVIES LIST</div>
            <form class="form-inline" style="float:right;margin-bottom:30px;">
                <div class="form-group">
                    <label>Search</label> 
                    <input type="text" class="form-control" ng-model="searchFilter">
                </div>
            </form>
            <br><br>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a href="#" ng-click="sortType='title';sortReverse=!sortReverse">Title 
                                <span ng-show="sortType=='title' && !sortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="sortType=='title' && sortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>
                            <a href="#" ng-click="sortType='director';sortReverse=!sortReverse">Director
                                <span ng-show="sortType=='director' && !sortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="sortType=='director' && sortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>Description</th>
                        <th>Notes</th>
                        <th>
                            <a href="#" ng-click="sortType='year_released';sortReverse=!sortReverse">Year Released
                                <span ng-show="sortType=='year_released' && !sortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="sortType=='year_released' && sortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>
                            <a href="#" ng-click="sortType='date_watched';sortReverse=!sortReverse">Date Watched
                                <span ng-show="sortType=='date_watched' && !sortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="sortType=='date_watched' && sortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="movie in movies | orderBy: sortType : sortReverse | filter: searchFilter">
                        <td>
                            <b>[[movie.title]]</b><br><br>
                            <img ng-src="[['uploads/img/' + movie.hash + '.jpg']]" onerror="this.src='uploads/img/default.jpg'" alt="[[movie.title + ' Picture']]" 
                                    title="[[movie.title + ' Picture']]" width="140">
                        </td>
                        <td>[[movie.director]]</td>
                        <td style="white-space:pre-line;">[[movie.description]]</td>
                        <td style="white-space:pre-line;">[[movie.notes]]</td>
                        <td>[[movie.year_released]]</td>
                        <td><span class="date-watched">[[movie.date_watched]]</span></td>
                        <td>
                            <button class="btn btn-default button-spacing" style="width:67px;" ng-click="goToEditMoviePage(movie)">Edit</button>
                            <button class="btn btn-danger" ng-click="openDeleteMovieModal(movie)">Delete</button>
                        </td>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit movie section. -->
        <div id="edit-movie" ng-if="editMoviePage">
            <div class="page-title">EDIT MOVIE</div>
            <form name="editMovieForm" class="form-horizontal" action="editMovie.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" ng-value="movie.id">
                <input type="hidden" name="hash" ng-value="movie.hash">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" ng-model="movie.title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="5" ng-model="movie.description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="form-control"  rows="5" ng-model="movie.notes"></textarea>
                </div>
                <div class="form-group">
                    <label for="director">Director</label>
                    <input type="text" id="director" name="director" class="form-control" ng-model="movie.director">
                </div>
                <div class="form-group">
                    <label for="year_released">Year Released</label>
                    <input type="number" id="year_released" name="year_released" class="form-control" min="1900" max="2099" ng-model="movie.year_released">
                </div>
                <div class="form-group">
                    <label for="picture">Photo</label>
                    <input type="file" id="picture" name="picture" accept="image/*">
                    <img style="margin-top:5px;" src="[['uploads/img/' + movie.hash + '.jpg']]" alt="[[movie.title + ' Picture']]" 
                                    title="[[movie.title + ' Picture']]" width="70">
                </div>
                <div class="form-group">
                    <label for="date_watched">Date Watched</label>
                    <input type="text" id="date_watched" name="date_watched" class="form-control" data-provide="datepicker" 
                            data-date-end-date="0d" ng-model="movie.date_watched" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" ng-click="editMovie()" ng-disabled="addMovieForm.$invalid">Submit</button>
                    <button class="btn btn-default" ng-click="goToViewMoviesPage()">Cancel</button>
                </div>
            </form>
            <br>
        </div>

        <!-- Delete movie modal. -->
        <div id="deleteMovieModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Movie</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the <b>[[currentMovie.title]]</b> movie?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" ng-click="deleteMovie()">Delete</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Success deleting movie modal. -->
        <div id="movieDeletedModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="goToViewMoviesPage()"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Movie Deleted</h4>
                </div>
                <div class="modal-body">
                    <p>The <b>[[currentMovie.title]]</b> movie was successfully deleted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="goToViewMoviesPage()">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Movie delete failed modal. -->
        <div id="movieDeleteFailedModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="goToViewMoviesPage()"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Movie Delete Failed</h4>
                </div>
                <div class="modal-body">
                    <p>The <b>[[currentMovie.title]]</b> movie was unable to be deleted. The following error occured:</p> 
                    <p class="error-message">[[errorMessage]]</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="goToViewMoviesPage()">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div><!-- End angular controller --->
</div><!-- End row  -->

</div><!-- End container -->
<?php include 'views/footer.php'; ?>