<?php

namespace WsdlToPhp\PackageGenerator\DomHandler\Wsdl\Tag;

use WsdlToPhp\PackageGenerator\DomHandler\Wsdl\Wsdl as WsdlDocument;
use WsdlToPhp\PackageGenerator\DomHandler\AbstractAttributeHandler as Attribute;

class TagHeader extends AbstractTagOperationElement
{
    const ATTRIBUTE_PART = 'part';
    const REQUIRED_HEADER = 'required';
    const OPTIONAL_HEADER = 'optional';
    const ATTRIBUTE_REQUIRED = 'wsdl:required';
    /**
     * @return TagInput|null
     */
    public function getParentInput()
    {
        return $this->getStrictParent(WsdlDocument::TAG_INPUT);
    }
    /**
     * @return string
     */
    public function getAttributeRequired()
    {
        return $this->hasAttribute(self::ATTRIBUTE_REQUIRED) ? $this->getAttribute(self::ATTRIBUTE_REQUIRED)->getValue(true, true, 'bool') : true;
    }
    /**
     * @return string
     */
    public function getAttributeNamespace()
    {
        return $this->hasAttribute(Attribute::ATTRIBUTE_NAMESPACE) ? $this->getAttribute(Attribute::ATTRIBUTE_NAMESPACE)->getValue() : '';
    }
    /**
     * @return string
     */
    public function getAttributePart()
    {
        return $this->hasAttribute(self::ATTRIBUTE_PART) ? $this->getAttribute(self::ATTRIBUTE_PART)->getValue() : '';
    }
    /**
     * @return TagPart|null
     */
    public function getPartTag()
    {
        return $this->getPart($this->getAttributePart());
    }
    /**
     * @return string
     */
    public function getHeaderType()
    {
        $part = $this->getPartTag();
        return $part instanceof TagPart ? $part->getFinalType() : '';
    }
    /**
     * @return string
     */
    public function getHeaderName()
    {
        $part = $this->getPartTag();
        return $part instanceof TagPart ? $part->getFinalName() : '';
    }
    /**
     * @see \WsdlToPhp\PackageGenerator\DomHandler\AbstractNodeHandler::getNamespace()
     * @return string
     */
    public function getHeaderNamespace()
    {
        $namespace = $this->getHeaderNamespaceFromPart();
        if (empty($namespace)) {
            $namespace = $this->getHeaderNamespaceFromMessage();
        }
        return $namespace;
    }
    /**
     * @return string
     */
    protected function getHeaderNamespaceFromPart()
    {
        $part = $this->getPartTag();
        $namespace = '';
        if ($part instanceof TagPart) {
            $finalNamespace = $part->getFinalNamespace();
            if (!empty($finalNamespace)) {
                $namespace = $this->getDomDocumentHandler()->getNamespaceUri($finalNamespace);
            }
        }
        return $namespace;
    }
    /**
     * @return string
     */
    protected function getHeaderNamespaceFromMessage()
    {
        $namespace = '';
        $messageNamespace = $this->getAttributeMessageNamespace();
        if (!empty($messageNamespace)) {
            $namespace = $this->getDomDocumentHandler()->getNamespaceUri($messageNamespace);
        }
        return $namespace;
    }
    /**
     * @return string
     */
    public function getHeaderRequired()
    {
        return $this->getAttributeRequired() === true ? self::REQUIRED_HEADER : self::OPTIONAL_HEADER;
    }
}
