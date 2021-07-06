<?php

/**
 * GiixModelCode class file.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 * @link http://giix.org/
 * @copyright Copyright &copy; 2010-2011 Rodrigo Coelho
 * @license http://giix.org/license/ New BSD License
 */
Yii::import('system.gii.generators.model.ModelCode');
Yii::import('ext.giix-core.helpers.*');

/**
 * GiixModelCode is the model for giix model generator.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 */
class GiixModelCode extends ModelCode {

	/**
	 * @var string The (base) model base class name.
	 */
	public $baseClass = 'GxActiveRecord';
	/**
	 * @var string The path of the base model.
	 */
	public $baseModelPath;
	/**
	 * @var string The base model class name.
	 */
	public $baseModelClass;

	/**
	 * Prepares the code files to be generated.
	 * #MethodTracker
	 * This method is based on {@link ModelCode::prepare}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>Generates the base model.</li>
	 * <li>Provides the representing column for the table.</li>
	 * <li>Provides the pivot class names for MANY_MANY relations.</li>
	 * </ul>
	 */
	 
	public function prepareTable($table){
		
		try{
			$command = Yii::app()->db->createCommand();
			$command->alterColumn($table->name,$table->primaryKey,"INT(11) NOT NULL AUTO_INCREMENT");
		}
		catch (CDbException $e)
		{
		  // Do something else (eg update record)
		}
		
	}
	
	public function prepare() {
		
		if (($pos = strrpos($this->tableName, '.')) !== false) {
			$schema = substr($this->tableName, 0, $pos);
			$tableName = substr($this->tableName, $pos + 1);
		} else {
			$schema = '';
			$tableName = $this->tableName;
		}
		if ($tableName[strlen($tableName) - 1] === '*') {
			$tables = Yii::app()->db->schema->getTables($schema);
			if ($this->tablePrefix != '') {
				foreach ($tables as $i => $table) {
					if (strpos($table->name, $this->tablePrefix) !== 0)
						unset($tables[$i]);
					
				}
			}
		}
		else
			$tables=array($this->getTableSchema($this->tableName));
		
		
		
		$this->files = array();
		$templatePath = $this->templatePath;

		$this->relations = $this->generateRelations();
		

		foreach ($tables as $table) {
			
			$this->prepareTable($table);
			
			$tableName = $this->removePrefix($table->name);
			$className = $this->generateClassName($table->name);

			// Generate the pivot model data.
			$pivotModels = array();
			if (isset($this->relations[$className])) {
				foreach ($this->relations[$className] as $relationName => $relationData) {
					if (preg_match('/^array\(self::MANY_MANY,.*?,\s*\'(.+?)\(/', $relationData, $matches)) {
						// Clean the table name if needed.
						$pivotTableName = str_replace(array('{', '}'), '', $matches[1]);
						$pivotModels[$relationName] = $this->generateClassName($pivotTableName);
					}
				}
			}
			
			
			$tableName = $schema === '' ? $tableName : $schema . '.' . $tableName;
			$tableComments = Metadata::getTableComments($tableName);
			
			
			$modelLables = $tableComments[0] != "" ? $tableComments[1].'|'.$tableComments[0] : $className.'|'.$this->pluralize($className);
			
			if($tableComments[2] != ""){
				$explode_representing_columns = explode(",",trim($tableComments[2]));
				if(count($explode_representing_columns) > 1){
					$representingColumn = $explode_representing_columns;
				}
				else{
					$representingColumn = $tableComments[3];
				}
			}
			else{
				$representingColumn = $this->getRepresentingColumn($table);
			}
			
			$params = array(
				'tableName' => $tableName,
				'modelClass' => $className,
				'columns' => $table->columns,
				'modelLables' => $modelLables,
				'labels' => $this->generateLabelsEx($table, $className),
				'rules' => $this->generateRules($table),
				'relations' => isset($this->relations[$className]) ? $this->relations[$className] : array(),
				'representingColumn' => $representingColumn, // The representing column for the table.
				'pivotModels' => $pivotModels, // The pivot models.
			);
			// Setup base model information.
			$this->baseModelPath = $this->modelPath . '._base';
			$this->baseModelClass = 'Base' . $className;
			// Generate the model.
			$this->files[] = new CCodeFile(
							Yii::getPathOfAlias($this->modelPath . '.' . $className) . '.php',
							$this->render($templatePath . DIRECTORY_SEPARATOR . 'model.php', $params)
			);
			// Generate the base model.
			$this->files[] = new CCodeFile(
							Yii::getPathOfAlias($this->baseModelPath . '.' . $this->baseModelClass) . '.php',
							$this->render($templatePath . DIRECTORY_SEPARATOR . '_base' . DIRECTORY_SEPARATOR . 'basemodel.php', $params)
			);
			
		}
	}

