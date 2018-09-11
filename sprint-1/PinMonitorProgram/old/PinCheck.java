
//theworldisquiethere

import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.*;
import java.io.*;

public class PinCheck {

	private double timer; // Tracks time since last pin state change.
	private GpioPinDigitalInput pin; // A specific GPIO pin on the Raspberry Pi.
	private boolean sent; // Tracks if the current accepted pin state has been reported.
	private PinState last; // The last sent pin state.

	public PinCheck() {

		// Initialize variables
		timer = 0;
		sent = false;
		pin = GpioFactory.getInstance().provisionDigitalInputPin(RaspiPin.GPIO_01, "pin1", PinPullResistance.PULL_DOWN);
			pin.setShutdownOptions(true, PinState.LOW);

		// Create a pin listener on the new pin.
		pinListener();

		// Start monitor loop.
		start();

	}

	// Every time the pin's state changes the event will trigger.
	// A state change resets the timer and sent variables.
	private void pinListener() {

		pin.addListener(new GpioPinListenerDigital() {

			@Override
			public void handleGpioPinDigitalStateChangeEvent(GpioPinDigitalStateChangeEvent event) {

				timer = System.currentTimeMillis();
				sent = false;

			}

		});

	}

	private void send() {

		// Common part of path to scripts. (on the raspberry pi)
		String commandStr = "/home/pi/java/GPIO/";

		// Update pin state.
		last = pin.getState();

		// Complete script path, based on the state being sent.
		if(last == PinState.LOW) {

			commandStr += "notfull.sh";

		} else if(last == PinState.HIGH){

			commandStr += "full.sh";

		}

		String[] commandAndArgs = new String[]{"/bin/sh", "-c", commandStr};

		// Excecute bash scripts to send new state to website. (using curl)
		try {

			Process p = java.lang.Runtime.getRuntime().exec(commandAndArgs);

			p.waitFor();

		} catch (Exception e) {}

		// Mark current state as sent.
		sent = true;

	}

	private void start() {

		// Initial update.
		send();

		// Main program loop. ( Decide if sending data is necessary. )
		//	- Poll pin, checking for:
		//		- Pin state has changed.
		//		- New state has not been sent.
		//		- State change happened at least 2 seconds ago.
		//			. (more reliable state changes)
		//	- Sleep 1 second.
		while (true) {

			if (!sent && pin.getState() != last && (System.currentTimeMillis() - timer) >= 2000) {

				send();

			}

			try {

				Thread.sleep(1000);

			} catch (InterruptedException e) {}

		}		

	}

	public static void main(String args[]) throws InterruptedException {

		PinCheck stanley = new PinCheck();

	}

}
