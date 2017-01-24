<?php

	// for proper testing we need to point the working directory to the service
	chdir("./tests/Service");

	// prevent auto start of service
	define("MICROLA_PREVENT_AUTOSTART", true);

	// include autoloader
	include("../../vendor/autoload.php");

	// include support files
	include("../Support/FakeRestCall.php");