<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="welcome">Testy</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Logowanie <span class="caret"></span></a>
                    <ul class="dropdown-menu" style="padding: 15px;">
                        <form method="post" action="#" id="loginForm">
                            <div class="form-group">
                                <div class="control-group">
                                    <div class="controls">
                                        <input id="login" name="email" type="text" placeholder="E-mail" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="control-group">
                                    <div class="controls">
                                        <input id="password" name="password" type="password" placeholder="Hasło" class="form-control">
                                    </div>
                                </div>
                            </div>                   
                            
                            <button class="btn btn-primary btn-block">Zaloguj</button>
                        </form>
                        
                        <div class="text-right" style="margin-top: 15px;">
                            <a href="auth/forgot_password"><?php echo lang('login_forgot_password');?></a>
                        </div>
                        
                        <div class="alert alert-danger" id="loginAlert" role="alert" style="margin-top: 15px; margin-bottom: 0px; display: none;">
                            
                        </div>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

