function drawLineChart(dbData) {
  var data = new google.visualization.DataTable();
  data.addColumn("string", "Time");
  data.addColumn("number", "Water Level");
  data.addColumn("number", "pH");
  data.addColumn("number", "Dissolved Oxygen");
  data.addColumn("number", "Temperature");

  dbData.forEach((element) => {
    data.addRows([
      [
        element.time,
        parseInt(element.water_level),
        parseInt(element.ph),
        parseInt(element.dis_oxygen),
        parseInt(element.temperature),
      ],
    ]);
  });
  var options = {
    chart: {
      title: "All readings",
    },
    width: 1200,
    height: 500,
  };

  var chart = new google.charts.Line(document.getElementById("line_chart"));

  chart.draw(data, google.charts.Line.convertOptions(options));
}

function drawGaugeChart(dbData) {
  var data_ph = new google.visualization.DataTable();
  data_ph.addColumn("string", "Label");
  data_ph.addColumn("number", "Value");
  data_ph.addRows(1);
  data_ph.setValue(0, 0, "pH");
  data_ph.setValue(0, 1, dbData.ph);

  var data_water_level = new google.visualization.DataTable();
  data_water_level.addColumn("string", "Label");
  data_water_level.addColumn("number", "Value");
  data_water_level.addRows(1);
  data_water_level.setValue(0, 0, "Water level");
  data_water_level.setValue(0, 1, dbData.water_level);

  var data_do = new google.visualization.DataTable();
  data_do.addColumn("string", "Label");
  data_do.addColumn("number", "Value");
  data_do.addRows(1);
  data_do.setValue(0, 0, "Dis oxygen");
  data_do.setValue(0, 1, dbData.dis_oxygen);

  var data_temp = new google.visualization.DataTable();
  data_temp.addColumn("string", "Label");
  data_temp.addColumn("number", "Value");
  data_temp.addRows(1);
  data_temp.setValue(0, 0, "Temperature");
  data_temp.setValue(0, 1, dbData.temperature);

  var chart_ph = new google.visualization.Gauge(
    document.getElementById("gauge_ph")
  );
  var chart_water_level = new google.visualization.Gauge(
    document.getElementById("gauge_water")
  );
  var chart_do = new google.visualization.Gauge(
    document.getElementById("gauge_do")
  );
  var chart_temp = new google.visualization.Gauge(
    document.getElementById("gauge_temp")
  );

  var options_ph = {
    width: 200,
    height: 200,
    greenFrom: 6.7,
    greenTo: 7.1,
    redFrom: 7.1,
    redTo: 14,
    yellowFrom: 0,
    yellowTo: 6.7,
    minorTicks: 5,
    max: 14,
  };
  var options_water_level = {
    width: 200,
    height: 200,
    greenFrom: 90,
    greenTo: 140,
    redFrom: 140,
    redTo: 200,
    yellowFrom: 0,
    yellowTo: 90,
    minorTicks: 5,
    max: 200,
  };
  var options_do = {
    width: 200,
    height: 200,
    greenFrom: 80,
    greenTo: 120,
    redFrom: 120,
    redTo: 200,
    yellowFrom: 0,
    yellowTo: 80,
    minorTicks: 5,
    max: 200,
  };
  var options_temp = {
    width: 200,
    height: 200,
    greenFrom: 15,
    greenTo: 21,
    redFrom: 21,
    redTo: 50,
    yellowFrom: 0,
    yellowTo: 15,
    minorTicks: 5,
    max: 50,
  };
  chart_ph.draw(data_ph, options_ph);
  chart_water_level.draw(data_water_level, options_water_level);
  chart_do.draw(data_do, options_do);
  chart_temp.draw(data_temp, options_temp);
}
