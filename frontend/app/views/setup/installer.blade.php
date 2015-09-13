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
                position: absolute;
                bottom: 5%;
                right: 5%;
                left: 5%;
                width:100%;
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
                                                    <div id="debug_non_editable" class="col-md-3">
                                                        <input type="checkbox" disabled="disabled" @if (Config::get('app.debug')) checked="checked" @endif>   
                                                    </div>
                                                    <div id="debug_editable" class="hidden col-md-3">
                                                        <input type="checkbox" id="debug_mode_switch" @if (Config::get('app.debug')) checked="checked" @endif>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Registrations</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div id="registration_non_editable" class="col-md-3">
                                                        <input type="checkbox" disabled="disabled" @if (Config::get('paperwork.registration')) checked="checked" @endif>
                                                    </div>
                                                    <div id="registration_editable" class="col-md-3 hidden">
                                                        <input type="checkbox" id="registration_config_switch" @if (Config::get('paperwork.registration')) checked="checked" @endif>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Enable Forgot Password</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div id="forgot_password_non_editable" class="col-md-3">
                                                        <input type="checkbox" disabled="disabled" @if (Config::get('paperwork.forgot_password')) checked="checked" @endif>
                                                    </div>
                                                    <div id="forgot_password_editable" class="col-md-3 hidden">
                                                        <input type="checkbox" id="forgot_password_switch" @if (Config::get('paperwork.forgot_password')) checked="checked" @endif>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <h3>Show Issue Reporting Link</h3>
                                                        <p>Help Text</p>
                                                    </div>
                                                    <div id="issue_reporting_non_editable" class="col-md-3">
                                                        <input type="checkbox" disabled="disabled" @if (Config::get('paperwork.showIssueReportingLink')) checked="checked" @endif>
                                                    </div>
                                                    <div id="issue_reporting_editable" class="col-md-3 hidden">
                                                        <input type="checkbox" id="issue_reporting_link_switch" @if (Config::get('paperwork.showIssueReportingLink')) checked="checked" @endif>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-lg next_step" id="step3">Next</button>
                                    </li>
                                    <li class="form-group hidden">
                                        <h1>Registering your first user account</h1>
                                        <p>This account will serve as the administrator. </p>
                                        <div id="error_div" class="hidden" style="color:red">
                                        
                                        </div>
                                        @include("partials/registration-form", array('back' => false, 'frominstaller' => true))
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
                if(driver === "choice 2") {
                    driver = "mysql";
                    alert("New Database Options - Coming Soon");
                }
            });
            $(".next_step").click(function(event) {
                if($(event.currentTarget).hasClass("btn-primary") && event.target.id !== "step5" && event.target.id !== "step3") {
                    var currentStep = parseInt(event.currentTarget.id.replace("step", ""), 10) - 1;
                    var nextStep = currentStep + 1;
                    $("ul.form li").eq(currentStep).fadeOut("slow");
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
                        success: function() {
                            window.location.href = "/login";
                        }
                    });
                }else if(event.target.id === "step3") {
                    if($("#change_config").hasClass("hidden")) {
                        var debug = $("#debug_mode_switch").is(":checked");
                        var registration = $("#registration_config_switch").is(":checked");
                        var forgot = $("#forgot_password_switch").is(":checked");
                        var showIssue = $("#issue_reporting_link_switch").is(":checked");
                        var data = "debug="+debug+"&registration="+registration+"&forgot_password="+forgot+"&showIssueReportingLink="+showIssue;
                        $.ajax({
                            type: "POST",
                            url: "install/configurate",
                            data: data,
                            success: function() {
                                var currentStep = 3 - 1;
                                var nextStep = currentStep + 1;
                                $("ul.form li").eq(currentStep).fadeOut("slow");
                                $("ul.form li").eq(currentStep).addClass("hidden");
                                $("ul.form li").eq(nextStep).removeClass("hidden");
                                $("ul.form li").eq(nextStep).fadeIn("slow");
                                $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                            }
                        });
                    }else{
                        var currentStep = 3 - 1;
                        var nextStep = currentStep + 1;
                        $("ul.form li").eq(currentStep).fadeOut("slow");
                        $("ul.form li").eq(currentStep).addClass("hidden");
                        $("ul.form li").eq(nextStep).removeClass("hidden");
                        $("ul.form li").eq(nextStep).fadeIn("slow");
                        $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                    }
                }
            });
            $("#connection_check").click(function() {
                event.preventDefault();
                var user = $("#inputUser").val();
                var pass = $("#inputPassword").val();
                var server = $("#inputServer").val();
                var port = $("#inputPort").val();
                var dataString = "username="+user+"&password="+pass+"&server="+server+"&driver="+driver+"&port="+port;
                $.ajax({
                    type: "POST",
                    url: "install/checkdb",
                    data: dataString,
                    success: function() {
                        $("#connection_id_success").removeClass("hidden");
                        $("#connection_id_failure").addClass("hidden");
                        setTimeout(function() {
                            var currentStep = 2 - 1;
                            var nextStep = currentStep + 1;
                            $("ul.form li").eq(currentStep).fadeOut("slow");
                            $("ul.form li").eq(currentStep).addClass("hidden");
                            $("ul.form li").eq(nextStep).removeClass("hidden");
                            $("ul.form li").eq(nextStep).fadeIn("slow");
                            $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                        }, 2000);
                    },
                    error: function() {
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
                $.ajax({
                    type: "POST",
                    url: "install/registeradmin",
                    data: dataString,
                    success: function() {
                        var currentStep = 4 - 1;
                        var nextStep = currentStep + 1;
                        $("ul.form li").eq(currentStep).fadeOut("slow");
                        $("ul.form li").eq(currentStep).addClass("hidden");
                        $("ul.form li").eq(nextStep).removeClass("hidden");
                        $("ul.form li").eq(nextStep).fadeIn("slow");
                        $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");
                    },
                    error: function(jqXHR) {
                        var text = "Registration failed because of an error in these fields: ";
                        $.each(jqXHR.responseJSON.errors, function(index, value) {
                            text += index.charAt(0).toUpperCase() + index.substring(1) + ", ";
                        });
                        $("#error_div").text(text);
                        $("#error_div").removeClass("hidden");
                        $("#step5_submit").unbind("click");
                        $("#step5_submit").click(function() {
                            var currentStep = 4 - 1;
                            var nextStep = currentStep + 1;
                            $("ul.form li").eq(currentStep).fadeOut("slow");
                            $("ul.form li").eq(currentStep).addClass("hidden");
                            $("ul.form li").eq(nextStep).removeClass("hidden");
                            $("ul.form li").eq(nextStep).fadeIn("slow");
                            $("#progress_bar").css("width", ((nextStep / 5) * 100) + "%");                       
                        });
                        $("#step5_submit").attr("value", "Continue without registering");
                        $("#step5_submit").attr("id", "");
                    }
                });
            });
            $("#change_config").click(function() {
                $("#change_config").addClass("hidden");
                $("#debug_non_editable").addClass("hidden");
                $("#registration_non_editable").addClass("hidden");
                $("#forgot_password_non_editable").addClass("hidden");
                $("#issue_reporting_non_editable").addClass("hidden");
                $("#debug_editable").removeClass("hidden");
                $("#registration_editable").removeClass("hidden");
                $("#forgot_password_editable").removeClass("hidden");
                $("#issue_reporting_editable").removeClass("hidden");
            });
        </script>
    </body>
</html>