<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Дървета')}</h1>
</div>
<div class="main-content">
    
        <div class="content-block-content">
            <form name="registerForm" action="" method="POST"  class="content-block" enctype="multipart/form-data">
                <input type="hidden" name="act" value="doImport" />
                <div class="content-block-heading">
                    <h2>{_('Импортиране на дървета')}</h2>
                </div>
                <div class="content-block-content spacing">
                    <div class="form-row">
                        <label>{_('Файл')}: </label>
                        <input type="file" name="import_file"  {if $disabled==1}DISABLED{/if}/>
                    </div>
                    
                </div>
                {if $disabled!=1}
                    <div class="content-block-footer">
                        <input type="submit" class="button" name="submitRegister" value="{_('Импортирай')}" />
                        <button type="reset" class="button">{_('Изчисти формата')}</button>
                    </div>
                {/if}
            </form>
        </div>
    
</div>