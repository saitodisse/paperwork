<!DOCTYPE html>
<html lang="en">
    <head>
        @include('partials/header-sidewide-meta')
        [[ HTML::style('css/themes/paperwork-v1.min.css') ]]
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="paperwork-logo navbar-brand transition-effect" href="[[ URL::route('/') ]]">
                        <img src="[[ asset('images/navbar-logo.png') ]]"> Paperwork
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid" id="step1">
            <h1>Check if this is latest release</h1>
            <?php
                list($lastCommitOnInstall, $upstreamLatest, $lastCommitTimestamp, $upstreamTimestamp) = PaperworkHelpers::getHashes();
            ?>
            @if(empty($lastCommitOnInstall))
                @if(function_exists('curl_init'))
                <p>Paperwork cannot connect to Github to check the latest version. Please make sure that you are installing the latest version. </p>
                @else
                <p>Paperwork cannot connect to Github to check the latest version because you don't have php5-curl installed on your system. A solution to this can be installling the curl PHP extension. This not mandatory however. </p>
                @endif
            @elseif(strtotime($lastCommitTimestamp) > strtotime($upstreamTimestamp))
                <p>It seems like you have done some changes to the Paperwork code. Before opening a new issue, please check if this issue is present in the official source code available in our Github repository. </p>
            @else
                <p>It seems like thisis not the latest version of Paperwork. Please consider installing a newer version. </p>
            @endif
            <button type="button" id="step1_submit" class="btn btn-lg btn-primary btn-block">Next</button>
        </div> <!-- page 1 (update check) -->
        <div class="container-fluid hidden" id="step2">
            <h1>Checking all composer dependencies are installed...</h1>
            <button type="button" id="step2_submit" class="btn btn-lg btn-primary btn-block">Next</button>
        </div> <!-- page 2 (dependency check - composer) -->
        <div class="container-fluid hidden" id="step3">
            <h1>Check all npm dependencies are installed...</h1>
            <button type="button" id="step3_submit" class="btn btn-lg btn-primary btn-block">Next</button>
        </div> <!-- page 3 (dependency check - npm) -->
        <div class="container-fluid hidden" id="step4">
            <!-- TODO: Check if mysql is installed -->
            <h1>Check if mysql is running and is correct</h1>
        </div> <!-- page 4 (database options) -->
        <div class="container-fluid hidden" id="step5">
            <div id="registration_error" class="hidden">
                <p>The registration for your account has failed. Please finish setting up your installation and try registering again, by clicking <a href="finish" id="finish_install_register_fail">here</a>. 
            </div>
            @include("partials/registration-form", array('back' => false, 'frominstaller' => true))
        </div> <!-- page 5 (first user registration) -->
        <div style="height:25px;position:fixed;bottom:0;width:100%">
            <span style="height:15px;width:100%">Installing and configuring Paperwork...</span><br>
            <span id="progress_bar" style="width:0%;background-color:red;height:5px;margin-bottom:5px;position:fixed"></span>
        </div> <!-- progress bar -->
        [[ HTML::script('js/jquery.min.js') ]]
        [[-- HTML::script('js/bootstrap.min.js') --]]
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            [[ HTML::script('js/ltie9compat.min.js') ]]
        <![endif]-->
        <!--[if lt IE 11]>
            [[ HTML::script('js/ltie11compat.js') ]]
        <![endif]-->
        <script type="text/javascript">
            $("#step1_submit").click(function() {
                $("#step1").fadeOut("slow", function() {
                    $("#step1").addClass("hidden");
                    $("#step2").removeClass("hidden");
                    $("#progress_bar").width("20%");
                });
            });
            $("#step2_submit").click(function() {
                $("#step2").fadeOut("slow", function() {
                    $("#step2").addClass("hidden");
                    $("#step3").removeClass("hidden");
                    $("#progress_bar").width("40%");
                });
            });
            $("#step3_submit").click(function() {
                $("#step3").fadeOut("slow", function() {
                    $("#step3").addClass("hidden");
                    var data = "";
                    $.ajax({
                        type: "POST",
                        url: "install/checkdb",
                        data: data,
                        success: function() {
                            console.log($("#step4").append("<button id='step4_submit' type='button' class='btn btn-lg btn-primary btn-block'>Next</button>"));
                            console.log("test");
                            $("#step4").removeClass("hidden");
                            $("#progress_bar").width("60%");
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                    return false;
                });
            });
            $("#step4_submit").click(function() {
                /*var dbserver = $("input#dbserver").val();
                var dbuser = $("input#dbusername").val();
                var dbpassword = $("input#dbpassword").val();
                var dataString = 
                    'dbserver=' + dbserver + '&dbusername=' + dbusername + '&dbpassword=' + dbpassword;
                $.ajax({
                    type: "POST",
                    url: "install/checkdb",
                    data: dataString,
                    success: function() {
                        $("#step4").fadeOut("slow", function() {
                            $("#step4").addClass("hidden");
                            $("#step5").removeClass("hidden");
                            $("#progress_bar").width("80%");
                        });
                    }
                });*/
                //return false;
 /*               var data = "";
                $.ajax({
                    type: "POST",
                    url: "install/checkdb",
                    data: data,
                    success: function() {
                        $("#step4").fadeOut("slow", function() {
                            $("#step4").addClass("hidden");
                            $("#step5").removeClass("hidden");
                            $("#progress_bar").width("80%");
                        });
                    },
                    error: function() {
                        alert("Error");
                    }
                });
                return false;*/
                $("#step4").fadeOut("slow", function() {
                    $("#step4").addClass("hidden");
                    $("#step5").removeClass("hidden");
                    $("#progress_bar").width("80%");
                });
          });
            $("#step5_submit").click(function() {
                $("#step5").fadeOut("slow", function() {
                    $("#step5").addClass("hidden");
                });
                //$("#step5").addClass("hidden");
                //$("#step2").css("display", "block");
                $("#progress_bar").width("95%");
                var dataString = "";
                var token = $('[name=_token]').val();
                var username = $('[name=username]').val();
                var firstname = $('[name=firstname]').val();
                var lastname = $('[name=lastname]').val();
                var password = $('[name=password]').val();
                var confirmation = $('[name=password_confirmation]').val();
                var language = $("#ui_language").val();
                var registrationData = 
                    '_token='+token+'&username='+username+'&firstname='+firstname+'&lastname='+lastname+'&password='+password+'&password_confirmation='+confirmation+'&ui_language='+language+'&frominstaller=true';
                $.ajax({
                    type: "POST",
                    url: "install/registeradmin",
                    data: registrationData,
                    success: function(data, status, jqXHR) {
                        console.log(data);
                        //console.log(status);
                        //console.log(jqXHR);
                        console.log(jqXHR.status);
                        if(jqXHR.status == 302) {
                            $("body").html(data);
                            $("#step1").addClass("hidden");
                            $("#step5").removeClass("hidden");
                        }else{
                            $.ajax({
                                type: "POST",
                                url: "install/finish",
                                data: dataString,
                                success: function(data) {
                                    $("#progress_bar").width("100%");
                                    //window.location.href = "/login";
                                }
                            });
                        }
                    },
                    error: function() {
                        $("#registration_error").removeClass("hidden");
                        $("#finish_install_register_fail").click(function() {
                            $.ajax({
                                type: "POST",
                                url: "install/finish",
                                data: dataString,
                                success: function() {
                                    $("#progress_bar").width("100%");
                                    window.location.href = "/register";
                                }
                            });
                        });
                    }
                });
                return false;
            });
        </script>
    </body>
</html>