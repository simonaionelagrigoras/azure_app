<!DOCTYPE html>
<html lang="en">
<head>
    <title>APIs</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></link>

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"></link>


    <link rel="stylesheet" href="static/css/style.css"></link>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
<div id="header">
</div>
<div id="menu">
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Logs-Reader</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>">Home</a></li>
                    <li><a href="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>viewLogs.php">View Logs</a></li>
                    <li class="dropdown" style="float:right">
                        <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"-->
                           <!--aria-expanded="false"><span class="carety" id="user-account"></span></a>-->
                        <ul class="dropdown-menu">
                            <li><a href="#">Login</a></li>
                            <li><a href="#">Registration</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
</div>
<div class="container">

    <div class="main">
        <div class="row">
            <div class="col-sm-6">
                <h3>List Users</h3>
                <button type="button" id="list-users" class="btn btn-dark"><strong>Submit</strong></button>
                <hr>
                <form id="add-user">
                    <h3>Add user</h3>
                    <table>
                        <tr>
                            <td><label>User name</label></td><td><input name="user_name" class="user_input" type="text" /></td>
                        </tr>
                        <tr>
                            <td><label>User Email</label></td><td><input name="user_email" class="user_input"  type="text" /></td>
                        </tr>
                        <tr>
                            <td><label>Age</label></td><td><input name="age" class="user_input"  type="text" /></td>
                        </tr>
                        <tr>
                            <td></td><td></td>
                        </tr>
                        <tr>
                            <td><label>Authentication key</label></td><td><input name="authentication_token" class="user_input"  type="password" /></td>
                        </tr>
                    </table>
                    <button type="submit" id="create-user" class="btn btn-dark"><strong>Submit</strong></button>
                </form>
                <hr>
                <!--<form id="delete-user">
                    <h3>Delete User</h3>
                    <table>
                        <tr>
                            <td><label>User Id</label></td><td><input class="user_input"  name="user_id_to_delete" type="text" /></td>
                        </tr>
                        <tr>
                            <td><label>Authentication key</label></td><td><input name="authentication_token_2" class="user_input"  type="password" /></td>
                        </tr>
                    </table>
                    <button type="submit" id="remove-user" class="btn btn-dark"><strong>Submit</strong></button>
                </form>-->

                <form id="upload">
                    <h3>File Upload</h3>
                    <div class="fileUpload btn-primary">
                        <input name="userFile" id="userFile" multiple="" max="3" type="file" class="upload">
                    </div>
                </form>

            </div>

            <div class="col-sm-6">
                <div id="description">
                    <textarea class="list-result" disabled="disabled" placeholder="Users list"></textarea>
                </div>
            </div>
        </div>

    </div>
    <footer class="footer-bs">
        <div class="row">
            <div class="col-md-3 footer-brand animated fadeInLeft">
                <h2>Logo</h2>
                <p>Some details about game</p>
                <p>© 2018 3B, All rights reserved</p>
            </div>
            <div class="col-md-4 footer-nav animated fadeInUp">
                <div class="col-md-6">
                    <ul class="list">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contacts</a></li>
                        <li><a href="#">Terms &amp; Condition</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 footer-social animated fadeInDown">
                <h4>Follow Us</h4>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
            <div class="col-md-3 footer-ns animated fadeInRight">
                <h4>Newsletter</h4>
                <p>Please subscribe for news</p>
                <p>
                </p><div class="input-group">
                <input type="text" class="form-control" placeholder="Email address">
                <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-envelope"></span></button>
                      </span>
            </div><!-- /input-group -->
                <p></p>
            </div>
        </div>
    </footer>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        $('body').on('click','#list-users', function(){
            $.ajax({
                url: 'app/simpleApi.php',
                type: 'GET',
                //data: { "function": "getUsers"},
                success: function(response) {
                    var result = '';
                    //response = jQuery.parseJSON(response);
                    if(typeof response.error != 'undefined'){
                        alert(response.error);
                    }
                    $.each( response, function( key, value ) {
                        result = result + JSON.stringify(value);
                    });
                    $('.list-result').val(result);
                }
            });
        });

        $('body').on('submit','#add-user', function(e){
            e.preventDefault();
            var userName  = $('input[name="user_name"]').val();
            var userEmail = $('input[name="user_email"]').val();
            var age       = $('input[name="age"]').val();
            var token     = $('input[name="authentication_token"]').val();
            $.ajax({
                url: 'app/authenticationApi.php',
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify({ 'user_name': userName, 'user_email': userEmail, 'age': age }),
                headers: {
                    "Authorization": "Bearer " + token
                },
                success: function(response) {
                    var result = '';

                    if(typeof response.error != 'undefined'){
                        alert(response.error);
                    }
                    $.each( response, function( key, value ) {
                        result = result + JSON.stringify(value);
                    });
                    alert(result);

                },
                error: function(response) {
                    var result = '';
                    response = response.responseJSON;

                    if(typeof response.error != 'undefined'){
                        alert(response.error);
                    }
                }
            });
        });

        $('body').on('submit','#delete-user', function(e){
            e.preventDefault();
            var userID = $('input[name="user_id_to_delete"]').val();
            var token  = $('input[name="authentication_token_2"]').val();

            $.ajax({
                url: 'app/authenticationApi.php',
                type: 'DELETE',
                data: JSON.stringify({ 'user_id_to_delete': userID }),
                dataType: 'json',
                headers: {
                    "Authorization": "Bearer " + token
                },
                success: function(response) {
                    var result = '';

                    if(typeof response.error != 'undefined'){
                        alert(response.error);
                    }
                    $.each( response, function( key, value ) {
                        result = result + JSON.stringify(value);
                    });
                    alert(result);
                },
                error: function(response) {
                    var result = '';
                    response = response.responseJSON;

                    if(typeof response.error != 'undefined'){
                        alert(response.error);
                    }
                }
            });
        });

        jQuery(document).on("change", "#userFile", function(event)
        {
            var existing_files = jQuery('.uploaded-values').length;

            var form_data = new FormData();

            form_data.append('file',  $('#userFile')[0].files[0]);

            if(jQuery('#userFile').val()) {

                jQuery.ajax({
                    url: "app/simpleApi.php",
                    dataType: 'json',
                    cache: false,
                    /*enctype: 'multipart/form-data',*/
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'POST',
                    target:   '#targetLayer',
                    success:function (response){
                        if(typeof response.error != 'undefined'){
                            alert(response.error);
                        }
                        if(typeof response.success != 'undefined'){
                            alert(response.success);
                        }
                        jQuery('#upload')[0].reset();
                    },
                    error:function (response){
                        if(typeof response.error != 'undefined'){
                            alert(response.error);
                        }else{
                           alert('An error occurred during the file upload. Please try again');
                        }
                        jQuery('#upload')[0].reset();
                    },
                    resetForm: true
                });
                return false;
            }

        })

    })
</script>
</body>
</html>