<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <base href="{$mainDir}"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/font-awesome.css" media="all"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.modalWindow.css" media="all"/>
        <link rel="stylesheet" type="text/css" href="css/design.css" media="all"/>
        
        <script type="text/javascript" src="js_lib/jquery.js"></script>
        <script type="text/javascript" src="js_lib/jquery-ui.js"></script>
        <script type="text/javascript" src="js_lib/jquery.modalWindow.js"></script>
        <script type="text/javascript" src="js/common-scripts.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="wrapper">
                <div class="logo">
                    <a href="index.php"><img src="img/logo.png" alt=""/></a>
                </div>
                <div class="buttons-container">
                    <div class="button"><span class="fa fa-gear"></span></div>
                    <div class="button dropdown-container">
                        <span class="fa fa-power-off"></span>
                        <div class="content-container">
                            {if $smarty.session.user_id}
                                <div class="dropdown-item">
                                    <a href="user.php">{$smarty.session.user->name}</a>
                                </div>
                                <div class="dropdown-item">
                                    <a href="user.php?act=logout" >{_('изход')}</a>
                                </div>
                                
                                
                            {else}
                                <div class="dropdown-item">
                                    <a href="user.php?act=register">{_('регистрация')}</a>
                                </div>
                                <div class="dropdown-item">
                                    <a href="user.php?act=showLoginForm" class="modal-trigger">{_('вход')}</a>
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <div class="main_container">
                <div class="main_sidebar">
                    {include file='sidebar.tpl'}
                </div>
                <div class="main_content-container">
                    {if isset($system_error)}    
                        <div class="alert alert-error">
                            {$system_error}
                        </div>
                    {/if}
                    {if isset($system_message)}
                        <div class="alert alert-block">
                            {$system_message}
                        </div>
                    {/if}
                    {if isset($system_success)}
                        <div class="alert alert-success">
                            {$system_success}
                        </div>
                    {/if}
                    {if isset($system_info)}
                        <div class="alert alert-info">
                            {$system_info}
                        </div>
                    {/if}
                    
                    {include file=$template}
                </div>
            </div>
        </div>
    </body>
</html>