<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Регистрация:')}</h1>
</div>
<div class="main-content">
    <form name="registerForm" action="" method="POST"  class="content-block">
        <input type="hidden" name="act" value="doRegister" />
        <div class="content-block-heading">
            <h2>{_('Моля, попълнете Вашите данни:')}</h2>
        </div>
        <div class="content-block-content spacing">
            <div class="form-row">
                 <label>{_('Е-Маил')}: </label>
                <input type="text" name="email" value="{$smarty.request.email}" />
            </div>
            <div class="form-row">
                <label>{_('Име')}:</label>
                <input type="text" name="name" value="{$smarty.request.name}" />
            </div>
            <div class="form-row">
                <label>{_('Парола')}:</label>
                <input type="password" name="password" value="" />
            </div>
            <div class="form-row">
                <label>{_('Повторете паролата')}:</label>
                <input type="password" name="password_again" value="" />
            </div>
        </div>
        <div class="content-block-footer">
            <input type="submit" class="button" name="submitRegister" value="{_('Регистрирай се')}" />
            <button type="reset" class="button">{_('Изчисти формата')}</button>
        </div>
    </form>
</div>