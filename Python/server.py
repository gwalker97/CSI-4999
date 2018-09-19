#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import socket, threading, subprocess, pickle
from mysql.connector import MySQLConnection, Error
from python_mysql_dbconfig import read_db_config
#import RPi.GPIO as GPIO
import time

class message(object):
	Type = ""
	pin = 0
	state = ""
	payload = ""

#messType holds the type of message that is being sent
#Types: GPIO - used for power; Noti - notification; end - End connection; conn - Connect to server
	def __init__(self, Type, pin, state, payload):
		self.Type = Type
		self.pin = pin
		self.state = state
		self.payload = payload
	def getType(self):
		return Type
	def getPin(self):
		return pin
	def getState(self):
		return state
	#The payload will contain messages in text to be sent a displayed
	def getPayload(self):
		return payload	


#Runs the script that sets up ethernet based on given ip and connection name
def setupEth(IP, Name):
	subprocess.call(['./eth0On %s %s sudo' %(IP, Name)], shell=True)

def query_with_fetchall():
    try:
        dbconfig = read_db_config()
        conn = MySQLConnection(**dbconfig)
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM books")
        rows = cursor.fetchall()
 
        print('Total Row(s):', cursor.rowcount)
        for row in rows:
            print(row)
 
    except Error as e:
        print(e)
 
    finally:
        cursor.close()
        conn.close()


#Sends messages to the GPIO Pins
def GPIO(pinNum, On_Off):
	state = ""
	not_state = ""
	#This statement handles the state which the LED is on
	#Right now the inverse is used to turn the light off.
	if On_Off == "high":
		state = GPIO.HIGH
		#not_state = GPIO.LOW
	else:
		state = GPIO.LOW
		#not_state = GPIO.HIGH
	pin = int(pinNum)
	GPIO.setup(pin,GPIO.OUT)
	print "LED on"
	GPIO.output(pin,state)
	#time.sleep(1)
	#print "LED off"
	#GPIO.output(pin,state)
	return "Pin %s has is now %s!" %(pin,state)

#Sends messages to the client
#def sendClient():
#	while True:
#Receives messages from the client
def parseGPIO(obj):
	
	print "Pin: %s / State: %s / Message: %s" %(obj.pin, obj.state, obj.payload)



#Temp for sending and receiving from client
def clientThread(conn):
	conn.send("You have connected!\n")
	boolGo = True
	try:
		while boolGo:
			data = conn.recv(4096)
			obj = pickle.loads(data)
			data.strip("\n")
			reply = "I got your message!"
			mess = message("", 0, "", "")
			mess = obj
			parseGPIO(mess)
			print "%s" %(reply)
			if "end!" in data:
				print "Client has closed connection"
				conn.send("end!")
				boolGo = False
			else:
				newMess = message("", "", "", "I got ur message")
				reply = pickle.dumps(newMess)
				conn.send(reply)
				
	finally:
		conn.close()
		print "Socket has been closed."
def main():
	s = socket.socket()
	s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	print "Socket successfully created"


	#Ask for the port we wish to listen on (type 'str')
	#port = raw_input("Please enter the port you wish to listen on: ")
	port = 1234
	#Ask for the ip address of the server. This will be used on the client side as well 	to connect to the server. (type 'str')
	ip = raw_input("Enter the ip address of the server: ")
	#ip = '169.254.152.22'
	
	#ip = '127.0.0.1'
	#Ask for the name of the connection. (eth0, ens, eps, etc...) 
	#connName = raw_input("Enter the connection name: ")
	connName = 'ens37'

	boolVal = True


	#Ask the user if they wish to setup ethernet using a script
	#while boolVal:
	#	select = raw_input("Do you want to setup ethernet now? (Y/N):")
	#	if (select.lower() == 'y'):
	#		setupEth(ip, connName)
	#		boolVal = False
	#	elif (select.lower() == 'n'):
	#		boolVal = False
	#Using the ip and connName varaibles, we will pass them into a script to setup 		ethernet for this device if not already done.
	s.bind((ip, int(port)))

	print "socket binded to IP %s and Port %s" %(ip, port)

	s.listen(5)

	print "socket is listening"

	#Used for non-threaded socket test

	#Establish connection with the client
	print "Waiting for client connection..."
	client, addr = s.accept()
	print 'Got connection from ', addr
	#Setting up the modes for the GPIO
	#print "here"
	#GPIO.setmode(GPIO.BOARD)
	#GPIO.setwarnings(False)
	t = threading.Thread(target=clientThread, args=(client,))
	t.start()

if __name__ =='__main__':
	main()
