<?php

class hello extends Controller {
    function index() {
		echo "Hello, world!";
    }

    function another() {
        return false;
    }
}