	/**
	 * Lists the template files.
	 * #MethodTracker
	 * This method is based on {@link ModelCode::requiredTemplates}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>Includes the base model.</li>
	 * </ul>
	 * @return array A list of required template filenames.
	 */
	public function requiredTemplates() {
		return array(
			'model.php',
			'_base' . DIRECTORY_SEPARATOR . 'basemodel.php',
		);
	}

	/**
	 * Generates the labels for the table fields and relations.
	 * By default, the labels for the FK fields and for the relations is null. This
	 * will cause them to be represented by the related model label.
	 * #MethodTracker
	 * This method is based on {@link ModelCode::generateLabels}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>Default label for FKs is null.</li>
	 * <li>Creates entries for the relations. The default label is null.</li>
	 * </ul>
	 * @param CDbTableSchema $table The table definition.
	 * @param string $className The model class name.
	 * @return array The labels.
	 * @see GxActiveRecord::label
	 * @see GxActiveRecord::getRelationLabel
	 */
	public function generateLabelsEx($table, $className) {
		$labels = array();
		// For the fields.
		foreach ($table->columns as $column) {
			if ($column->isForeignKey) {
				$label = null;
			} else {
				$comments = Metadata::getArrayComments($column); 
				if($comments[0] != ""){
					$label = $comments[0];
				}
				else{
					$label = ucwords(trim(strtolower(str_replace(array('-', '_'), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $column->name)))));
					$label = preg_replace('/\s+/', ' ', $label);
	
					if (strcasecmp(substr($label, -3), ' id') === 0)
						$label = substr($label, 0, -3);
					if ($label === 'Id')
						$label = 'ID';
				}
				
				$label = "Yii::t('app', '{$label}')";
			}
			$labels[$column->name] = $label;
		}
		// For the relations.
		$relations = $this->getRelationsData($className);
		if (isset($relations)) {
			foreach (array_keys($relations) as $relationName) {
				$labels[$relationName] = null;
			}
		}

