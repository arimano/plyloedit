<link rel="stylesheet" type="text/css" href="css/jquery.tabs.css" media="all"/>
<script type="text/javascript" src="js_lib/jquery.tabs.js"></script>
<script type="text/javascript" src="js_lib/jquery.simpleEdit.js"></script>
<script type="text/javascript" src="js_lib/jquery.form.js"></script>
<script type="text/javascript" src="js/edit-node.js"></script>
<form id="node_edit-form" class="content-block">
    <div class="content-block-heading">
        <div class="fa fa-times closeModalWindow"></div>
        <h2>{_('Данни за')} {$node_id}:</h2>
    </div>
    <div id="EditTabs" class="tabs">
        <div class="tabs-container">
            <a href="#tab-1" class="tab current first">Tab 1</a>
            <a href="#tab-2" class="tab first">Tab 2</a>
        </div>
        <div class="tabs-content_container">
            <div id="tab-1"class="tab-content current">
                <div class="content-block-content spacing">
                    <div class="form-row">
                        <label>{_('Параметър 1')}</label>
                        <input type="text" name="parameter_1" value="{$node_parameters['parameter_1']}" DISABLED/>
                    </div>
                    <div class="form-row">
                        <label>{_('Параметър 2')}</label>
                        <input type="text" name="parameter_2" value="{$node_parameters['parameter_2']}" DISABLED/>
                    </div>
                    <div class="form-row">
                        <label>{_('Параметър 3')}</label>
                        <input type="text" name="parameter_3" value="{$node_parameters['parameter_3']}" DISABLED/>
                    </div>
                    <div class="form-row">
                        <label>{_('Параметър 4')}</label>
                        <input type="text" name="parameter_4" value="{$node_parameters['parameter_4']}" DISABLED/>
                    </div>
                    <div class="form-row">
                        <label>{_('Параметър 5')}</label>
                        <input type="text" name="parameter_5" value="{$node_parameters['parameter_5']}" DISABLED/>
                    </div>
                    <div class="form-row">
                        <label>{_('Параметър 6')}</label>
                        <input type="text" name="parameter_6" value="{$node_parameters['parameter_6']}" DISABLED/>
                    </div>
                    <div class="form-row">
                        <label>{_('Параметър 7')}</label>
                        <input type="text" name="parameter_7" value="{$node_parameters['parameter_7']}" DISABLED/>
                    </div>

                </div>
            </div>
            <div id="tab-2"class="tab-content">
                <div class="content-block-content spacing">
                    <div class="form-row">
                        <label>Element label</label>
                        <input type="text" name="" value=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>