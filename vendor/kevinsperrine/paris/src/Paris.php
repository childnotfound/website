<?php
/**
 * Paris
 *
 * A completely rewritten, to match PSR coding standards, version of Paris.
 *
 * http://github.com/j4mie/paris/
 * http://github.com/kevinsperrine/paris/
 *
 * A simple Active Record implementation built on top of Idiorm
 * ( http://github.com/j4mie/idiorm/ ).
 *
 * You should include Idiorm before you include this file:
 * require_once 'your/path/to/idiorm.php';
 *
 * BSD Licensed.
 *
 * Copyright (c) 2010, Jamie Matthews
 * Copyright (c) 2012, Kevin Perrine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 *
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category ActiveRecord
 * @package  Paris
 * @author   Jamie Matthews <jamie.matthews@gmail.com>
 * @author   Kevin Perrine <kperrine@gmail.com>
 * @license  BSD http://opensource.org/licenses/bsd-license.php
 * @version  2.0.0
 * @link     http://github.com/j4mie/idiorm/
 * @link     https://github.com/kevinsperrine/idiorm
 */

/**
 * Paris
 *
 * A completely rewritten, to match PSR coding standards, version of Paris.
 *
 * http://github.com/j4mie/paris/
 * http://github.com/kevinsperrine/paris/
 *
 * A simple Active Record implementation built on top of Idiorm
 * ( http://github.com/j4mie/idiorm/ ).
 *
 * You should include Idiorm before you include this file:
 * require_once 'your/path/to/idiorm.php';
 *
 * BSD Licensed.
 *
 * Copyright (c) 2010, Jamie Matthews
 * Copyright (c) 2012, Kevin Perrine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 *
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category ActiveRecord
 * @package  Paris
 * @author   Jamie Matthews <jamie.matthews@gmail.com>
 * @author   Kevin Perrine <kperrine@gmail.com>
 * @license  BSD http://opensource.org/licenses/bsd-license.php
 * @version  2.0.0
 * @link     http://github.com/j4mie/idiorm/
 * @link     https://github.com/kevinsperrine/idiorm
 */
class Paris
{
    // Default ID column for all models. Can be overridden by adding
    // a public static _id_column property to your model classes.
    const DEFAULT_ID_COLUMN = 'id';

    // Default foreign key suffix used by relationship methods
    const DEFAULT_FOREIGN_KEY_SUFFIX = '_id';

    /**
     * The ORM instance used by this model
     * instance to communicate with the database.
     */
    public $orm;

    /**
     * Retrieve the value of a static property on a class. If the
     * class or the property does not exist, returns the default
     * value supplied as the third argument (which defaults to null).
     *
     * @param string $class_name class name to retreive property from
     * @param string $property   property you wish to get
     * @param null   $default    the default return value if the property doesn't exist
     *
     * @return mixed returns the property, if exists, or default otherwise
     */
    protected static function getStaticProperty($class_name, $property, $default = null)
    {
        if (!class_exists($class_name) || !property_exists($class_name, $property)) {
            return $default;
        }
        $properties = get_class_vars($class_name);

        return $properties[$property];
    }

    /**
     * Static method to get a table name given a class name.
     * If the supplied class has a public static property
     * named $_table, the value of this property will be
     * returned. If not, the class name will be converted using
     * the classNameToTableName method method.
     *
     * @param string $class_name class name
     *
     * @return string name of table associated with given class
     */
    protected static function getTableName($class_name)
    {
        $specified_table_name = self::getStaticProperty($class_name, '_table');
        if (is_null($specified_table_name)) {
            return self::classNameToTableName($class_name);
        }

        return $specified_table_name;
    }

