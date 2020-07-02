using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace libSendParams
{
    class waterParams
    {
        private decimal ph;
        private decimal dissovledOxygen;
        private decimal temperature;
        private decimal waterLevel;
        internal decimal Ph
        {
            get { return ph; }
            set { ph = value; }
        }
        internal decimal DissovledOxygen
        {
            get { return dissovledOxygen; }
            set { dissovledOxygen = value; }
        }
        internal decimal Temperature
        {
            get { return temperature; }
            set { temperature = value; }
        }
        internal decimal WaterLevel
        {
            get { return waterLevel; }
            set { waterLevel = value; }
        }

        internal waterParams(decimal ph, decimal dissovledOxygen, decimal temperature, decimal waterLevel)
        {
            this.ph = ph;
            this.dissovledOxygen = dissovledOxygen;
            this.temperature = temperature;
            this.waterLevel = waterLevel;
        }
    }
}
