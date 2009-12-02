<?php
/**
 * Csv Library v1.0
 *
 * @author alexis cretinoir
 */
 class Csv
{
	protected $file = null;
	protected $delimiter = null;
	protected $enclosure = null;
	protected $data = array();

	public function __construct($file = null, $delimiter = ',', $enclosure = '"')
	{
		$this->setFile($file);
		$this->setDelimiter($delimiter);
		$this->setEnclosure($enclosure);
		$row = 0;
		$handle = fopen($file, "r");
		while (($line = fgetcsv($handle, 0, (is_null($delimiter)?',':$delimiter), (is_null($enclosure)?'"':$enclosure))) !== FALSE) {
			$this->data[$row] = $line;
			$row++;
		}
		fclose($handle);
	}
	
	public function getNumberLine()
	{
		return count($this->getData());
	}
	
	public function getSizeLine($num = 0)
	{
		$line = $this->getData();
		return count($line[$num]);
	}
	
	public function getLine($line = 0)
	{
		return $this->data[$line];
	}
	
	public function getCell($line = 0, $column = 0)
	{
		return $this->data[$line][$column];
	}
	
	public function getFile()
	{
		return $this->file;
	}

	public function setFile($file_new = null){
		if(!is_null($file_new))
		{
			$this->file = $file_new;
		}
	}
	
	public function getDelimiter()
	{
		return (is_null($this->delimiter)? ',':$this->delimiter);
	}

	public function setDelimiter($delimiter_new = null){
		if(!is_null($delimiter_new))
		{
			$this->delimiter = $delimiter_new;
		}
	}
	
	public function getEnclosure()
	{
		return (is_null($this->enclosure)? '"':$this->enclosure);
	}

	public function setEnclosure($enclosure_new = '"'){
		if(!is_null($enclosure_new))
		{
			$this->enclosure = $enclosure_new;
		}
	}
	
	public function getData()
	{
		return $this->data;
	}

	public function setData($data_new = null){
		if(!is_null($data_new))
		{
			$this->data = $data_new;
		}
	}
}