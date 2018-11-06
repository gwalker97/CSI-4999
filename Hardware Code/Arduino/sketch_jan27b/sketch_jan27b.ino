
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <EEPROM.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>
//qjxu4534 is the old password
// Replace with your network credentials
const char *ssidHost = "ESPap";
const char *passwordHost = "onewordalllowercase";
IPAddress server_addr(192,168,43,219); // IP of the MySQL *server* here
char* user = "root";              // MySQL user login username
char* dbpass = "root";
int LED = 2;
WiFiClient client;
ESP8266WebServer server(80);
MySQL_Connection conn((Client *)&client);
bool connected = true;
bool hosting = false;
String st;
String content;
int statusCode;
char ssid[32];
char password[32];
char ipAddr[16] = "192,168,43,219";//Pi Access Point IP-Adr.


bool tryConn(){
  EEPROM.begin(512);
  readEEPROM(0,32,ssid);
  readEEPROM(32,32,password);
  readEEPROM(64,16,ipAddr);
   WiFi.begin(ssid, password);
  Serial.println("");
  int count = 0; // Holds the timing variable.
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    count = count + 1;
    if( count >= 10){
      connected = false;
      break;
    }
    Serial.println(".");
  }
  if(connected){
    Serial.println("Connected to Wifi!");
    
WiFi.softAPdisconnect(true);
Serial.println("Stopped AP");
    while(!conn.connect(server_addr, 3306, user, dbpass)){
     delay(1000);
  //Serial.println(".");
}

 MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
 cur_mem->execute("use SeniorProject");
Serial.println("You have connected");
}
return connected;
}

void hostWifi(){
  Serial.println("Did not connect!");
    Serial.println(ssidHost);
    Serial.println(passwordHost);
    WiFi.softAP(ssidHost, passwordHost);
    IPAddress myIP = WiFi.softAPIP();
  Serial.print("AP IP address: ");
  Serial.println(myIP);
  createWebServer(1);
  //server.on("/", createWebServer(1));
  server.begin();
  Serial.println("HTTP server started");
  hosting = true;
}
  
//setup function
void setup(void){
  EEPROM.begin(512);
  pinMode(LED, OUTPUT);
  // preparing GPIOs
  Serial.begin(115200);
  delay(100);

  
  //strcat(ssid, "Galaxy S6 active 6927");
  //strcat(password, "qjxu4534");

  //writeEEPROM(0,32,ssid);//32 byte max length
  //writeEEPROM(32,32, password);//32 byte max length
  //writeEEPROM(64,16, ipAddr);//16 byte max length
  /*85 byte saved in total?*/  
 if (!tryConn()){
    hostWifi();
  }
}
 
void loop(void){
  if(connected){
    digitalWrite(LED, HIGH);
    queryDB();
  }else if(hosting){
    //Serial.println("Hosting");
    server.handleClient();
    //handleRoot();
    //server.createWebServer(1);
  }
  else{
    hostWifi();
    tryConn();
    hosting = true;
  }
}

