
//theworldisquiethere

import com.pi4j.io.gpio.*;
import com.pi4j.io.gpio.event.*;
import java.io.*;

public class Test2 {

	private double timer;
	private GpioPinDigitalInput pin;
	private boolean sent, run;
	private PinState last;
	private int count;

	public Test2() {

		timer = 0; count = 0;
		pin = GpioFactory.getInstance().provisionDigitalInputPin(RaspiPin.GPIO_01, "pin1", PinPullResistance.PULL_DOWN);
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

				//timer = System.currentTimeMillis();
				//sent = false;
				count++;
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


		while (run) {

			if (count > 10) run = false;

		}

	}

	public static void main(String args[]) throws InterruptedException {

		Test2 stanley = new Test2();

	}

}
