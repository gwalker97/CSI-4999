
//theworldisquiethere

import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.*;
import java.io.*;

public class Test {

	private double timer;
	private GpioPinDigitalInput pin;
	private boolean sent, run;
	private PinState last;
	private int count;
	private final GpioController gpio;

	public Test() {

		gpio = GpioFactory.getInstance();
		timer = 0; count = 0;
		pin = gpio.provisionDigitalInputPin(RaspiPin.GPIO_01, "pin1", PinPullResistance.PULL_DOWN);
		sent = false;
		run = true;

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
				System.out.println("[" + pin.getState() + "]");
	
			}

		});

	}

	private void send() {

		last = pin.getState();

		sent = true;

		count++;

		if (count == 5) run = false;

		System.out.println("[" + last + "]");

	}

	private void start() {

		send();

		while (run) {

		/*	if (!sent && pin.getState() != last && (System.currentTimeMillis() - timer) >= 1000) {

				send();

			}*/

			try {

				Thread.sleep(1000);

			} catch (InterruptedException e) {}

		}		

		gpio.shutdown();

	}

	public static void main(String args[]) throws InterruptedException {

		Test stanley = new Test();

	}

}
