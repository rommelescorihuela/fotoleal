<?php
namespace lucasguo\import\consumers;

use lucasguo\import\components\Importer;

interface ConsumerInterface
{
	/**
	 * Import use this interface to consume the input file into array as below
	 * [
	 * 	1 => ['row1col1', 'row1col2'],
	 * 	2 => ['row2col1', 'row2col2'],
	 * ]
	 * the index is the source input file line number
	 * consumer takes responsible to call $importer->addSkipRow($i) to notify which rows have been skipped
	 * @param Importer
	 * @return array
	 */
	public function consume(&$importer);
}