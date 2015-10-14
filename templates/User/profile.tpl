<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Профил на потребител')}</h1>
</div>
<div class="main-content">
    <form  class="content-block">
    
        <div class="content-block-heading">
            <h2>{_('Данни за профил на ')}{$user->name}</h2>
        </div>
        <div class="content-block-content spacing">
            <div class="form-row">
                 <label>{_('Е-Маил')}: </label>
                {$user->email}
            </div>
            <div class="form-row">
                <label>{_('Име')}:</label>
                {$user->name}
            </div>
            {if $user->description}
                <div class="form-row">
                    <label>{_('Допълнителна информация')}:</label>
                    {$user->description}
                </div>
            {/if}
        </div>

    </form>
        
    <div class="content-block">
        <div class="content-block-heading">
            <h2>{_('Дървета създадени от ')}{$user->name}</h2>
        </div>
        <div class="content-block-content">
            <table>
                <thead>
                    <tr>
                        <th>{_('ID')}</th>
                        <th>{_('Име')}</th>
                        <th>{_('Автор')}</th>
                        <th>{_('Дата на създаване')}</th>
                        <th>{_('Преглед')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$user->trees item=tree}
                        <tr>
                            <td>{$tree->id}</td>
                            <td>{$tree->title}</td>
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
        
</div>