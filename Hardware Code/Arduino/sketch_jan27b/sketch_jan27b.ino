
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

// Replace with your network credentials
const char* ssid = "Galaxy S6 active 6927";
const char* password = "qjxu4534";
IPAddress server_addr(192,168,43,219);  // IP of the MySQL *server* here
char* user = "root";              // MySQL user login username
char* dbpass = "root";

WiFiClient client;
MySQL_Connection conn((Client *)&client);


void setup(void){
  pinMode(LED_BUILTIN, OUTPUT);
  digitalWrite(LED_BUILTIN, HIGH);
  // preparing GPIOs
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  Serial.println("");

  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("MAC Address: ");
  Serial.println(WiFi.macAddress());
while(!conn.connect(server_addr, 3306, user, dbpass)){
  delay(1000);
  //Serial.println(".");
}
Serial.println("You have connected");
}
 
void loop(void){
  int totaltime = 0;
  delay(50);
  totaltime += 50;
  //Serial.println("\nRunning SELECT and printing results\n");
  if(totaltime % 200 == 0){
    digitalWrite(LED_BUILTIN, LOW);
  }else{
    digitalWrite(LED_BUILTIN, HIGH);
  }
  // Initiate the query class instance
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  char* query = "Select Addon_MAC, Addon_Pin, Addon_State from SeniorProject.Addon";
  //char* query = "Select * from SeniorProject.Addon";
  cur_mem->execute(query);
  // Fetch the columns and print them
  column_names *cols = cur_mem->get_columns();
  // printing the column names.
  for (int f = 0; f < cols->num_fields; f++) {
    //Serial.print(cols->fields[f]->name);
    if (f < cols->num_fields-1) {
      //Serial.print(", ");
    }
  }
  //Serial.println();
  // Read the rows and print them
  row_values *row = NULL;
  do {
    row = cur_mem->get_next_row();
    if (row != NULL) {
      for (int f = 0; f < cols->num_fields; f++) {
        // Printing each value. Send the values to the gpio function for pin manipulation
        // Send specific index to the gpio function. As of now, check the ip, index length-1, and index length-2
         String mac = row->values[0];
          //Serial.print("From db: ");
         // Serial.println(row->values[0]);
          // Serial.println(WiFi.macAddress());
        if(mac == WiFi.macAddress()){
          gpio(atoi(row->values[1]), atof(row->values[2]));
         // Serial.println("Made it here");
       }
        if (f < cols->num_fields-1) {
         //Serial.print(", ");
        }
      }
     // Serial.println();
    }
  } while (row != NULL);
  //Serial.println("Deleting Cursor");
  delete cur_mem;
}

//This function is passed a pin and state to determine if it is to be shut off or turned on.
void gpio(int pin, float state){
  pinMode(pin, OUTPUT);
  //Serial.println(state);
  if(state == 1 && digitalRead(pin) < 1){
    digitalWrite(pin, HIGH);
   // Serial.println("Turn on");
  }else if(state == 0 && digitalRead(pin) > 0){
    digitalWrite(pin, LOW);
   // Serial.println("Turn off");
  }else{
    int turns = state * 255;
    analogWrite(pin, turns);
   // Serial.println("Dim");
  }
}
