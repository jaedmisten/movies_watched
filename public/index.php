<?php ini_set('display_errors', 0); ?>
<?php include '../config/connect.php' ?>
<?php include 'addMovie.php' ?>
<?php include 'views/header.php'; ?>

        <!-- home -->
        <div id="home" ng-if="homePage" ng-init="getRandomMovies()" style="text-align: center;">
            <button class="btn btn-default" ng-click="goToAddMoviePage()">Add Movie</button><br><br>
            <button class="btn btn-default" ng-click="goToViewMoviesPage()">View Movies Watched</button><br><br>
            <button class="btn btn-default" ng-click="goToManageDirectorsPage()">Manage Directors</button><br><br>
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
                    <textarea id="notes" name="notes" class="form-control" rows="8"></textarea>
                </div>
                <div class="form-group">
                    <label>Director</label><br>
                    Please select the director of this movie. Multiple directors can be see selected.
                    <br>
                    <div class="add-directors-dev">
                        <div style="margin-left:5px;">
                            <span ng-repeat="director in directors | orderBy:'last_name'">
                                <input type="checkbox" name="director[]" value="[[director.id]]"> [[director.first_name]] [[director.middle_name]] [[director.last_name]]<br>
                            </span>
                        </div>
                    </div>
                    Do not see the director of this movie in the list? Click the following link to add him or her: 
                    <a href="" ng-click="openAddDirectorModal()">Add Director</a>
                </div>
                <div class="form-group">
                    <label for="year_released">Year Released</label>
                    <input type="number" id="year_released" name="year_released" class="form-control" min="1900" max="[[currentYear]]">
                </div>
                <div class="form-group">
                    <label for="picture">Photo</label>
                    <input style="margin-bottom:4px;" type="file" id="picture" name="picture" accept="image/*" 
                           onchange="angular.element(this).scope().getAddMoviePhotoFile(this)">
                    <img id="add-movie-photo" src="uploads/img/default.jpg" alt="Movie Photo" width="70">
                </div>
                <div class="form-group">
                    <label for="date_watched">Date Watched</label>
                    <input type="text" id="date_watched" name="date_watched" class="form-control" 
                            data-provide="datepicker" data-date-end-date="0d" ng-model="date_watched" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" ng-disabled="addMovieForm.$invalid">Submit</button>
                    <button class="btn btn-default" ng-click="goToHomePage()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- View movies section using angular. -->
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
                        <th style="width:175px;">
                            <a href="#" ng-click="sortType='directors[0].last_name';sortReverse=!sortReverse">Director
                                <span ng-show="sortType=='directors[0].last_name' && !sortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="sortType=='directors[0].last_name' && sortReverse" class="fa fa-caret-down"></span>
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
                            <img ng-src="[['uploads/img/' + movie.hash + '.jpg']]" onerror="this.src='uploads/img/default.jpg'" 
                                    alt="[[movie.title + ' Picture']]" title="[[movie.title + ' Picture']]" width="140">
                        </td>
                        <td><span ng-repeat="director in movie.directors">[[director.first_name]] [[director.middle_name]] [[director.last_name]]<br></span></td>
                        <td style="white-space:pre-line;">[[movie.description]]</td>
                        <td style="white-space:pre-line;">[[movie.notes]]</td>
                        <td>[[movie.year_released]]</td>
                        <td><span class="date-watched">[[movie.date_watched]]</span></td>
                        <td>
                            <button class="btn btn-default button-spacing" style="width:83px;" ng-click="goToEditMoviePage(movie)"><i class="far fa-edit"></i> Edit</button>
                            <button class="btn btn-danger" ng-click="openDeleteMovieModal(movie)"><i class="far fa-trash-alt"></i> Delete</button>
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
                    <textarea id="notes" name="notes" class="form-control"  rows="8" ng-model="movie.notes"></textarea>
                </div>
                <div class="form-group">
                    <label>Director</label><br>
                    Please select the director of this movie. Multiple directors can be see selected.
                    <br>
                    <div class="add-directors-dev">
                        <div style="margin-left:5px;">
                            <span ng-repeat="director in directors | orderBy:'last_name'">
                                <input type="checkbox" name="director[]" value="[[director.id]]" ng-checked="checkDirector(director.id)"> [[director.first_name]] [[director.middle_name]] [[director.last_name]]
                                <br>
                            </span>
                        </div>
                    </div>
                    Do not see the director of this movie in the list? Click the following link to add him or her: 
                    <a href="" ng-click="openAddDirectorModal()">Add Director</a>
                </div>
                <div class="form-group">
                    <label for="year_released">Year Released</label>
                    <input type="number" id="year_released" name="year_released" class="form-control" min="1900" max="2099" ng-model="movie.year_released">
                </div>
                <div class="form-group">
                    <label for="edit-movie-picture">Photo</label>
                    <input type="file" id="edit-movie-photo-file" name="picture" accept="image/*" title=""
                                    onchange="angular.element(this).scope().updateEditMoviePhotoFile(this)" ng-click="updateEditMoviePhotoText()">
                    <img id="edit-movie-photo" style="margin-top:5px;" ng-src="[['uploads/img/' + movie.hash + '.jpg']]" onerror="this.src='uploads/img/default.jpg'"  
                                    alt="[[movie.title + ' Photo']]" title="[[movie.title + ' Photo']]" width="70">
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

        <!-- Manage directors section -->
        <div id="manage-directors" ng-if="manageDirectorsPage">
            <div class="page-title">MANAGE DIRECTORS</div>
            <form class="form-inline" style="float:right;margin-bottom:30px;">
                <div class="form-group">
                    <button class="btn btn-default" style="margin-right:10px;" ng-click="openAddDirectorModal()">Add Director</button>
                    <label>Search</label> 
                    <input type="text" class="form-control" ng-model="searchFilter">
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a href="#" ng-click="directorSortType='first_name';directorSortReverse=!directorSortReverse">First Name
                                <span ng-show="directorSortType=='first_name' && !directorSortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="directorSortType=='first_name' && directorSortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>
                            <a href="#" ng-click="directorSortType='middle_name';directorSortReverse=!directorSortReverse">Middle Name
                                <span ng-show="directorSortType=='middle_name' && !directorSortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="directorSortType=='middle_name' && directorSortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>
                            <a href="#" ng-click="directorSortType='last_name';directorSortReverse=!directorSortReverse">Last Name
                                <span ng-show="directorSortType=='last_name' && !directorSortReverse"  class="fa fa-caret-up"></span>
                                <span ng-show="directorSortType=='last_name' && directorSortReverse" class="fa fa-caret-down"></span>
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="director in directors | orderBy: directorSortType : directorSortReverse | filter: searchFilter">
                        <td>[[director.first_name]]</td>
                        <td>[[director.middle_name]]</td>
                        <td>[[director.last_name]]</td>
                        <td style="width:200px;">
                            <button class="btn btn-default" style="width:83px;" ng-click="openEditDirectorModal(director)">
                                <i class="far fa-edit"></i> Edit</button>
                            <button class="btn btn-danger" ng-click="openDeleteDirectorModal(director)">
                                <i class="far fa-trash-alt"></i> Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Delete movie modal -->
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
                    <button type="button" class="btn btn-danger" ng-click="deleteMovie()"><i class="far fa-trash-alt"></i> Delete</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Success deleting movie modal -->
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

        <!-- Movie delete failed modal -->
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

        <!-- Add director modal -->
        <div id="addDirectorModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Director</h4>
                </div>
                <div class="modal-body">
                    <form name="addDirectorForm">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" ng-model="director.first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" id="middle_name" name="first_name" class="form-control" ng-model="director.middle_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" ng-model="director.last_name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" ng-click="addDirector()" 
                            ng-disabled="(director.first_name == null) || (director.last_name == null)">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Delete director modal -->
        <div id="deleteDirectorModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Director</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <b>[[currentDirector.first_name]] [[currentDirector.middle_name]] [[currentDirector.last_name]]</b> 
                       from the list of directors.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" ng-click="deleteDirector()"><i class="far fa-trash-alt"></i> Delete</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Success deleting director modal -->
        <div id="directorDeletedModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Director Deleted</h4>
                </div>
                <div class="modal-body">
                    <p><b>[[currentDirector.first_name]] [[currentDirector.middle_name]] [[currentDirector.last_name]]</b> was successfully deleted from the list of directors.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="goToManageDirectorsPage()">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Director delete failed modal -->
        <div id="directorDeleteFailedModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Director Delete Failed</h4>
                </div>
                <div class="modal-body">
                    <p>The <b>[[currentDirector.first_name]] [[currentDirector.middle_name]] [[currentDirector.last_name]]</b> was unable to be deleted from the list of directors. The following error occured:</p>
                    <p class="error-message">[[errorMessage]]</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="goToManageDirectorsPage()">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Edit director modal -->
        <div id="editDirectorModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Director</h4>
                </div>
                <div class="modal-body">
                    <form name="addDirectorForm">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" ng-model="currentDirector.first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" id="middle_name" name="first_name" class="form-control" ng-model="currentDirector.middle_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" ng-model="currentDirector.last_name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" ng-click="editDirector()" 
                            ng-disabled="(currentDirector.first_name == null) || (currentDirector.last_name == null)">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Success editing director modal -->
        <div id="directorEditedModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Director Edited</h4>
                </div>
                <div class="modal-body">
                    <p><b>[[currentDirector.first_name]] [[currentDirector.middle_name]] [[currentDirector.last_name]]</b> was successfully edited.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="goToManageDirectorsPage()">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Director edit failed modal -->
        <div id="directorEditFailedModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click=""><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Director Edit Failed</h4>
                </div>
                <div class="modal-body">
                    <p>The <b>[[currentDirector.first_name]] [[currentDirector.middle_name]] [[currentDirector.last_name]]</b> was unable to be edited. The following error occured:</p>
                    <p class="error-message">[[errorMessage]]</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="goToManageDirectorsPage()">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </div><!-- End angular controller --->
</div><!-- End row  -->

</div><!-- End container -->
<?php include 'views/footer.php'; ?>