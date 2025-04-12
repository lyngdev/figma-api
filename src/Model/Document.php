<?php

namespace LyngDev\FigmaAPI\Model;

class Document
{

    private array $arrayData = [];

    public function __construct(string $jsonContent){
        $this->arrayData = json_decode($jsonContent, true);
    }

    public function getChildrenByType(string $typeToFind, bool $caseSensitive = false){
        $documentChildren = $this->arrayData["document"]["children"] ?? [];
        if(is_array($documentChildren)){
            return self::findChildren($documentChildren, function($child) use ($caseSensitive, $typeToFind) {
                if($caseSensitive === false && strtolower($child['type'] ?? '') === strtolower($typeToFind)){
                    return true;
                }
                if($child['type'] ?? '' === $typeToFind){
                    return true;
                }
                return false;
            });
        }
    }

    public function getFrameNames():array{
        $foundFrameElements = $this->getChildrenByType('FRAME', true);
        return array_combine(array_column($foundFrameElements, 'id'), array_column($foundFrameElements, 'name'));
    }

    private static function findChildren(array $elementsWithChildren, callable $filter = null):array{
        $children = [];
        foreach($elementsWithChildren as $element){

            if($element["children"]){
                $foundChildren = array_filter($element["children"], function($child) use ($filter){
                    if($filter){
                        return $filter($child);
                    }
                    return true;
                });
                $children = array_merge($children, $foundChildren);
            }
        }
        return $children;
    }
}
