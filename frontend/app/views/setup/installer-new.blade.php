<!DOCTYPE html>
<html lang="en">
    <head>
        @include('partials/header-sidewide-meta')
        [[ HTML::style('css/themes/paperwork-v1.min.css') ]]
        <style>
            html, body {
                height:100%;
                margin-top: -1px;
            }
            body {
                position: relative;
            }
            ul.form li {
                margin:0;
                padding:0;
                list-style:none;
                position:abolsute;
                width:100%;
            }
            .next_step {
                padding: 20px 60px;
                font-size: 24px;
                position: absolute;
                bottom: 5%;
                right: 5%;
                left: 5%;
                width:100%;
            }
            .toggle_button {
                position: absolute;
                margin-left: -9999px;
                visibility: hidden;
            }
            .toggle_button + label {
                display: block;
                position: relative;
                cursor: pointer;
                outline: none;
                user-select: none;
            }
            input.toggle_button + label {
                padding: 2px;
                width: 90px;;
                height: 45px;
                background-color: #DDDDDD;
                border-radius: 45px;
            }
            input.toggle_button + label:before, input.toggle_button + label:after {
                display: block;
                position: absolute;
                top: 1px;
                left: 1px;
                bottom: 1px;
                content: "";
            }
            input.toggle_button + label:before {
                right: 1px;
                background-color: #F1F1F1;
                border-radius: 45px;
                transition: background 0.4s;
            }
            input.toggle_button + label:after {
                width: 43.5px;
                background-color: #FFFFFF;
                border-radius: 100%;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
                transition: margin 0.4s;
            }
            input.toggle_button:checked + label:before {
                background-color: #8CE196;
            }
            input.toggle_button:checked + label:after {
                margin-left: 45px;
            }
        </style>
    </head>
    
    <body>
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="paperwork-logo navbar-brand transition-effect" href="[[-- URL::route('/') --]]">
                        <img src="[[ asset('images/navbar-logo.png') ]]"> Paperwork
                    </a>
                </div>
            </div>
        </div>
        <div style="left:0;right:0;z-index:1;display:block;width:100%;height:100%;/*overflow:hidden*/"> <!-- wizard -->
            <div style="height:10px"> <!-- progress container -->
                <div id="progress_bar" style="width:0;background:red;height:10px;"><!-- progress bar --></div>
            </div>
            <div class="container-fluid" style="display:table;width:100%;height:100%">
                <div style="z-index:1111111111;display:table-cell;vertical-align:middle;padding:0px">
                    <div class="inner cover" style="padding:30px;padding:20px 60px;">
                        <div class="centerUp" style="max-width:750px;margin:0 auto">
                            <div class="questionnaire">
                                <ul class="form text-center" style="position:relative;margin:0px;padding:0px;min-height:500px">
                                    <li class="form-group">
                                        <h1>Checking for updates</h1>
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
                                            <button class="btn btn-default btn-lg next_step" style="left: 5% !important;right: 5% !important;width:100%; bottom:25%;" id="update_button">Update</button>
                                        @endif
                                        <button class="btn btn-primary btn-lg next_step" id="step1">Next</button>
                                    </li>
                                    <li class="form-group hidden">
                                        <h1>Setting up the database</h1>
                                        <div style="width:48%;height:100%;float:left;margin-right:10px"> <!--- first drop down - dbms choice -->
                                            <a class="database_links" style="padding:20px 0;display:block">MySQL <span class="caret"></span></a><br>
                                            <a class="database_links" style="padding:20px 0;display:block;font-style:italic;background:#CCCCCC">Choice 2 <span class="caret"></span></a>
                                        </div>
                                        <div style="width:50%;height:100%;float:left;"> <!-- second drop down - requirements and credentials form -->
                                            <div>
                                                <p>Requirements: ...</p> 
                                                <form class="form-horizontal">
                                                    <div class="form-group">
                                                        <div id="connection_id_success" class="hidden" style="height:15px;background:green;">Credentials correct</div>
                                                        <div id="connection_id_failure" class="hidden" style="15px;background:red">Credentials not correct. Please delete db_settings file in storage directory and try again. </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputServer" class="col-sm-2 control-label">Server</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="inputServer" placeholder="Server">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputPort" class="col-sm-2 control-label">Port</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" class="form-control" id="inputPort" placeholder="Port">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputUser" class="col-sm-2 control-label">Username</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="inputUser" placeholder="Username">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <button class="btn btn-default" id="connection_check">Check Connection and Install Database</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-lg next_step" id="step2">Next</button>
                                    </li>
                                    <li class="form-group hidden">
                                        <h1>Configurating</h1>
                                        <!-- 
                                        disabled form with all config values and with a change link 
                                        -->
                                        <div>
                                            <div class="row">
                                                <h2 style="">Default Configuration Settings</h2>
                                                <a id="change_config" style="float:right">Change</a>
                                            </div>
                                            <div>    
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Debug Mode</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        read only checkbox
                                                    </div>
                                                    <div class="hidden col-md-3">
                                                        <div>
                                                            <input id="toggle_btn_4" type="checkbox" class="toggle_button">
                                                            <label id="toggle_label_4" for="toggle_btn_4"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Registrations</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        read only checkbox
                                                    </div>
                                                    <div class="col-md-3 hidden">
                                                        <div>
                                                            <input id="toggle_btn_3" type="checkbox" class="toggle_button">
                                                            <label id="toggle_label_3" for="toggle_btn_3"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Enable Forgot Password</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div class="col-md-3 hidden">
                                                        read only checkbox
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div>
                                                            <input id="toggle_btn_2" type="checkbox" class="toggle_button">
                                                            <label id="toggle_label_2" for="toggle_btn_2"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Show Issue Reporting Link</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div class="col-md-3 hidden">
                                                        read only checkbox
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div>
                                                            <input id="toggle_btn_1" type="checkbox" class="toggle_button">
                                                            <label id="toggle_label_1" for="toggle_btn_1"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-lg next_step" id="step3">Next</button>
                                    </li>
                                    <li class="form-group hidden">
                                        <h1>Registering your first user account</h1>
                                        <p>This account will serve as the administrator. </p>
                                        @include("partials/registration-form", array('back' => false, 'frominstaller' => true))
                                        <!--<button class="btn btn-primary btn-lg next_step" id="step4">Next</button>-->
                                    </li>
                                    <li class="form-group hidden">
                                        <h1>Installation completed</h1>
                                        <p>Congratulations! You have now finished installing and setting up Paperwork. Click on the link below to login in the account you have just created. </p>
                                        <button class="btn btn-primary btn-lg next_step" id="step5">Proceed to Paperwork</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        [[ HTML::script('js/jquery.min.js') ]]
        <script type="text/javascript">
            var driver = "mysql";
            $(".database_links").click(function(event) {
                driver = ((event.currentTarget.innerText).trim()).toLowerCase();
                console.log(driver);
                if(driver === "choice 2") {
                    driver = "mysql";
                    alert("New Database Options - Coming Soon");
                }
            });
            $(".next_step").click(function(event) {
                console.log(event);
                console.log(event.currentTarget);
                if($(event.currentTarget).hasClass("btn-primary") && event.target.id !== "step5") {
                    var currentStep = parseInt(event.currentTarget.id.replace("step", ""), 10) - 1;
                    console.log(currentStep);
                    var nextStep = currentStep + 1;
                    console.log(nextStep);
                    $("ul.form li").eq(currentStep).fadeOut("slow");
                    console.log($("ul.form li").eq(currentStep));
                    $("ul.form li").eq(currentStep).addClass("hidden");
                    $("ul.form li").eq(nextStep).removeClass("hidden");
                    $("ul.form li").eq(nextStep).fadeIn("slow");
                    $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                }else if(event.currentTarget.id === "update_button") {
                    alert("Coming Soon");
                }else if(event.target.id === "step5") {
                    $.ajax({
                        type: "POST",
                        url: "install/finish",
                        finished: function() {
                            window.location.href = "/login";
                        }
                    });
                }
            });
            $("#connection_check").click(function() {
                // Run AJAX - TODO
                event.preventDefault();
                //var driver = "mysql";
                var user = $("#inputUser").val();
                var pass = $("#inputPassword").val();
                var server = $("#inputServer").val();
                //var token = "";
                var port = $("#inputPort").val();
                var dataString = /*"_token="+token+"&*/"username="+user+"&password="+pass+"&server="+server+"&driver="+driver+"&port="+port;
                console.log(dataString);
                $.ajax({
                    type: "POST",
                    url: "install/checkdb",
                    data: dataString,
                    success: function() {
                        alert("Good credentials");
                        $("#connection_id_success").removeClass("hidden");
                        $("#connection_id_failure").addClass("hidden");
                        setTimeout(function() {
                            var currentStep = 2 - 1;
                            console.log(currentStep);
                            var nextStep = currentStep + 1;
                            console.log(nextStep);
                            $("ul.form li").eq(currentStep).fadeOut("slow");
                            console.log($("ul.form li").eq(currentStep));
                            $("ul.form li").eq(currentStep).addClass("hidden");
                            $("ul.form li").eq(nextStep).removeClass("hidden");
                            $("ul.form li").eq(nextStep).fadeIn("slow");
                            $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                        }, 2000);
                    },
                    error: function() {
                        alert("Fix credentials");
                        $("#connection_id_failure").removeClass("hidden");
                        $("#connection_id_success").addClass("hidden");
                    }
                });
            });
            $("#step5_submit").click(function(event) {
                event.preventDefault();
                var token = $("[name='_token']").val();
                var user = $("[name='username']").val();
                var name = $("[name='firstname']").val();
                var lastname = $("[name='lastname']").val();
                var pass = $("[name='password']").val();
                var confirm = $("[name='password_confirmation']").val();
                var lang = $("#ui_language").val(); 
                var dataString = 
                    "_token="+token+"&username="+user+"&firstname="+name+"&lastname="+
                    lastname+"&password="+pass+"&password_confirmation="+confirm
                    +"&ui_language="+lang+"&frominstaller=1";
                console.log(dataString);
                $.ajax({
                    type: "POST",
                    url: "install/registeradmin",
                    data: dataString,
                    success: function() {
                        var currentStep = 4 - 1;
                        console.log(currentStep);
                        var nextStep = currentStep + 1;
                        console.log(nextStep);
                        $("ul.form li").eq(currentStep).fadeOut("slow");
                        console.log($("ul.form li").eq(currentStep));
                        $("ul.form li").eq(currentStep).addClass("hidden");
                        $("ul.form li").eq(nextStep).removeClass("hidden");
                        $("ul.form li").eq(nextStep).fadeIn("slow");
                        $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                    }
                });
            });
        </script>
    </body>
</html>