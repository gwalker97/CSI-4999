
//theworldisquiethere

import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.*;
import java.io.*;

public class PinCheck {

	private double timer;
	private GpioPinDigitalInput pin;
	private boolean sent;
	private PinState last;

	public PinCheck() {

		timer = 0;
		pin = GpioFactory.getInstance().provisionDigitalInputPin(RaspiPin.GPIO_01, "pin1", PinPullResistance.PULL_DOWN);
		sent = false;

		pin.setShutdownOptions(true, PinState.LOW);
		pinListener();

		start();

	}

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

		String commandStr = "/home/pi/java/GPIO/";

		last = pin.getState();

		if(last == PinState.LOW) {

			commandStr += "notfull.sh";

		} else if(last == PinState.HIGH){

			commandStr += "full.sh";

		}

		String[] commandAndArgs = new String[]{"/bin/sh", "-c", commandStr};

		try {

			Process p = java.lang.Runtime.getRuntime().exec(commandAndArgs);

			p.waitFor();

		} catch (Exception e) {}

		sent = true;

	}

	private void start() {

		send();

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
