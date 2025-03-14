document.addEventListener("DOMContentLoaded", function () {
    function isEmptyRow(instance, row) {
        for (
            var rowData = instance.getData()[row], i = 0, ilen = rowData.length;
            i < ilen;
            i++
        )
            if (null !== rowData[i]) return !1;
        return !0;
    }

    function defaultValueRenderer(
        instance,
        td,
        row,
        col,
        prop,
        value,
        cellProperties
    ) {
        var args = arguments;
        null === args[5] && isEmptyRow(instance, row)
            ? ((args[5] = tpl[col]), (td.style.color = "#999"))
            : (td.style.color = ""),
            Handsontable.renderers.TextRenderer.apply(this, args);
    }
    var hot1,
        tpl = ["one", "two", "three"],
        data = [
            ["", "Kia", "Nissan", "Toyota", "Honda"],
            ["2014", 10, 11, 12, 13],
            ["2015", 20, 11, 14, 13],
            ["2024", 30, 15, 12, 13],
        ],
        container = document.getElementById("populating");
    (hot1 = new Handsontable(container, {
        startRows: 8,
        startCols: 5,
        minSpareRows: 1,
        contextMenu: !0,
        cells: function (row, col, prop) {
            var cellProperties = {};
            return (
                (cellProperties.renderer = defaultValueRenderer), cellProperties
            );
        },
        beforeChange: function (changes) {
            var i,
                c,
                instance = hot1,
                ilen = changes.length,
                clen = instance.colCount,
                rowColumnSeen = {},
                rowsToFill = {};
            for (i = 0; i < ilen; i++)
                null === changes[i][2] &&
                    null !== changes[i][3] &&
                    isEmptyRow(instance, changes[i][0]) &&
                    ((rowColumnSeen[changes[i][0] + "/" + changes[i][1]] = !0),
                    (rowsToFill[changes[i][0]] = !0));
            for (var r in rowsToFill)
                if (rowsToFill.hasOwnProperty(r))
                    for (c = 0; c < clen; c++)
                        rowColumnSeen[r + "/" + c] ||
                            changes.push([r, c, null, tpl[c]]);
        },
    })),
        hot1.loadData(data);
});
