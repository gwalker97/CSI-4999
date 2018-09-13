#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import socket, threading, subprocess
#import RPi.GPIO as GPIO
import time

class message:

#messType holds the type of message that is being sent
#Types: GPIO - used for power; Noti - notification; end - End connection; conn - Connect to server
	def __init__(messType, kind = "mes"):
		self.kind = kind

	def getType():
		print "this is dumb"


#Runs the script that sets up ethernet based on given ip and connection name
def setupEth(IP, Name):
	subprocess.call(['./eth0On %s %s sudo' %(IP, Name)], shell=True)

#Sends messages to the GPIO Pins
def GPIO():
	print "here"
#	GPIO.setmode(GPIO.BCM)
#	GPIO.setwarnings(False)
#	GPIO.setup(18,GPIO.OUT)
#	print "LED on"
#	GPIO.output(18,GPIO.HIGH)
#	time.sleep(1)
#	print "LED off"
#	GPIO.output(18,GPIO.LOW)

#Sends messages to the client
#def sendClient():
#	while True:
#Receives messages from the client
def receive():
	print "here"

#Temp for sending and receiving from client
def clientThread(conn):
	conn.send("You have connected!\n")
	boolGo = True
	try:
		while boolGo:
			data = conn.recv(1024)
			data.strip("\n")
			reply = "I got your message: " + data.decode()
			print "%s" %(reply)
			if "end!" in data:
				print "Client has closed connection"
				conn.send("end!")
				boolGo = False
	finally:
		conn.close()
		print "Socket has been closed."
def main():
	s = socket.socket()
	s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	print "Socket successfully created"


	#Ask for the port we wish to listen on (type 'str')
	port = raw_input("Please enter the port you wish to listen on: ")
	#port = 1235
	#Ask for the ip address of the server. This will be used on the client side as well 	to connect to the server. (type 'str')
	ip = raw_input("Enter the ip address of the server: ")
	#ip = '169.254.152.22'
	#Ask for the name of the connection. (eth0, ens, eps, etc...) 
	#connName = raw_input("Enter the connection name: ")
	connName = 'ens37'

	boolVal = True


	#Ask the user if they wish to setup ethernet using a script
	while boolVal:
		select = raw_input("Do you want to setup ethernet now? (Y/N):")
		if (select.lower() == 'y'):
			setupEth(ip, connName)
			boolVal = False
		elif (select.lower() == 'n'):
			boolVal = False
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
	#Send a thanks to the client
	t = threading.Thread(target=clientThread, args=(client,))
	t.start()

if __name__ =='__main__':
	main()
