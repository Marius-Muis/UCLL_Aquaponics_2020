
#include <TimerOne.h>
#include <dht.h>
#include <SPI.h>
#include <Ethernet.h>

dht DHT1;
int temperatureSensorPin1 = 25;

byte mac[] = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED
};
EthernetClient client;
EthernetServer server(80);

long previousMillis = 0;
unsigned long currentMillis = 0;
long interval = 250000;
String data;

long lastUpdate = 0;

long timeLeft = 30;

void setup() {
  Serial.begin(9600);
  
  if(Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
  }
  data = "";
  pinMode(temperatureSensorPin1, INPUT);
  
  Timer1.initialize(1000000); // 1 second
  Timer1.attachInterrupt(countDownOneSecond);
}

void loop() {
  
  }

void countDownOneSecond() {
  int temp1 = DHT1.read11(temperatureSensorPin1);

switch (temp1)
  {
    case DHTLIB_OK:  
        Serial.print("OK,\t"); 
        break;
    case DHTLIB_ERROR_CHECKSUM: 
        Serial.print("Checksum error,\t"); 
        break;
    case DHTLIB_ERROR_TIMEOUT: 
        Serial.print("Time out error,\t"); 
        break;
    case DHTLIB_ERROR_CONNECT:
        Serial.print("Connect error,\t");
        break;
    case DHTLIB_ERROR_ACK_L:
        Serial.print("Ack Low error,\t");
        break;
    case DHTLIB_ERROR_ACK_H:
        Serial.print("Ack High error,\t");
        break;
    default: 
        Serial.print("Unknown error,\t"); 
        break;
  }

  currentMillis = millis();
  if(currentMillis - previousMillis > interval) {
    previousMillis = currentMillis;
  }
  String data="temp1="+String(DHT1.temperature, 0)+"&temp2="+String(DHT2.temperature, 0)+"&temp3="+String(DHT3.temperature, 0)+"&temp4="+String(DHT4.temperature, 0)+"&temp5="+String(DHT5.temperature, 0)
                  +"&hum1="+String(DHT1.humidity, 0)+"&hum2="+String(DHT2.humidity, 0)+"&hum3="+String(DHT3.humidity, 0)+"&hum4="+String(DHT4.humidity, 0)+"&hum5="+String(DHT5.humidity, 0)+"&gas="+gasValue;

  Serial.println(Ethernet.localIP());
  if(client.connect("192.168.0.1", 80)) {
    client.println("POST /home/visualisation.php HTTP/1.1");
    client.println("Host:  192.168.0.1");
    //client.println("User-Agent: Arduino/1.0");
    //client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(data.length());
    client.println();
    client.print(data);
    //Serial.println(data);
    Serial.println("We have connection");
  }

  if(client.connected()) {    
    Serial.println("We have abc");
    client.stop();
  }

  delay(4000);


  
  timeLeft--;
  Serial.println(timeLeft);

  if(timeLeft == 0) {
    Timer1.stop();
  }
}
