// to include in index.html:
//<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

//<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
//<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
//<script src="scripts/retrievereadings.js"></script>

//<body>
//<input type="button" value="Alert" onClick='showAlert()'>
//<div id="notice-message-dialog" title="" style="display: none;">
//  <p></p>
//</div>
//</body>

const evtLiveReadings = new EventSource("/Models/retrieveLive.php");
const optimalReadings = {
    pH : {
        low = 6.5,
        high = 7
    },
    temp : {
        low = 17,
        high = 34
    },
    do : {
        min = 5
    }
}


evtLiveReadings.addEventListener("liveReceived", function(event) {
    var objReadings = JSON.parse(event.data);

    // pH = objReadings.pH
    // dissolvedOxygen = objReadings.do
    // waterTemp = objReadings.temp
    // waterLevel = objReadings.level

    $message = objReadings.message;

    if (String.length($message) != 0) {
        $message = "Message from the server:\n" + $message + "\n";
    }
    if (objReadings.pH < optimalReadings.pH.low) {
        $message += "The pH level of the water is too low.\n";
    } else { if (objReadings.pH > optimalReadings.pH.high) {
            $message += "The pH level of the water is too high.\n";
        }
    }
    if (objReadings.temp < optimalReadings.temp.low) {
        $message += "The water's temperature is below the optimal temperature range.\n";
    }
    else { if (objReadings.temp > optimalReadings.temp.high) {
            $message += "The water's temperature is above the optimal temperature range.\n";
        }
    }
    if (objReadings.do < optimalReadings.do.min) {
        $message += "Dissolved oxygen level is below the minimum recommended 5 ppm.";
    }

    if (String.length($message) != 0) {
        showDialog($message);
    }
});

evtLiveReadings.onerror = (e) => {
    showDialog();
}

function showDialog($message = "Connection to the server could not be established.") {
    // create the dialog?
    $("#notice-message-dialog").dialog({
        autoOpen: false,
        position: { my: 'top', at: 'top+50px' },
        modal: true,
        resizable: false,
        closeOnEscape: false
    });
    $("#notice-message-dialog").find("p").innerHTML = $message;
}

function updateGraphs(event) {
    // startdate = ;
    // enddate = ;
    $.get('/Models/retrieveHistorical.php?startdate=' + startdate + '&enddate=' + enddate, function(data) {      
        var objReadings = JSON.parse(data);
    });
    // assign values to elements -> { time, pH, do, temp, level, countReadings }
}