		return $labels;
	}

	/**
	 * Generates the rules for table fields.
	 * #MethodTracker
	 * This method overrides {@link ModelCode::generateRules}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>Adds the rule to fill empty attributes with null.</li>
	 * </ul>
	 * @param CDbTableSchema $table The table definition.
	 * @return array The rules for the table.
	 */
	public function generateRules($table) {
		$rules = array();
		$null = array();
		foreach ($table->columns as $column) {
			if ($column->autoIncrement)
				continue;
			if (!(!$column->allowNull && $column->defaultValue === null))
				$null[] = $column->name;
			if($column->dbType == 'varchar(120)'){
				$rules[] = "array('".$column->name."', 'file', 'types'=>'jpg,png', 'allowEmpty'=>true)";
				$rules[] = "array('".$column->name."_delete', 'length', 'max'=>1)";
			}
			if($column->dbType == 'varchar(130)'){
				$rules[] = "array('".$column->name."', 'allowEmpty'=>true)";
				$rules[] = "array('".$column->name."_delete', 'length', 'max'=>1)";
			}
		}
		if ($null !== array())
			$rules[] = "array('" . implode(', ', $null) . "', 'default', 'setOnEmpty' => true, 'value' => null)";

		return array_merge(parent::generateRules($table), $rules);
	}

	/**
	 * Selects the representing column of the table.
	 * The "representingColumn" method is the responsible for the
	 * string representation of the model instance.
	 * @param CDbTableSchema $table The table definition.
	 * @return string|array The name of the column as a string or the names of the columns as an array.
	 * @see GxActiveRecord::representingColumn
	 * @see GxActiveRecord::__toString
	 */
	protected function getRepresentingColumn($table) {
		$columns = $table->columns;
		// If this is not a MANY_MANY pivot table
		if (!$this->isRelationTable($table)) {
			// First we look for a string, not null, not pk, not fk column, not original number on db.
			foreach ($columns as $name => $column) {
				if ($column->type === 'string' && !$column->allowNull && !$column->isPrimaryKey && !$column->isForeignKey && stripos($column->dbType, 'int') === false)
					return $name;
			}
			// Then a string, not null, not fk column, not original number on db.
			foreach ($columns as $name => $column) {
				if ($column->type === 'string' && !$column->allowNull && !$column->isForeignKey && stripos($column->dbType, 'int') === false)
					return $name;
			}
			// Then the first string column, not original number on db.
			foreach ($columns as $name => $column) {
				if ($column->type === 'string' && stripos($column->dbType, 'int') === false)
					return $name;
			}
		} // If the appropriate column was not found or if this is a MANY_MANY pivot table.
		// Then the pk column(s).
		$pk = $table->primaryKey;
		if ($pk !== null) {
			if (is_array($pk))
				return $pk;
			else
				return (string) $pk;
		}
		// Then the first column.
		return reset($columns)->name;
	}

	/**
	 * Finds the related class of the specified column.
	 * @param string $className The model class name.
	 * @param CDbColumnSchema $column The column.
	 * @return string The related class name. Or null if no matching relation was found.
	 */
	public function findRelatedClass($className, $column) {
		if (!$column->isForeignKey)
			return null;

		$relations = $this->getRelationsData($className);

		foreach ($relations as $relation) {
			// Must be BELONGS_TO.
			if (($relation[0] === GxActiveRecord::BELONGS_TO) && ($relation[3] === $column->name))
				return $relation[1];
		}
		// None found.
		return null;
	}

	/**
	 * Finds the relation data for all the relations of the specified model class.
	 * @param string $className The model class name.
	 * @return array An array of arrays with the relation data.
	 * The array will have one array for each relation.
	 * The key is the relation name. There are 5 values:
	 * 0: the relation type,
	 * 1: the related active record class name,
	 * 2: the joining (pivot) table (note: it may come with curly braces) (if the relation is a MANY_MANY, else null),
	 * 3: the local FK (if the relation is a BELONGS_TO or a MANY_MANY, else null),
	 * 4: the remote FK (if the relation is a HAS_ONE, a HAS_MANY or a MANY_MANY, else null).
	 * Or null if the model has no relations.
	 */
	public function getRelationsData($className) {
		if (!empty($this->relations))
			$relations = $this->relations;
		else
			$relations = $this->generateRelations();

		if (!isset($relations[$className]))
			return null;

		$result = array();
		foreach ($relations[$className] as $relationName => $relationData) {
			$result[$relationName] = $this->getRelationData($className, $relationName, $relations);
		}
		return $result;
	}

	/**
	 * Finds the relation data of the specified relation name.
	 * @param string $className The model class name.
	 * @param string $relationName The relation name.
	 * @param array $relations An array of relations for the models
	 * in the format returned by {@link ModelCode::generateRelations}. Optional.
	 * @return array The relation data. The array will have 3 values:
	 * 0: the relation type,
	 * 1: the related active record class name,
	 * 2: the joining (pivot) table (note: it may come with curly braces) (if the relation is a MANY_MANY, else null),
	 * 3: the local FK (if the relation is a BELONGS_TO or a MANY_MANY, else null),
	 * 4: the remote FK (if the relation is a HAS_ONE, a HAS_MANY or a MANY_MANY, else null).
	 * Or null if no matching relation was found.
	 */
	public function getRelationData($className, $relationName, $relations = array()) {
		if (empty($relations)) {
			if (!empty($this->relations))
				$relations = $this->relations;
			else
				$relations = $this->generateRelations();
		}

		if (isset($relations[$className]) && isset($relations[$className][$relationName]))
			$relation = $relations[$className][$relationName];
		else
			return null;

		$relationData = array();
		if (preg_match("/^array\(([\w:]+?),\s?'(\w+)',\s?'([\w\s\(\),]+?)'\)$/", $relation, $matches_base)) {
			$relationData[1] = $matches_base[2]; // the related active record class name

			switch ($matches_base[1]) {
				case 'self::BELONGS_TO':
					$relationData[0] = GxActiveRecord::BELONGS_TO; // the relation type
					$relationData[2] = null;
					$relationData[3] = $matches_base[3]; // the local FK
					$relationData[4] = null;
					break;
				case 'self::HAS_ONE':
					$relationData[0] = GxActiveRecord::HAS_ONE; // the relation type
					$relationData[2] = null;
					$relationData[3] = null;
					$relationData[4] = $matches_base[3]; // the remote FK
					break;
				case 'self::HAS_MANY':
					$relationData[0] = GxActiveRecord::HAS_MANY; // the relation type
					$relationData[2] = null;
					$relationData[3] = null;
					$relationData[4] = $matches_base[3]; // the remote FK
					break;
				case 'self::MANY_MANY':
					if (preg_match("/^((?:{{)?\w+(?:}})?)\((\w+),\s?(\w+)\)$/", $matches_base[3], $matches_manymany)) {
						$relationData[0] = GxActiveRecord::MANY_MANY; // the relation type
						$relationData[2] = $matches_manymany[1]; // the joining (pivot) table
						$relationData[3] = $matches_manymany[2]; // the local FK
						$relationData[4] = $matches_manymany[3]; // the remote FK
					}
					break;
			}

			return $relationData;
		} else
			return null;
	}

	/**
	 * Returns the message to be displayed when the newly generated code is saved successfully.
	 * #MethodTracker
	 * This method overrides {@link CCodeModel::successMessage}, from version 1.1.7 (r3135). Changes:
	 * <ul>
	 * <li>Custom giix success message.</li>
	 * </ul>
	 * @return string The message to be displayed when the newly generated code is saved successfully.
	 */
	public function successMessage() {
		return <<<EOM
<p><strong>Sweet!</strong></p>
<ul style="list-style-type: none; padding-left: 0;">
	<li><img src="http://giix.org/icons/love.png"> Show how you love giix on <a href="http://www.yiiframework.com/forum/index.php?/topic/13154-giix-%E2%80%94-gii-extended/">the forum</a> and on its <a href="http://www.yiiframework.com/extension/giix">extension page</a></li>
	<li><img src="http://giix.org/icons/vote.png"> Upvote <a href="http://www.yiiframework.com/extension/giix">giix</a></li>
	<li><img src="http://giix.org/icons/powered.png"> Show everybody that you are using giix in <a href="http://www.yiiframework.com/forum/index.php?/topic/19226-powered-by-giix/">Powered by giix</a></li>
	<li><img src="http://giix.org/icons/donate.png"> <a href="http://giix.org/">Donate</a></li>
</ul>
<p style="margin: 2px 0; position: relative; text-align: right; top: -15px; color: #668866;">icons by <a href="http://www.famfamfam.com/lab/icons/silk/" style="color: #668866;">famfamfam.com</a></p>
EOM;
	}
	
	//Sobrecarga
	protected function generateRelations()
	{
		if(!$this->buildRelations)
			return array();
		$relations=array();
		foreach(Yii::app()->{$this->connectionId}->schema->getTables() as $table)
		{
			
			if($table->name == 'gallery')
				continue;
			if($this->tablePrefix!='' && strpos($table->name,$this->tablePrefix)!==0)
				continue;
			$tableName=$table->name;
		
			if ($this->isRelationTable($table))
			{
				$pks=$table->primaryKey;
				$fks=$table->foreignKeys;

				$table0=$fks[$pks[0]][0];
				$table1=$fks[$pks[1]][0];
				$className0=$this->generateClassName($table0);
				$className1=$this->generateClassName($table1);

				$unprefixedTableName=$this->removePrefix($tableName);

				$relationName=$this->generateRelationName($table0, $table1, true);
				$relations[$className0][$relationName]="array(self::MANY_MANY, '$className1', '$unprefixedTableName($pks[0], $pks[1])')";

				$relationName=$this->generateRelationName($table1, $table0, true);

				$i=1;
				$rawName=$relationName;
				while(isset($relations[$className1][$relationName]))
					$relationName=$rawName.$i++;

				$relations[$className1][$relationName]="array(self::MANY_MANY, '$className0', '$unprefixedTableName($pks[1], $pks[0])')";
			}
			else
			{
				$className=$this->generateClassName($tableName);
				foreach ($table->foreignKeys as $fkName => $fkEntry)
				{
					// Put table and key name in variables for easier reading
					$refTable=$fkEntry[0]; // Table name that current fk references to
					$refKey=$fkEntry[1];   // Key in that table being referenced
					$refClassName=$this->generateClassName($refTable);

					// Add relation for this table
					//Alterado: $relationName=$this->generateRelationName($tableName, $fkName, false);
					$relationName=$this->generateRelationName($tableName, $this->removePrefix($refTable,false), false);
					$relations[$className][$relationName]="array(self::BELONGS_TO, '$refClassName', '$fkName')";

					// Add relation for the referenced table
					$relationType=$table->primaryKey === $fkName ? 'HAS_ONE' : 'HAS_MANY';
					$relationName=$this->generateRelationName($refTable, $this->removePrefix($tableName,false), $relationType==='HAS_MANY');
					$i=1;
					$rawName=$relationName;
					while(isset($relations[$refClassName][$relationName]))
						$relationName=$rawName.($i++);
					$relations[$refClassName][$relationName]="array(self::$relationType, '$className', '$fkName')";
				}
			}
		}
		return $relations;
	}
	
	public function getColumnCode($modelClass,$column,$place){
		//return print_r($column,false);
            
		if ($column->dbType == 'date') {
			if ($place == 'beforeSave') {
				return '		if($this->'.$column->name.' != "")
			$this->' . $column->name . ' = Util::formataDataBanco($this->' . $column->name . ');' . "\n";
			}
			if ($place == 'afterFind') {
				return '		if($this->' . $column->name . ' != "")
			$this->' . $column->name . ' = Util::formataDataApp($this->' . $column->name . ');' . "\n";
			}
			if($place == 'init'){
				return '		$this->' . $column->name . " = date('d/m/Y');"."\n";
			}
			
		}
            
		if($column->dbType == 'datetime'){
			if($place == 'beforeSave'){
				return '		if($this->'.$column->name.' != "")
				$this->'.$column->name.'= Util::formataDataHoraBanco($this->'.$column->name.');'."\n";
			}
			if($place == 'afterFind'){
				return '		if($this->'.$column->name.' != "")
				$this->'.$column->name.' = Util::formataDataHoraApp($this->'.$column->name.');'."\n";
			}
			if($place == 'init'){
				return '		$this->' . $column->name . " = date('d/m/Y H:i:s');"."\n";
			}
		}
		
		if($column->dbType == 'varchar(120)'){
			if($place == 'behaviors'){
				return '			\''.$this->generateClassName($column->name).'\' => array(
				  \'class\' => \'ext.behaviors.AttachmentBehavior\',
				  \'attribute\' => \''.$column->name.'\',
				  \'fallback_image\' => \'img/imagem_nao_cadastrada.png\',
				  \'attribute_delete\' => \''.$column->name.'_delete\',
				  /*
				  \'attribute_size\' => \''.$column->name.'_tamanho\',
				  \'attribute_type\' => \''.$column->name.'_tipo\',
				  \'attribute_ext\' => \''.$column->name.'_ext\',
				  */
				  \'path\' => "uploads/:model/'.$column->name.'_:id_".time().".:ext",
				  \'styles\' => array(
					  \'p\' => \'200x200\',
					  \'g\' => \'800x800\',
				)          
			),'."\n";
			}
			
			if($place == 'beforeSave'){
				return '		if($this->'.$column->name.' != "")
				unset($this->'.$column->name.');'."\n";
			}
		}
		if($column->dbType == 'varchar(130)'){
			if($place == 'behaviors'){
				return '			\''. $this->generateClassName($column->name).'\' => array(
				  \'class\' => \'ext.behaviors.AttachmentBehavior\',
				  \'attribute\' => \''.$column->name.'\',
				  \'fallback_image\' => \'img/imagem_nao_cadastrada.png\',
				  \'attribute_delete\' => \''.$column->name.'_delete\',
				  /*
				  \'attribute_size\' => \''.$column->name.'_tamanho\',
				  \'attribute_type\' => \''.$column->name.'_tipo\',
				  \'attribute_ext\' => \''.$column->name.'_ext\',
				  */
				  \'path\' => "uploads/:model/'.$column->name.'_:id_".time().".:ext",          
			),'."\n";
			}
			if($place == 'beforeSave'){
				return '		if($this->'.$column->name.' != "")
				unset($this->'.$column->name.');'."\n";
			}
		}
		
		if($column->dbType == 'varchar(150)'){
			if($place == 'get'){
				$code = '//'.$column->name.'
	public function get'.$this->generateClassName($column->name).'Array(){
		return array(
';
		
		$comments = Metadata::getArrayComments($column);
		if(is_array($comments)){
			array_shift($comments);
			foreach($comments as $opcao){
				$code .= '			\''.$this->generateValueName($opcao).'\'=>\''.$opcao.'\','."\n";	
			}
		}
		
			
				$code .= '		);
	}
	public function get'.$this->generateClassName($column->name).'(){
		$array = $this->get'.$this->generateClassName($column->name).'Array();
		return $array[$this->'.$column->name.'];
	}
	
	';
				return $code;
			}
		}
		
		if($column->isForeignKey && $this->findRelatedClass($modelClass, $column) == 'Gallery'){
			if($place == 'behaviors'){
				$comments = Metadata::getArrayComments($column);
				$nome_behavior = $comments[1] != "" ? $comments[1] : 'galeria';
				return '			\''.$nome_behavior.'\' => array(
				\'class\' => \'GalleryBehavior\',
				\'idAttribute\' => \''.$column->name.'\',
				\'versions\' => array(
					\'small\' => array(
						\'centeredpreview\' => array(200, 200),
					),
					\'medium\' => array(
						\'resize\' => array(800, null),
					)
				),
				\'name\' => false,
				\'description\' => true,
			 )'."\n";
			}
		}
		
		return false;
	}
	
	
	public function generateClassName($name){
		return parent::generateClassName($name);
	}
	
	public	function generateValueName($var) {

		$var = strtolower($var);
		
		$var = ereg_replace("[áàâãª]","a",$var);	
		$var = ereg_replace("[éèê]","e",$var);	
		$var = ereg_replace("[óòôõº]","o",$var);	
		$var = ereg_replace("[úùû]","u",$var);	
		$var = str_replace("ç","c",$var);
		$var = str_replace(" ","_",$var);
		
		return $var;
	}
}