<script type="text/javascript" src="js_lib/PhyloCanvas.js"></script>
<script type="text/javascript" src="js/tree-view.js"></script>
<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Изглед на дърво:')} ID: {$tree->id}, {_('Автор')}: <a href='user.php?id={$tree->user->id}'>{$tree->user->name}</a></h1>
</div>
<div class="main-content">

    <div class="content-block">        
        <div id="phylocanvas" data-id="{$tree->id}"></div>
    </div>
</div>
