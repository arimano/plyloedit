<div class="main-heading">
    <div class="search-container">
        {include file='search.tpl'}
    </div>
    <h1>{_('Търсене')}</h1>
</div>
<div class="main-content">
    <div class="content-block">
        <div class="content-block-heading">
            <h2>{_('Търсене по ключова дума')}</h2>
        </div>
        
        <form act="search.php" method="GET" class="search-container" >
             <input type="hidden"name="act" value="doSearch" />
             <input type="text" placeholder="search..." name="keyword" />
             <button type="submit"></button>
         </form>

    </div>
  
        
</div>