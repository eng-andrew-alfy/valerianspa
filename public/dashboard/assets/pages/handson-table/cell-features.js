document.addEventListener("DOMContentLoaded", function () {
    function getReadOnlyData() {
        return [
            { car: "Nissan", year: 2012, chassis: "black", bumper: "black" },
            { car: "Nissan", year: 2013, chassis: "blue", bumper: "blue" },
            { car: "Chrysler", year: 2014, chassis: "yellow", bumper: "black" },
            { car: "Volvo", year: 2015, chassis: "white", bumper: "gray" },
        ];
    }
    function getDisableData() {
        return [
            { car: "Nissan", year: 2012, chassis: "black", bumper: "black" },
            { car: "Nissan", year: 2013, chassis: "blue", bumper: "blue" },
            { car: "Chrysler", year: 2014, chassis: "yellow", bumper: "black" },
            { car: "Volvo", year: 2015, chassis: "white", bumper: "gray" },
        ];
    }
    var settings1,
        ipValidatorRegexp,
        emailValidator,
        people = [
            {
                id: 1,
                name: { first: "Joe", last: "Fabiano" },
                ip: "0.0.0.1",
                email: "Joe.Fabiano@ex.com",
            },
            {
                id: 2,
                name: { first: "Fred", last: "Wecler" },
                ip: "0.0.0.1",
                email: "Fred.Wecler@ex.com",
            },
            {
                id: 3,
                name: { first: "Steve", last: "Wilson" },
                ip: "0.0.0.1",
                email: "Steve.Wilson@ex.com",
            },
            {
                id: 4,
                name: { first: "Maria", last: "Fernandez" },
                ip: "0.0.0.1",
                email: "M.Fernandez@ex.com",
            },
            {
                id: 5,
                name: { first: "Pierre", last: "Barbault" },
                ip: "0.0.0.1",
                email: "Pierre.Barbault@ex.com",
            },
            {
                id: 6,
                name: { first: "Nancy", last: "Moore" },
                ip: "0.0.0.1",
                email: "Nancy.Moore@ex.com",
            },
            {
                id: 7,
                name: { first: "Barbara", last: "MacDonald" },
                ip: "0.0.0.1",
                email: "B.MacDonald@ex.com",
            },
            {
                id: 8,
                name: { first: "Wilma", last: "Williams" },
                ip: "0.0.0.1",
                email: "Wilma.Williams@ex.com",
            },
            {
                id: 9,
                name: { first: "Sasha", last: "Silver" },
                ip: "0.0.0.1",
                email: "Sasha.Silver@ex.com",
            },
            {
                id: 10,
                name: { first: "Don", last: "Pérignon" },
                ip: "0.0.0.1",
                email: "Don.Pérignon@ex.com",
            },
            {
                id: 11,
                name: { first: "Aaron", last: "Kinley" },
                ip: "0.0.0.1",
                email: "Aaron.Kinley@ex.com",
            },
        ],
        example1 = document.getElementById("validation"),
        example1console = document.getElementById("example1console");
    (ipValidatorRegexp =
        /^(?:\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b|null)$/),
        (emailValidator = function (value, callback) {
            setTimeout(function () {
                callback(/.+@.+/.test(value) ? !0 : !1);
            }, 1e3);
        }),
        (settings1 = {
            data: people,
            beforeChange: function (changes, source) {
                for (var i = changes.length - 1; i >= 0; i--)
                    if ("foo" === changes[i][3]) changes.splice(i, 1);
                    else {
                        if ("nuke" === changes[i][3]) return !1;
                        ("name.first" !== changes[i][1] &&
                            "name.last" !== changes[i][1]) ||
                            !changes[i][3].charAt(0) ||
                            (changes[i][3] =
                                changes[i][3].charAt(0).toUpperCase() +
                                changes[i][3].slice(1));
                    }
            },
            afterChange: function (changes, source) {
                "loadData" !== source &&
                    (example1console.innerText = JSON.stringify(changes));
            },
            colHeaders: ["ID", "First name", "Last name", "IP", "E-mail"],
            columns: [
                { data: "id", type: "numeric" },
                { data: "name.first" },
                { data: "name.last" },
                { data: "ip", validator: ipValidatorRegexp, allowInvalid: !0 },
                { data: "email", validator: emailValidator, allowInvalid: !1 },
            ],
        });
    var hot1,
        hot = new Handsontable(example1, settings1),
        data = [
            ["", "Kia", "Nissan", "Toyota", "Honda"],
            ["2012", 10, 11, 12, 13],
            ["2013", 20, 11, 14, 13],
            ["2014", 30, 15, 12, 13],
            ["2015", "", "", "", ""],
            ["2024", "", "", "", ""],
        ],
        container = document.getElementById("drag");
    (hot1 = new Handsontable(container, {
        rowHeaders: !0,
        colHeaders: !0,
        fillHandle: !0,
    })),
        hot1.loadData(data);
    var hot,
        container = document.getElementById("merged");
    hot = new Handsontable(container, {
        data: Handsontable.helper.createSpreadsheetData(25, 18),
        colWidths: [
            47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47,
        ],
        rowHeaders: !0,
        colHeaders: !0,
        contextMenu: !0,
        mergeCells: [
            { row: 1, col: 1, rowspan: 3, colspan: 3 },
            { row: 3, col: 4, rowspan: 2, colspan: 2 },
            { row: 5, col: 6, rowspan: 3, colspan: 3 },
        ],
    });
    var hot1,
        container = document.getElementById("alignment");
    hot1 = new Handsontable(container, {
        data: Handsontable.helper.createSpreadsheetData(25, 18),
        colWidths: [
            47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47, 47,
        ],
        rowHeaders: !0,
        colHeaders: !0,
        contextMenu: !0,
        mergeCells: [
            { row: 1, col: 1, rowspan: 3, colspan: 3 },
            { row: 3, col: 4, rowspan: 2, colspan: 2 },
        ],
        className: "htCenter",
        cell: [
            { row: 0, col: 0, className: "htRight" },
            { row: 1, col: 1, className: "htLeft htMiddle" },
            { row: 3, col: 4, className: "htLeft htBottom" },
        ],
        afterSetCellMeta: function (row, col, key, val) {},
    });
    var hot1,
        container1 = document.getElementById("readOnly");
    hot1 = new Handsontable(container1, {
        data: getReadOnlyData(),
        colHeaders: ["Car", "Year", "Chassis color", "Bumper color"],
        columns: [
            { data: "car", readOnly: !0 },
            { data: "year" },
            { data: "chassis" },
            { data: "bumper" },
        ],
    });
    var hot1,
        container1 = document.getElementById("nonEditable");
    hot1 = new Handsontable(container1, {
        data: getDisableData(),
        colHeaders: ["Car", "Year", "Chassis color", "Bumper color"],
        columns: [
            { data: "car", editor: !1 },
            { data: "year", editor: "numeric" },
            { data: "chassis", editor: "text" },
            { data: "bumper", editor: "text" },
        ],
    });
});
