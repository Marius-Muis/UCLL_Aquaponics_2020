using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Net;

namespace libSendParams
{
    /*      Responsible sending the params to the remote hosting server via call to a script and including
     *          them in the URL params. Not capable of processing serial input!
     */
    public class sendParams
    {
        private DateTime timeFirstReading = Convert.ToDateTime(0);            // the time that the first entry was made since the historical data was added to the database
        private DateTime timeRetryWait = Convert.ToDateTime(0);               // the time recorded at the time an entry is added to arrStorage
        private List<waterParams> arrSixHours = new List<waterParams>();      // holds the submissions for 6 hours before calling the PHP script that adds it to the database (should be 360 entries)
        private List<waterParams> arrStorage = new List<waterParams>();       // in case the program can't connect to the server, it will hold the historical data's values and try later
        private List<DateTime> arrStorageTimes = new List<DateTime>();        // parallel to arrStorage; holds the time at which entries are made in arrStorage

        public String send(String serialMessage)
        {
            string response = "";                                  // the string message to be returned; constructed out of HTML responses
            string url = "http://localhost/logLive.php";           // logLive.php script URL on remote server
            string[] arrParams = serialMessage.Split(';');         // ph, dissolved oxygen, temperature, water level, error message
            DateTime currTime = DateTime.Now;

            if (arrSixHours.Count == 0)
                timeFirstReading = currTime;
            if (arrParams[4] == "none")                            // stores the reading if there is no errors
                arrSixHours.Add(new waterParams(Convert.ToDecimal(arrParams[0]), Convert.ToDecimal(arrParams[1]), Convert.ToDecimal(arrParams[2]), Convert.ToDecimal(arrParams[3])));
            using (WebClient webClient = new WebClient())
                response = webClient.DownloadString(string.Format("{0}?datetime={1}&ph={2}&do={3}&temp={4}&level={5}&error={6}", url, currTime.ToString("YYYY-MM-DD HH:MM:SS"), arrParams[0], arrParams[1], arrParams[2], arrParams[3], arrParams[4]));
            if (timeFirstReading != Convert.ToDateTime(0) && (currTime - timeFirstReading).TotalHours >= 6)
                response += sendHistoricalParams();
            if (timeRetryWait != Convert.ToDateTime(0) && arrStorage.Count != 0 && (timeRetryWait - currTime).TotalMinutes >= 5)
                response += retry();

            return response;
        }

        private string sendHistoricalParams()
        {
            string url = "http://localhost/logHistorical.php";      // logHistrical.php script URL on remote server
            string response = "";

            if (arrSixHours.Count != 0)
            {
                decimal totPh = 0;
                decimal totDo = 0;
                decimal totTemp = 0;
                decimal totLevel = 0;
                decimal meanPh, meanDo, meanTemp, meanLevel;

                foreach (waterParams instance in arrSixHours)
                {
                    totPh += instance.Ph;
                    totDo += instance.DissovledOxygen;
                    totTemp += instance.Temperature;
                    totLevel += instance.WaterLevel;
                }

                meanPh = totPh / arrSixHours.Count;
                meanDo = totDo / arrSixHours.Count;
                meanTemp = totTemp / arrSixHours.Count;
                meanLevel = totLevel / arrSixHours.Count;

                using (WebClient webClient = new WebClient())
                    response = "/n" + webClient.DownloadString(string.Format("{0}?datetime={1}&ph={2}&do={3}&temp={4}&level={5}", url, timeFirstReading.ToString("YYYY-MM-DD HH:MM:SS"), meanPh.ToString(), meanDo.ToString(), meanTemp.ToString(), meanLevel.ToString()));
                if (response.ToLower().Contains("failed"))
                {
                    arrStorage.Add(new waterParams(meanPh, meanDo, meanTemp, meanLevel));
                    response += "/nEntry is being held in memory. Retrying in 5 minutes.";
                    timeRetryWait = DateTime.Now;
                }

                arrSixHours.Clear();
                timeFirstReading = Convert.ToDateTime(0);
            }

            return response;
        }

        private string retry()
        {
            string url = "http://localhost/logHistorical.php";  // logHistrical.php script URL on remote server
            string response = "";
            int index = -1;                                     // index for parallel arrStorageTimes
            bool success = false;                               // turns true if there was at least one successfull transfer
            foreach (waterParams instance in arrStorage)
            {
                index++;
                response += "/nRetrying historical set recorded at " + arrStorageTimes[index].ToString();
                using (WebClient webClient = new WebClient())
                    response += "/n" + webClient.DownloadString(string.Format("{0}?datetime={1}&ph={2}&do={3}&temp={4}&level={5}", url, arrStorageTimes[index].ToString("YYYY-MM-DD HH:MM:SS"), instance.Ph.ToString(), instance.DissovledOxygen.ToString(), instance.Temperature.ToString(), instance.WaterLevel.ToString()));
                if (response.ToLower().Contains("failed"))
                {
                    response += "/nFailed to insert entry. Retrying in 5 minutes.";
                    timeRetryWait = DateTime.Now;
                    break;
                }
                else
                {
                    arrStorage.RemoveAt(index);
                    arrStorageTimes.RemoveAt(index);
                    success = true;
                }
            }
            if (success)                // restructures lists to resort them (no empty spaces)
            {
                List<waterParams> arrTemp = new List<waterParams>();    // temporarily stores non-null contents of arrStorage for restructuring
                List<DateTime> arrTempT = new List<DateTime>();         // temporarily stores non-null contents of arrStorageTimes for restructuring
                for (int x = 0; x < arrStorage.Count; x++)
                {
                    if (arrStorage[x] != null)
                    {
                        arrTemp.Add(arrStorage[x]);
                        arrTempT.Add(arrStorageTimes[x]);
                    }
                }
                arrStorage = arrTemp;
                arrStorageTimes = arrTempT;
            }
            if (arrStorage.Count == 0)
                timeRetryWait = Convert.ToDateTime(0);
            return response;
        }
    }
}