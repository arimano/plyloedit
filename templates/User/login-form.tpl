<form class="content-block" action="" method="POST" >
    <input type="hidden" value="login" name="act" />
    <div class="content-block-heading">
        <div class="fa fa-times closeModalWindow"></div>
        <h2>{_('Вход')}:</h2>
    </div>
    <div class="content-block-content spacing">
        <div class="form-row">
            <label>{_('E-Mail')}:</label>
            <input type="text" name="username" value="" placeholder="please enter..."/>
        </div>
        <div class="form-row">
            <label>{_('Парола')}:</label>
            <input type="password" name="password" value="" placeholder="please enter..."/>
        </div>
        <div class="form-row">
            
        </div>
    </div>
    <div class="content-block-footer">
        <button type="submit" class="button">Enter</button>
        <button class="button closeModalWindow">Cancel</button>
    </div>
</form>