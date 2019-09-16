<?php
namespace lucasguo\import\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use lucasguo\import\components\DataMapping;
use lucasguo\import\consumers\ExcelConsumer;
use lucasguo\import\generators\ModelGenerator;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;

class Importer extends Component
{
	const SOURCE_FORMAT_EXCEL = "excel";
	
	/**
	 * @var string
	 * If use preset format, specific it in this attribute.
	 * Default to Import::SOURCE_FORMAT_EXCEL
	 */
	public $format = self::SOURCE_FORMAT_EXCEL;
	/**
	 * @var string
	 * The name of class which transform file into data array.
	 */
	public $fileConsumerClass;
	/**
	 * @var string
	 * The name of class which transform the data array into models.
	 */
	public $modelGeneratorClass;
	/**
	 * @var string
	 * The name of model which want to generate by this import process
	 */
	public $modelClass;
	/**
	 * @var array
	 * Define the rules how the file transform into the wanted model
	 * The rule can be simple as the attribute name, or a configuration array.
	 * possible configuration option, please @see DataMapping
	 * this configuration array is mapped to data columns one by one.
	 * if the attribute index is different from the element index in this array, please specific colIndex attribute
	 * Please notice colIndex is 0-based.
	 * 
	 * One example:
	 * ```php
	 * [
	 * 	'field1',
	 * 	[
	 * 		'attribute' => 'field2'
	 * 		'required' => true,
	 * 		'colIndex' => 4,
	 * 		'translation' => function($orgValue) {
	 * 			return $newValue;
	 * 		}
	 * 	],
	 * 	...
	 * ]
	 * ```
	 */
	public $columnMappings = [];
	/**
	 * @var string|UploadedFile
	 * the file to be processed.
	 */
	public $file;
	/**
	 * @var integer
	 * if the file has some header rows, specfic this value to skip these header rows.
	 */
	public $skipRowsCount = 0;
	/**
	 * @var string
	 * the name of class which deal with $columnMappings elements
	 * default to DataMapping
	 */
	public $mappingClass;
	/**
	 * @var array
	 * the collections of mappingClass
	 */
	protected $_columns;
	/**
	 * @var array
	 * the array which contains the row number of success processed row
	 */
	protected $_importRows;
	/**
	 * @var array
	 * the array which contains the row number of not processed row
	 */
	protected $_skipRows;
	
	/**
	 * get highest col index after init column mapping
	 * @var integer
	 */
	protected $_maxColIndex;
	
	protected $_requiredCols;
	
	public $validateWhenImport;
	
	protected $_validationErrors;
	
	public $modelScenario;
	
	
	public function getImportRows() {
		if ($this->_importRows === null) {
			throw new InvalidCallException("This method is callable after import() done");
		}
		return $this->_importRows;
	}
	
	public function getSkipRows() {
		if ($this->_skipRows === null) {
			throw new InvalidCallException("This method is callable after import() done");
		}
		return $this->_skipRows;
	}
	
	public function getRequiredCols() {
		return $this->_requiredCols;
	}
	
	public function getMaxColIndex() {
		return $this->_maxColIndex;
	}
	
	public function addSuccessRow($rowNumber) {
		$this->_importRows[] = $rowNumber;
	}
	
	public function addSkipRow($rowNumber) {
		$this->_skipRows[] = $rowNumber;
	}
	
	public function getColumns() {
		return $this->_columns;
	}
	
	public function getValidationErrors() {
		if ($this->_validationErrors === null) {
			throw new InvalidCallException("This method is callable after import() done");
		}
		if (!$this->validateWhenImport) {
			throw new InvalidCallException("This method is only available when validateWhenImport set to true");
		}
		return $this->_validationErrors;
	}
	
	public function addValidationErrors($rowNumberAndErrors) {
		$this->_validationErrors = ArrayHelper::merge($this->_validationErrors, $rowNumberAndErrors);
	}
	
	public function init()
	{
		if ($this->modelClass === null) {
			throw new InvalidConfigException("\$modelClass must be provided");
		}
		if ($this->file instanceof UploadedFile) {
			$this->file = $this->file->tempName;
		}
		if (!is_string($this->file)) {
			throw new InvalidConfigException("Import file must be provided");
		}
		if ($this->fileConsumerClass === null) {
			$this->fileConsumerClass = $this->getPresetConsumers()[$this->format];
			if ($this->fileConsumerClass === null) {
				throw new InvalidConfigException("\$format is not valid");
			}
		}
		if ($this->modelGeneratorClass === null) {
			$this->modelGeneratorClass = ModelGenerator::className();
		}
		$this->initMappings();
		$this->_maxColIndex = max(array_keys($this->_columns));
	}
	
	protected function getPresetConsumers() 
	{
		return [
			static::SOURCE_FORMAT_EXCEL => ExcelConsumer::className(),
		];		
	}
	
	protected function initMappings()
	{
		if (empty($this->columnMappings)) {
			$this->guessMappings();
		}
		$this->_requiredCols = [];
		foreach ($this->columnMappings as $i => $mapping) {
			if (is_string($mapping)) {
				$mapping = ['attribute' => $mapping];
			}
			if (isset($mapping['colIndex'])) {
				$colIndex = $mapping['colIndex'];
				unset($mapping['colIndex']);
			} else {
				$colIndex = $i;
			}
			$mapping = Yii::createObject(array_merge([
					'class' => $this->mappingClass ? : DataMapping::className(),
				], $mapping));
			$this->_columns[$colIndex] = $mapping;
			if ($mapping->required) {
				$this->_requiredCols[] = $colIndex;
			}
		}
	}
	
	protected function guessMappings()
	{
		$model = new $this->modelClass;
		foreach ($model as $name => $value) {
			$this->columnMappings[] = (string) $name;
		}
	}
	
	public function import(){
		$this->_importRows = [];
		$this->_skipRows = [];
		$this->_validationErrors = [];
		$consumer = new $this->fileConsumerClass;
		$data =  $consumer->consume($this);
		$generator = new $this->modelGeneratorClass;
		$models = $generator->generate($data, $this);
		foreach ($models as $lineno => $model) {
			if ($this->validateWhenImport) {
				if ($this->modelScenario !== null) {
					$model->setScenario($this->modelScenario);
				}
				if (!$model->validate()) {
					$this->addValidationErrors([$lineno => $model->getErrors()]);
					unset($models[$lineno]);
				} else {
					$this->addSuccessRow($lineno);
				}
			} else {
				$this->addSuccessRow($lineno);
			}
		}
		unset($data);
		return $models;
	}
}