<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Списък на дървета')}</h1>
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
                        <th>{_('Име')}</th>
                        <th>{_('Автор')}</th>
                        <th>{_('Дата на създаване')}</th>
                        <th>{_('Преглед')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$trees item=tree}
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
                    <tr>
                        <td colspan="5" style="text-align:center">
                            <form name="paginator" method="GET" class="search-container">
                                <input type="text" name="page_num" value="{$page_num}" /> от {$pages_count} страница
                                <button type="submit"></button>
                            </form>

                        </td>
                        
                    </tr>
                </tbody>
            </table>
                
        </div>
    </div>
</div>