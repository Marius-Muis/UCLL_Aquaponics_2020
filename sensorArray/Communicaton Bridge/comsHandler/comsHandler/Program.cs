using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.IO;
using System.IO.Ports;
using System.Management;
using libSendParams;

namespace comsHandler
{
    class Program
    {
        static SerialPort sp;
        static sendParams transmit;
        static void Main(string[] args)
        {
            sp = new SerialPort();
            sp.PortName = configurePortName();      // choose the port on which the arduino is connected
            sp.BaudRate = 9600;                     // the speed at which data is transferred over serial. must be same as Arduino's (deafault: 9600 bits/second)
            sp.Open();
            while (true)
            {
                string s = sp.ReadExisting();
                if (s.Length != 0)
                    Console.WriteLine(transmit.send(s));
                Thread.Sleep(1000);
            }
        }

        private static string configurePortName(string defaultPort = "COM4")
        {
            string portName;

            Console.WriteLine("Available Ports:\n");
            foreach (string port in SerialPort.GetPortNames())
            {
                Console.WriteLine("{0}\n", port);
            }

            Console.Write("Select a COM port (Default: {0}): ", defaultPort);
            portName = Console.ReadLine();

            if (portName == "" || !(portName.ToLower()).StartsWith("com"))
            {
                Console.WriteLine("Invalid selection. Setting the port name to default ({0})", defaultPort);
                portName = defaultPort;
            }

            return portName;
        }
    }
}
