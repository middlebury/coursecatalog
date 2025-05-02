<?php

/**
 * Copyright (c) 2009 Middlebury College.
 *
 *     Permission is hereby granted, free of charge, to any person
 *     obtaining a copy of this software and associated documentation
 *     files (the "Software"), to deal in the Software without
 *     restriction, including without limitation the rights to use,
 *     copy, modify, merge, publish, distribute, sublicesne, and/or
 *     sell copies of the Software, and to permit the persons to whom the
 *     Software is furnished to do so, subject the following conditions:
 *
 *     The above copyright notice and this permission notice shall be
 *     included in all copies or substantial portions of the Software.
 *
 *     The Software is provided "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *     EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 *     OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 *     NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 *     HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *     WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *     OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 *     DEALINGS IN THE SOFTWARE.
 */

namespace Catalog\OsidImpl\Middlebury\configuration;

/**
 *  <p>This interface specifies the value portion of a configuration
 *  parameter. </p>.
 */
class Value implements \osid_configuration_Value
{
    /**
     * Constructor.
     */
    public function __construct(
        private \osid_id_Id $id,
        private int $index,
        private $value,
    ) {
    }

    /**
     *  Gets the index of this value.
     *
     * @return int the index of this value
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     *  Gets the value object. This returns the object corresponding to the
     *  value <code> Type. </code>.
     *
     *  @param object \osid_type_Type $valueType the type of the object to
     *          retrieve
     *
     * @return object the object corresponding to the value <code> Type
     *                </code>
     *
     * @throws \osid_NullArgumentException <code> valueType </code> is <code>
     *                                            null </code>
     * @throws \osid_UnsupportedException <code>
     *                                            implementsValueType(valueType) </code> is <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getValue(\osid_type_Type $valueType)
    {
        if ('urn' == strtolower($valueType->getIdentifierNamespace())
            && 'middlebury.edu' == strtolower($valueType->getAuthority())) {
            switch ($valueType->getIdentifier()) {
                case 'Primitives/String':
                    return strval($this->value);
                case 'Primitives/Integer':
                    return intval($this->value);
                case 'Primitives/Float':
                    return floatval($this->value);
                case 'Primitives/DateTime':
                    return new DateTime($this->value);
                case 'Primitives/Boolean':
                    return boolval($this->value);
                case 'Primitives/Array':
                    if (is_array($this->value)) {
                        return $this->value;
                    } else {
                        \osid_UnsupportedException('Could not convert a '.gettype($this->value).' to an Array.');
                    }
            }
        }

        throw new \osid_UnsupportedException('Value type Namespace: '.$valueType->getIdentifierNamespace().', Authority: '.$valueType->getAuthority().', Identifier: '.$valueType->getIdentifier().' is not supported.');
    }

    /*********************************************************
     * Methods from Parameter
     *********************************************************/

    /**
     *  Gets the <code> Id </code> associated with this instance of this
     *  parajmeter. Persisting any reference to this parameter is done by
     *  persisting the <code> Id </code> returned from this method. The <code>
     *  Id </code> returned may be different than the <code> Id </code> used
     *  to query this object. In this case, the new <code> Id </code> should
     *  be preferred over the old one for future queries.
     *
     * @return object \osid_id_Id the parameter <code> Id </code>
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  The <code> Id </code> is intended to be constant and
     *          persistent. A consumer may at any time persist the <code> Id
     *          </code> for retrieval at any future time. Ideally, the <code>
     *          Id </code> should consistently resolve into the designated
     *          object and not be reused.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  Gets the preferred display name associated with this instance of this
     *  parameter appropriate for display to the user.
     *
     * @return string the display name
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  A display name is a string used for identifying an object in
     *          human terms. A provider may wish to initialize the display
     *          name based on one or more object attributes. In some cases,
     *          the display name may not map to a specific or significant
     *          object attribute but simply be used as a preferred display
     *          name that can be modified. A provider may also wish to
     *          translate the display name into a specific locale using the
     *          Locale service. Some OSIDs define methods for more detailed
     *          naming.
     */
    public function getDisplayName()
    {
        return $this->getId()->getIdentifier();
    }

    /**
     *  Gets the description associated with this instance of this parameter.
     *
     * @return string the description
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  A description is a string used for describing an object in
     *          human terms and may not have significance in the underlying
     *          system. A provider may wish to initialize the description
     *          based on one or more object attributes and/or treat it as an
     *          auxiliary piece of data that can be modified. A provider may
     *          also wish to translate the description into a specific locale
     *          using the Locale service.
     */
    public function getDescription()
    {
        return '';
    }

    /**
     *  Gets the type of this parameter values.
     *
     * @return object \osid_type_Type the type of the values
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getValueType()
    {
        switch (gettype($this->value)) {
            case 'string':
                return new \phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String');
            case 'integer':
                return new \phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Integer');
            case 'double':
                return new \phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Float');
            case 'boolean':
                return new \phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Boolean');
            case 'array':
                return new \phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Array');
            default:
                throw new \osid_OperationFailedException($this->getDisplayName().' is a value of with type '.gettype($this->value).", but I don't have a supported type for that.");
        }
    }

    /**
     *  Tests if this object supports the given interface <code> Type. </code>
     *  The given interface type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the typed interface extension.
     *
     *  @param object \osid_type_Type $valueType a type
     *
     * @return bool <code> true </code> if the values associated with this
     *                     parameter implement the given <code> Type, </code> <code>
     *                     false </code> otherwise
     *
     * @throws \osid_NullArgumentException <code> valueType </code> is <code>
     *                                            null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function implementsValueType(\osid_type_Type $valueType)
    {
        return $this->getValueType()->isEqual($valueType);
    }
}
