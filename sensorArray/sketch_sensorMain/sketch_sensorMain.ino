#include <cmath>

class waterParams {
  private:
  
    float ph;
    float dissovledOxygen;
    float temperature;
    float waterLevel;
    String errorMessage;
    String payload;

    float returnRound(float value) {
      return std::floor((value * 100) + .5) / 100;
    }
    void sendPayload() {
      payload = returnRound(ph) + ";" + returnRound(dissovledOxygen) +  ";" + returnRound(temperature) +  ";" + returnRound(waterLevel) +  ";" + returnRound(errorMessage);
      Serial.print(payload);
    }
    
  public:
  
    void waterParams(float ph, float dissovledOxygen, float temperature, float waterLevel, String errorMessage) {
      this.ph = ph;
      this.dissovledOxygen = dissovledOxygen;
      this.temperature = temperature;
      this.waterLevel = waterLevel;
      this.errorMessage = errorMessage;
      sendPayload();
    }
}

void setup() {
  // put your setup code here, to run once:



  
  // remember to set up serial port!
}

void loop() {
  // put your main code here, to run repeatedly:

}
