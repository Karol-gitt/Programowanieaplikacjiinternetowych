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
            <a class="navbar-brand" href="Admin">Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="<?php if($this->uri->rsegment(1)=='tests' || $this->uri->rsegment(2)=='create_test' || $this->uri->rsegment(2)=='edit_test' || $this->uri->rsegment(2)=='test' || $this->uri->rsegment(2)=='test_attempts' || $this->uri->rsegment(2)=='test_attempt') echo ' active'; ?>"><?php echo anchor("tests", 'Testy') ;?></li>
                <li class="dropdown <?php if($this->uri->rsegment(2)=='categories' || $this->uri->rsegment(2)=='questions') echo ' active'; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Baza pytań <span class="caret"></span></a>
                    <ul class="dropdown-menu" style="padding: 15px;">
                        <li <?php if($this->uri->rsegment(2)=='categories') echo 'class="active"'; ?>><?php echo anchor("questions_base/categories", 'Kategorie') ;?></li> 
                        <li <?php if($this->uri->rsegment(2)=='questions') echo 'class="active"'; ?>><?php echo anchor("questions_base/questions", 'Pytania') ;?></li> 
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown <?php if (($this->uri->rsegment(2) == 'index' && $this->uri->rsegment(1) != 'tests') || $this->uri->rsegment(2) == 'groups') echo ' active'; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administracja <span class="caret"></span></a>
                    <ul class="dropdown-menu" style="padding: 15px;">
                        <li <?php if ($this->uri->rsegment(2) == 'index' && $this->uri->rsegment(1) != 'tests') echo 'class="active"'; ?>><a href="auth">Użytkownicy</a></li>
                        <li <?php if ($this->uri->rsegment(2) == 'groups') echo 'class="active"'; ?>><a href="auth/groups">Grupy</a></li> 
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Moje konto <span class="caret"></span></a>
                    <ul class="dropdown-menu" style="padding: 15px;">
                        <li><?php echo anchor("auth/edit_user/" . $this->session->userdata('user_id'), 'Moje dane'); ?></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="auth/logout">Wyloguj</a></li> 
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