    /**
     * Static method to convert a class name in CapWords
     * to a table name in lowercase_with_underscores.
     * For example, CarTyre would be converted to car_tyre.
     *
     * @param string $class_name class name
     *
     * @return string table name, in underscored lowercase, of associated class
     */
    protected static function classNameToTableName($class_name)
    {
        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $class_name));
    }

    /**
     * Return the ID column name to use for this class. If not set in the class
     * it will return 'id'
     *
     * @param string $class_name class name
     *
     * @return string the name of the id column for this class
     */
    protected static function getIdColumnName($class_name)
    {
        return self::getStaticProperty($class_name, '_id_column', self::DEFAULT_ID_COLUMN);
    }

    /**
     * Build a foreign key based on a table name. If the first argument
     * (the specified foreign key column name) is null, returns the second
     * argument (the name of the table) with the default foreign key column
     * suffix appended.
     *
     * @param string $specified_foreign_key_name name of foreign key
     * @param string $table_name                 name of table
     *
     * @return string either the name given, or one created by
     *                suffixing the default to the table name
     */
    protected static function buildForeignKeyName($specified_foreign_key_name, $table_name)
    {
        if (!is_null($specified_foreign_key_name)) {
            return $specified_foreign_key_name;
        }

        return $table_name . self::DEFAULT_FOREIGN_KEY_SUFFIX;
    }

    /**
     * Factory method used to acquire instances of the given class.
     * The class name should be supplied as a string, and the class
     * should already have been loaded by PHP (or a suitable autoloader
     * should exist). This method actually returns a wrapped ORM object
     * which allows a database query to be built. The wrapped ORM object is
     * responsible for returning instances of the correct class when
     * its findOne or findMany methods are called.
     *
     * @param string $class_name class name
     *
     * @return OrmWrapper OrmWrapper object used for this instance
     */
    public static function factory($class_name)
    {
        $table_name = self::getTableName($class_name);
        $wrapper = OrmWrapper::forTable($table_name);
        $wrapper->setClassName($class_name);
        $wrapper->useIdColumn(self::getIdColumnName($class_name));

        return $wrapper;
    }

    /**
     * Internal method to construct the queries for both the hasOne and
     * hasMany methods. These two types of association are identical; the
     * only difference is whether find_one or find_many is used to complete
     * the method chain.
     *
     * @param string $associated_class_name name of class that it has
     * @param string $foreign_key_name      foreign key to use in teh relationship
     *
     * @return OrmWrapper
     */
    protected function hasOneOrMany($associated_class_name, $foreign_key_name = null)
    {
        $base_table_name = self::getTableName(get_class($this));
        $foreign_key_name = self::buildForeignKeyName($foreign_key_name, $base_table_name);

        return self::factory($associated_class_name)->where($foreign_key_name, $this->id());
    }

    /**
     * Helper method to manage one-to-one relations where the foreign
     * key is on the associated table.
     *
     * @param string $associated_class_name class name of relation
     * @param string $foreign_key_name      foreign key for relationship
     *
     * @return OrmWrapper
     */
    protected function hasOne($associated_class_name, $foreign_key_name = null)
    {
        return $this->hasOneOrMany($associated_class_name, $foreign_key_name);
    }

    /**
     * Helper method to manage one-to-many relations where the foreign
     * key is on the associated table.
     */
    /**
     * [hasMany description]
     *
     * @param string $associated_class_name class name of relation
     * @param string $foreign_key_name      foreign key for relationship
     *
     * @return OrmWrapper
     */
    protected function hasMany($associated_class_name, $foreign_key_name = null)
    {
        return $this->hasOneOrMany($associated_class_name, $foreign_key_name);
    }

    /**
     * Helper method to manage one-to-one and one-to-many relations where
     * the foreign key is on the base table.
     *
     * @param string $associated_class_name class name of relation
     * @param string $foreign_key_name      foreign key used in relationship
     *
     * @return OrmWrapper
     */
    protected function belongsTo($associated_class_name, $foreign_key_name = null)
    {
        $associated_table_name = self::getTableName($associated_class_name);
        $foreign_key_name = self::buildForeignKeyName($foreign_key_name, $associated_table_name);
        $associated_object_id = $this->$foreign_key_name;

        return self::factory($associated_class_name)->whereIdIs($associated_object_id);
    }

    /**
     * Helper method to manage many-to-many relationships via an intermediate model. See
     * README for a full explanation of the parameters.
     *
     * @param string $associated_class_name   class name of relation
     * @param string $join_class_name         class name of intermediary
     * @param string $key_to_base_table       foreign key for base table
     * @param string $key_to_associated_table foreign key for assocated table
     *
     * @return OrmWrapper
     */
    protected function hasManyThrough($associated_class_name, $join_class_name = null, $key_to_base_table = null, $key_to_associated_table = null)
    {
        $base_class_name = get_class($this);

        // The class name of the join model, if not supplied, is
        // formed by concatenating the names of the base class
        // and the associated class, in alphabetical order.
        if (is_null($join_class_name)) {
            $class_names = array($base_class_name, $associated_class_name);
            sort($class_names, SORT_STRING);
            $join_class_name = join("", $class_names);
        }

        // Get table names for each class
        $base_table_name = self::getTableName($base_class_name);
        $associated_table_name = self::getTableName($associated_class_name);
        $join_table_name = self::getTableName($join_class_name);

        // Get ID column names
        $base_table_id_column = self::getIdColumnName($base_class_name);
        $associated_table_id_column = self::getIdColumnName($associated_class_name);

        // Get the column names for each side of the join table
        $key_to_base_table = self::buildForeignKeyName($key_to_base_table, $base_table_name);
        $key_to_associated_table = self::buildForeignKeyName($key_to_associated_table, $associated_table_name);

        return self::factory($associated_class_name)
            ->select("{$associated_table_name}.*")
            ->join($join_table_name, array("{$associated_table_name}.{$associated_table_id_column}", '=', "{$join_table_name}.{$key_to_associated_table}"))
            ->where("{$join_table_name}.{$key_to_base_table}", $this->id());
    }

    /**
     * Set the wrapped ORM instance associated with this Paris instance.
     *
     * @param Orm $orm OrmWrapper object to assign to this instance
     *
     * @return none
     */
    public function setOrm($orm)
    {
        $this->orm = $orm;
    }

    /**
     * Magic getter method, allows $model->property access to data.
     *
     * @param string $property name of property to access
     *
     * @return mixed returns the property if available, null otherwise
     */
    public function __get($property)
    {
        return $this->orm->get($property);
    }

    /**
     * Magic setter method, allows $model->property = 'value' access to data.
     *
     * @param string $property name of property
     * @param mixed  $value    value to assign to property
     *
     * @return none
     */
    public function __set($property, $value)
    {
        $this->orm->set($property, $value);
    }

    /**
     * Magic isset method, allows isset($model->property) to work correctly.
     *
     * @param string $property name of property check
     *
     * @return boolean true if isset, false otherwise
     */
    public function __isset($property)
    {
        return $this->orm->__isset($property);
    }

    /**
     * Getter method, allows $model->get('property') access to data
     *
     * @param string $property name of property to access
     *
     * @return mixed value of property if exists, null othewise
     */
    public function get($property)
    {
        return $this->orm->get($property);
    }

    /**
     * Setter method, allows $model->set('property', 'value') access to data.
     *
     * @param string $property name of property
     * @param mixed  $value    value to assign to property
     *
     * @return none
     */
    public function set($property, $value)
    {
        $this->orm->set($property, $value);
    }

    /**
     * Check whether the given field has changed since the object was created or saved
     *
     * @param string $property property to check
     *
     * @return boolean true if has not been saved, false otherwise
     */
    public function isDirty($property)
    {
        return $this->orm->isDirty($property);
    }

    /**
     * Wrapper for Idiorm's asArray method.
     *
     * @return array returns the current instance an as array
     */
    public function asArray()
    {
        $args = func_get_args();

        return call_user_func_array(array($this->orm, 'asArray'), $args);
    }

    /**
     * Save the data associated with this model instance to the database.
     *
     * @return boolean true on success, false on failure
     */
    public function save()
    {
        return $this->orm->save();
    }

    /**
     * Delete the database row associated with this model instance.
     *
     * @return boolean true on success, false on failure
     */
    public function delete()
    {
        return $this->orm->delete();
    }

    /**
     * Get the database ID of this model instance.
     *
     * @return integer database id of this model
     */
    public function id()
    {
        return $this->orm->id();
    }

    /**
     * Hydrate this model instance with an associative array of data.
     * WARNING: The keys in the array MUST match with columns in the
     * corresponding database table. If any keys are supplied which
     * do not match up with columns, the database will throw an error.
     *
     * @param array $data associative array of data
     *
     * @return OrmWrapper return the current instance populated with the new data.
     */
    public function hydrate($data)
    {
        $this->orm->hydrate($data)->forceAllDirty();
    }
}
