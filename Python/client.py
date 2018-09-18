import socket, threading, subprocess, pickle

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
		return self.Type
	def getPin(self):
		return self.pin
	def getState(self):
		return self.state
	#The payload will contain messages in text to be sent a displayed
	def getPayload(self):
		return self.payload	

#Receive data from the server0
def receive(conn):
	recData = True
	print "I am at function receive"
	while recData:
		reply = conn.recv(1024)
		if reply == "end!":
			print "Server has sent escape character!"	
			recData = False
	
			#Deserailize the object here
			

#Send data to the client
def send(conn):
	endChat = False
	while not endChat:
		mess = raw_input("Message: ")
		if mess != "end!":
			obj = message("", 18, "on", mess)
			data = pickle.dumps(obj)
			conn.send(data)
		else:
			conn.send("end!")
			conn.close()
			endChat = True
	
def main():
	#creates socket 's'
	server = socket.socket()

	#Asks for port used for server (type 'str')
	port = raw_input("Enter the port you want to connect to: ")
	#port = 1234

	#Aks for server ip
	#serv_ip = raw_input("Please enter the servers ip address: ")
	#serv_ip = '169.254.152.22'
	serv_ip = '127.0.0.1'

	#Attempts to connect to the server if it is listening
	server.connect((serv_ip, int(port)))

	t = threading.Thread(target=receive, args=(server,))
	t.start()
	send(server)
	print 'Connecting to IP %s through Port %s' %(serv_ip, port)
	
	#Prints the received data from the server up to 1024 byte buffer limit
	#This is only used for non-threaded test


if __name__ == '__main__':
	main()
