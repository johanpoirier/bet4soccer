<?php

class DateIterator implements Iterator {
	private $increment;
	private $format;
	private $startDate;
	private $endDate;

	private $currentDate;

	private $iterations = 0;

	/**
	 *
	 * @param string $increment Anything that strtotime can understand. eg. day, week, month, year
	 * @param int|string $startDate
	 * @param int|string $endDate
	 * @return
	 */
	function __construct($increment, $startDate, $endDate, $format=false) {
		$this->increment = $increment;

		if(is_int($startDate)) {
			$this->startDate = $startDate;
		} else {
			$this->startDate = strtotime($startDate);
		}

		if(is_int($endDate)) {
			$this->endDate = $endDate;
		} else {
			$this->endDate = strtotime($endDate);
		}
		$this->currentDate = $this->startDate;

		if($format) {
			$this->format = $format;
		}
		else {
			$this->format = 'Y-m-d';
		}
	}

	function current() {
		return date($this->format, $this->currentDate);
	}

	function next() {
		$current = date($this->format, $this->currentDate);
		$this->currentDate = strtotime($current." + 1 ".$this->increment);
		$this->iterations ++;
	}

	function valid() {
		return $this->currentDate <= $this->endDate;
	}

	function rewind() {
		$this->currentDate = $this->startDate;
	}
	function key() {
		return $this->iterations;
	}
}