void queryDB(){
  delay(50);
 digitalWrite(LED, LOW);
 if(WiFi.status() != WL_CONNECTED){
  connected = false;
 }else{
  connected = true;  
 }
   //Serial.println("\nRunning SELECT and printing results\n");
  // Initiate the query class instance
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  char* query = "SELECT Hosts.Host_Mac, Addon.Addon_Pin, Addon.Addon_State, Addon.Addon_Type from Addon INNER JOIN Hosts on Addon.Addon_Host_ID = Hosts.Host_ID;";
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
          gpio(atoi(row->values[1]), atof(row->values[2]), row->values[3]);
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
void gpio(int pin, float state, String type){
  pinMode(pin, OUTPUT);
  //Serial.println(type);
  //Serial.println(state);
  if (type == "F"){
    int turns = state * 10  * 255;
    analogWrite(pin, turns);
  }else{
     //Serial.println(state);
  if(state == 1 && digitalRead(pin) < 1){
    digitalWrite(pin, HIGH);
   // Serial.println("Turn on");
  }else if(state == 0 && digitalRead(pin) > 0){
    digitalWrite(pin, LOW);
   // Serial.println("Turn off");
  }else if(state != 1 && state != 0){
    int turns = state * 255;
    //Serial.println(pin);
    //Serial.println("Here at Dim");
    analogWrite(pin, turns);
    //Serial.println("Dim");
  }
  }
}


void createWebServer(int webtype)
{
  if ( webtype == 1 ) {
    server.on("/", []() {
        IPAddress ip = WiFi.softAPIP();
        String ipStr = String(ip[0]) + '.' + String(ip[1]) + '.' + String(ip[2]) + '.' + String(ip[3]);
        content = "<!DOCTYPE HTML>\r\n<html>Hello from ESP8266 at ";
        content += ipStr;
        content += "<p>";
        content += st;
        content += "</p><form method='get' action='setting'><label>SSID: </label><input name='ssid' length=32><label>Password: </label><input name='pass' length=64>";
        content += "<label>Pi IP Address: </label><input name='dbip' length=64><input type='submit'></form>";
        content += "</html>";
        server.send(200, "text/html", content);  
    });
    server.on("/setting", []() {
        String qsid = server.arg("ssid");
        String qpass = server.arg("pass");
        String qip = server.arg("dbip");
        if (qsid.length() > 0 && qpass.length() > 0) {
          Serial.println("clearing eeprom");
          for (int i = 0; i < 96; ++i) { EEPROM.write(i, 0); }
          Serial.println(qsid);
          Serial.println("");
          Serial.println(qpass);
          Serial.println("");
          Serial.println(qip);
          Serial.println("");
          Serial.println("writing eeprom ssid:");
          writeEEPROM(0,32,qsid);
          Serial.println("writing eeprom pass:");
          writeEEPROM(32,32,qpass);
          Serial.println("writing eeprom ip:"); 
          writeEEPROM(64,16,qip); 
          EEPROM.commit();
          content = "{\"Success\":\"saved to eeprom... reset to boot into new wifi\"}";
          statusCode = 200;
        } else {
          content = "{\"Error\":\"404 not found\"}";
          statusCode = 404;
          Serial.println("Sending 404");
        }
        server.send(statusCode, "application/json", content);
    });
  } else if (webtype == 0) {
    server.on("/", []() {
      IPAddress ip = WiFi.localIP();
      String ipStr = String(ip[0]) + '.' + String(ip[1]) + '.' + String(ip[2]) + '.' + String(ip[3]);
      server.send(200, "application/json", "{\"IP\":\"" + ipStr + "\"}");
    });
    server.on("/cleareeprom", []() {
      content = "<!DOCTYPE HTML>\r\n<html>";
      content += "<p>Clearing the EEPROM</p></html>";
      server.send(200, "text/html", content);
      Serial.println("clearing eeprom");
      for (int i = 0; i < 96; ++i) { EEPROM.write(i, 0); }
      EEPROM.commit();
    });
  }
}



//startAdr: offset (bytes), writeString: String to be written to EEPROM
void writeEEPROM(int startAdr, int laenge, String writeString) {
  EEPROM.begin(512); //Max bytes of eeprom to use
  yield();
  Serial.println();
  Serial.print("writing EEPROM: ");
  //write to eeprom 
  for (int i = 0; i < laenge; i++)
    {
      EEPROM.write(startAdr + i, writeString[i]);
      Serial.print(writeString[i]);
    }
  EEPROM.commit();
  EEPROM.end();           
}

void readEEPROM(int startAdr, int maxLength, char* dest) {
  EEPROM.begin(512);
  delay(10);
  for (int i = 0; i < maxLength; i++)
    {
      dest[i] = char(EEPROM.read(startAdr + i));
    }
  EEPROM.end();    
  Serial.print("ready reading EEPROM:");
  Serial.println(dest);
}
