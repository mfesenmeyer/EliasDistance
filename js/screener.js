// Yahoo YQL Helper Method 
function getData(symbol) {
    var url = "http://query.yahooapis.com/v1/public/yql";
    var data = encodeURIComponent("select * from yahoo.finance.quotes where symbol in ('" + symbol + "')");

    $.getJSON(url, 'q=' + data + "&format=json&diagnostics=true&env=http://datatables.org/alltables.env")
        .done(function (data) {
        $("#loading").css("display","none");
        $("#result").text("Bid Price: " + data.query.results.quote.LastTradePriceOnly);

    })
        .fail(function (jqxhr, textStatus, error) {
        $("#loading").css("display","none");
        var err = textStatus + ", " + error;
            $("#result").text('Request failed: ' + err);

    });
}