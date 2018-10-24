import RPi.GPIO as gpio
import temp

pin = 17


def coolOn():
	global pin
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pin, gpio.OUT)
	if(gpio.input(pin) != 1):
		gpio.output(pin, gpio.HIGH)
		#return "turn on"
def coolOff():
	global pin
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pin, gpio.OUT)
	if(gpio.input(pin)):
		gpio.output(pin, gpio.LOW)
		#return "turn off"
