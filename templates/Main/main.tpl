<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Начална страница')}</h1>
</div>
<div class="main-content">
    <div class="content-block">
        <div class="content-block-heading">
            <h2>{_('Последно създадени дървета')}</h2>
        </div>
        <div class="content-block-content">
            <table>
                <thead>
                    <tr>
                        <th>{_('ID')}</th>
                        <th>{_('Автор')}</th>
                        <th>{_('Дата на създаване')}</th>
                        <th>{_('Преглед')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $trees as $tree}
                    <tr>
                        <td>1</td>
                        <td>
                            <a href="user.php?id={$tree->user->id}">{$tree->user->name}</a>
                        </td>
                        <td>{$tree->creation_date}</td>
                        <td class="center">
                            <a href="tree.php?id={$tree->id}">
                                <span class="fa fa-eye"></span>
                            </a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
        
    <div class="content-block">
        <div class="content-block-heading">
            <h2>{_('Последно регистрирани потребители')}</h2>
        </div>
        <div class="content-block-content">
            <table>
                <thead>
                    <tr>
                        <th>{_('Име и фамилия')}</th>
                        <th>{_('Е-мейл')}</th>
                        <th>{_('Дата на регистрация')}</th>
                        <th>{_('Преглед')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $users as $user}
                    <tr>
                        <td>
                            <a href="user.php?id={$user->id}">{$user->name}</a>
                        </td>
                        <td>{$user->email}</td>
                        <td>{$user->creation_date}</td>
                        <td class="center">
                            <a href="user.php?id={$user->id}">
                                <span class="fa fa-eye"></span>
                            </a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
        
</div>