$(function () {
    var $phylocanvas = $('#phylocanvas');
    var phylocanvas = new PhyloCanvas.Tree('phylocanvas', {
        history: {
            collapsed: true
        },
        defaultCollapsed: {
            min: 30,
            max: 100,
            color: 'green'
        }
    });

    phylocanvas.showLabels = true;
    phylocanvas.hoverLabel = true;
    phylocanvas.setSize(745, 700);
    phylocanvas.setTreeType('rectangular');

    $.ajax({
        url: 'tree.php',
        data: {
            act: 'showTree',
            id: $phylocanvas.data('id')
        },
        success: function (res) {
            phylocanvas.load(res);
        }
    });

    var modalTemplate = $('#modalWindowTemplate').html();

    phylocanvas.on('selected', function (nodes) {
        console.log(nodes.nodeIds.length);
        if (nodes.nodeIds.length === 1)
            $.modalWindow('tree.php?act=editNode&id='+nodes.nodeIds[0]+'&tree_id='+$phylocanvas.data('id'));
    });

    window.phylocanvas = phylocanvas;
});