<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Дървета')}</h1>
</div>
<div class="main-content">
    
        <div class="content-block-content">
            <form name="registerForm" action="" method="POST"  class="content-block">
                <input type="hidden" name="act" value="doNewTree" />
                <div class="content-block-heading">
                    <h2>{_('Създаване на дърво')}</h2>
                </div>
                <div class="content-block-content spacing">
                    <div class="form-row">
                        <label>{_('Име')}: </label>
                        <input type="text" name="name" value="{$smarty.request.name}" {if $disabled==1}DISABLED{/if}/>
                    </div>
                    <div class="form-row">
                        <label>{_('Описание')}: </label>
                        <textarea name="description" {if $disabled==1}DISABLED{/if}>{$smarty.request.description}</textarea>
                    </div>
                    <div class="form-row">
                        <label>{_('Newick')}: </label>
                        <textarea name="newick" {if $disabled==1}DISABLED{/if}>{$smarty.request.newick}</textarea>
                    </div>
                </div>
                {if $disabled!=1}
                    <div class="content-block-footer">
                        <input type="submit" class="button" name="submitRegister" value="{_('Запиши')}" />
                        <button type="reset" class="button">{_('Изчисти формата')}</button>
                    </div>
                {/if}
            </form>
        </div>
    
</div>