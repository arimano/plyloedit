<div class="user_info-container">
    <div class="image">
        <img src="img/no_avatar.jpg" alt=""/>
    </div>
    <div class="content">
        <div class="user_name">
            <a href="">{if $smarty.session.user}{$smarty.session.user->name}{else}{_('Анонимен потребител')}{/if}</a>
        </div>
    </div>
</div>
<div class="list-container">
    <div class="list-item-container {if $page=='main'}active{/if}">
        <a href="index.php">{_('Начало')}</a>
    </div>
    <div class="list-item-container {if $page=='tree' and !$act}active{/if}">
        <a href="tree.php">{_('Списък дървета')}</a>
    </div>
    <div class="list-item-container {if $page=='tree' and $act=='newTree'}active{/if}">
        <a href="tree.php?act=newTree">{_('Създаване на дърво')}</a>
    </div>
    <div class="list-item-container {if $page=='tree' and $act=='showImport'}active{/if}">
        <a href="tree.php?act=showImport">{_('Импортиране на данни')}</a>
    </div>
    <div class="list-item-container {if $page=='user' }active{/if}">
        <a href="user.php?act=showList">{_('Потребители')}</a>
    </div>
    <div class="list-item-container {if $page=='search' }active{/if}">
        <a href="search.php">{_('Търсене')}</a>
    </div>
    
</div>