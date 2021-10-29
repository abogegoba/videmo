<?php

namespace ReLab\Commons\Wrappers;

use ReLab\Commons\Exceptions\ConvertFailedException;

/**
 * Class Data
 *
 * @package ReLab\Commons\Wrappers
 */
class Data extends \ArrayObject
{
    /**
     * データタイプ：配列
     */
    const TYPE_ARRAY = 1;

    /**
     * データタイプ：オブジェクト
     */
    const TYPE_OBJECT = 2;

    /**
     * @var object|null
     */
    private $original = null;

    /**
     * @var int
     */
    private $type = null;

    /**
     * Data constructor.
     *
     * @param array|object|\ArrayObject|null $data
     */
    public function __construct($data = null)
    {
        if (isset($data)) {
            if (is_array($data) || $data instanceof \IteratorAggregate) {
                $this->type = self::TYPE_ARRAY;
                foreach ($data as $key => $value) {
                    $this[$key] = self::wrap($value);
                }
                $this->original = $data;
            } else if (is_object($data)) {
                $this->type = self::TYPE_OBJECT;
                $this->original = $data;
            }
        } else {
            $this->type = self::TYPE_OBJECT;
        }
    }

    /**
     * 指定されたオブジェクトをDataクラスでラップする
     *
     * プリミティブ型の場合はそのまま値を返却します。
     *
     * @param mixed $data
     * @return Data|mixed
     */
    public static function wrap($data)
    {
        if (isset($data) && is_array($data) || (is_object($data) && !$data instanceof Data)) {
            $data = new Data($data);
        }
        return $data;
    }

    /**
     * データ設定
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $methodName = ucfirst($name);
        $setMethodName = "set" . $methodName;
        if (method_exists($this, $setMethodName)) {
            $this->$setMethodName($value);
        } else {
            $this[$name] = self::wrap($value);
        }
    }

    /**
     * データ取得
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        $value = null;
        $accessorMethodName = self::getAccessorMethodName($this, $name);
        if (isset($accessorMethodName)) {
            $value = $this->$accessorMethodName();
        } else if (isset($this[$name])) {
            return $this[$name];
        } else if ($this->typeIs(self::TYPE_OBJECT)) {
            if ($this->original instanceof Data) {
                $value = $this->original->$name;
            } else {
                $accessorMethodName = self::getAccessorMethodName($this->original, $name);
                if (isset($accessorMethodName)) {
                    $value = self::wrap($this->original->$accessorMethodName());
                }
            }
        }
        $this[$name] = $value;
        return $this[$name];
    }

    /**
     * パス指定のデータ取得
     *
     * @param string $path
     * @return mixed|null|Data|string
     */
    public function get(string $path)
    {
        $value = $this;
        $paths = explode(".", $path);
        foreach ($paths as $path) {
            if ($value instanceof Data) {
                $value = $value->$path;
            } else {
                $value = null;
            }
            if (!isset($value)) {
                break;
            }
        }
        if (isset($value) && $value instanceof Data && isset($format)) {
            $value = $value->format($format);
        }
        return $value;
    }

    /**
     * パス指定のデータ存在確認
     *
     * @param string $path
     * @return mixed|null|Data|string
     */
    public function has(string $path): bool
    {
        $value = $this->get($path);
        if (isset($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * パス指定のデータ取得（format）
     *
     * パス指定にて取得したデータをformatした値を返却する。
     * データがnull又はプリミティブ型である場合は、formatは実施されずそのままの値を返却する。
     *
     * @param string $path
     * @param string $format
     * @return mixed|null|Data|string
     */
    public function getWithFormat(string $path, string $format)
    {
        $value = $this->get($path);
        if (isset($value) && $value instanceof Data) {
            $value = $value->format($format);
        }
        return $value;
    }

    /**
     * パス指定のデータ取得（toString）
     *
     * パス指定にて取得したデータをtoStringした値を返却する。
     * 取得したデータがnull又はプリミティブ型である場合は、toStringは実施されるそのままの値を返却する。
     *
     * @param string $path
     * @return mixed|null|Data|string
     */
    public function getWithToString(string $path)
    {
        $value = $this->get($path);
        if (isset($value) && $value instanceof Data) {
            $value = $value->toString();
        }
        return $value;
    }

    /**
     * Dataタイプ確認
     *
     * @param int $type
     * @return bool
     */
    public function typeIs(int $type): bool
    {
        return $this->type === $type;
    }

    /**
     * フォーマット指定により文字列に変換した値を取得する
     *
     * 下記条件によって文字列に変換された値を返却する。
     * [TYPE_OBJECTの場合]
     * １．オリジナルのオブジェクトに実装されているformatメソッドを実行した結果を返却する。
     * ２．formatメソッドが存在しない場合、オリジナルのオブジェクトに実装されているtoStringメソッドを実行した結果を返却する。
     * ３．format、toStringいずれのメソッドも実装されていない場合はオリジナルのオブジェクトのクラス名を返却する。
     * [TYPE_ARRAYの場合]
     * １．配列の件数を返却する。
     *
     * @param string $format
     * @return null|string
     */
    public function format(string $format): ?string
    {
        $formatMethodName = "format";
        if ($this->typeIs(self::TYPE_OBJECT)) {
            if (method_exists($this->original, $formatMethodName)) {
                return $this->original->$formatMethodName($format);
            }
        }
        return $this->toString();
    }

    /**
     * 文字列に変換した値を取得する
     *
     * 下記条件によって文字列に変換された値を返却する。
     * [TYPE_OBJECTの場合]
     * １．オリジナルのオブジェクトに実装されているtoStringメソッドを実行した結果を返却する。
     * ２．toStringメソッドが実装されていない場合はオリジナルのオブジェクトのクラス名を返却する。
     * [TYPE_ARRAYの場合]
     * １．配列の件数を返却する。
     *
     * @return null|string
     */
    public function toString(): ?string
    {
        $toString = "toString";
        if ($this->typeIs(self::TYPE_OBJECT)) {
            if (method_exists($this->original, $toString)) {
                return $this->original->$toString();
            } else {
                if (isset($this->original)) {
                    return get_class($this->original);
                } else {
                    return get_class($this);
                }
            }
        } else if ($this->typeIs(self::TYPE_ARRAY)) {
            return count($this);
        }
        return null;
    }

    /**
     * KeyValueの配列に変換した配列に変換する
     *
     * [TYPE_OBJECTの場合]
     * １．オリジナルのオブジェクトに$keyNameと$valueNameのプロパティを取得するgetterメソッドが存在する場合
     * 　　そのメソッドから取得した値を使用してkeyValueのデータを作成する。
     * ２．$keyName、$valueName片方でもgetterメソッドが存在しなかった場合、keyValueのデータは作成しない。
     * [TYPE_ARRAYの場合]
     * １．通常の配列（連番）の場合
     * 　　配列分のtoKeyValuesメソッドを実行し、結果をkeyValueのデータ配列に追加する。
     * ２．連想配列（ハッシュマップキー）の場合
     * 　　１）$keyName、$valueNameをキーとして連想配列から取得した値を使用してkeyValueのデータを作成する。
     * 　　２）$keyName、$valueName片方でもキーが存在しなかった場合、keyValueのデータは作成しない。
     *
     * @param string $keyName Keyとなる値のプロパティ名
     * @param string $valueName Valueとなる値のプロパティ名
     * @return array
     */
    public function toKeyValues(string $keyName = "id", string $valueName = "name"): ?array
    {
        $keyValues = [];
        if ($this->typeIs(self::TYPE_OBJECT)) {
            $keyAccessorMethodName = self::getAccessorMethodName($this->original, $keyName);
            $valueAccessorMethodName = self::getAccessorMethodName($this->original, $valueName);
            if (isset($keyAccessorMethodName) && isset($valueAccessorMethodName)) {
                return [$this->original->$keyAccessorMethodName() => $this->original->$valueAccessorMethodName()];
            }
            return null;
        } else if ($this->typeIs(self::TYPE_ARRAY)) {
            $dataArray = $this->toArray();
            if (array_values($dataArray) === $dataArray) {
                /** @var Data $data */
                foreach ($dataArray as $data) {
                    $keyValue = $data->toKeyValues($keyName, $valueName);
                    $keyValues += $keyValue;
                }
            } else if (isset($this[$keyName]) && isset($this[$valueName])) {
                return [$this[$keyName] => $this[$valueName]];
            }
        }
        return $keyValues;
    }

    /**
     * DataオジェクトをArray配列に変換する
     *
     * @return array
     */
    public function toArray(): array
    {
        $values = [];
        if ($this->typeIs(self::TYPE_OBJECT)) {
            try {
                $objReflect = new \ReflectionClass($this->original);
                foreach ($objReflect->getProperties() as $objProperty) {
                    $propertyName = $objProperty->getName();
                    $accessorMethodName = self::getAccessorMethodName($this->original, $propertyName);
                    if (isset($accessorMethodName)) {
                        $values[$propertyName] = $this->original->$accessorMethodName();
                    }
                }
            } catch (\ReflectionException $e) {
                // リフレクションに失敗した場合は空の配列を返す
            }
        } else if ($this->typeIs(self::TYPE_ARRAY)) {
            $values = $this->original;
        }
        foreach ($this as $name => $value) {
            $values[$name] = $value;
        }
        return $values;
    }

    /**
     * オブジェクトへのプロパティデータマッピングを実施する
     *
     * $fromObjectに指定されたオブジェクトから$toObjectに対してプロパティ値のマッピングを行う。
     * $fromObjectから$toObjectへ変換して値をマッピングする必要がある場合は、下記の通り$extendsに
     * functionを指定する事。
     *
     * ex)
     * [
     *   "prefectureId" => function($value, $fromObject, $toObject) use ($prefectureRepository) {
     *     $toObject->setPrefecture($prefectureRepository::find($value));
     *   }
     * ]
     *
     * @param array|object $fromObject
     * @param object $toObject
     * @param array $extends
     * @return object
     */
    public static function mappingToObject($fromObject, $toObject, $extends = [])
    {
        $wrappedObject = self::wrap($fromObject);
        if ($wrappedObject instanceof Data && is_object($toObject)) {
            $values = $wrappedObject->toArray();
            foreach ($values as $name => $value) {
                if (isset($extends[$name])) {
                    $extends[$name]($value, $fromObject, $toObject);
                } else {
                    if ($toObject instanceof Data) {
                        $toObject->$name = $value;
                    } else {
                        $toObjectClass = get_class($toObject);
                        $methodName = "set" . ucfirst($name);
                        try {
                            $method = new \ReflectionMethod($toObjectClass, $methodName);
                            $params = $method->getParameters();
                            if (isset($params[0]) && isset($value)) {
                                $type = $params[0]->getType();
                                if (isset($type)) {
                                    $methodType = $type->getName();
                                    if (is_object($value)) {
                                        if ($value instanceof $methodType) {
                                            $toObject->$methodName($value);
                                        }
                                    } else {
                                        switch ($methodType) {
                                            case 'bool':
                                                $value = (bool)$value;
                                                break;
                                            case 'boolean':
                                                $value = (boolean)$value;
                                                break;
                                            case 'int':
                                                $value = (int)$value;
                                                break;
                                            case 'integer':
                                                $value = (integer)$value;
                                                break;
                                            case 'float':
                                                $value = (float)$value;
                                                break;
                                            case 'double':
                                                $value = (double)$value;
                                                break;
                                            case 'real':
                                                $value = (real)$value;
                                                break;
                                            case 'array':
                                                $value = (array)$value;
                                                break;
                                            case 'object':
                                                $value = (object)$value;
                                                break;
                                            case 'DateTime':
                                                if (strtotime($value)) {
                                                    $value = new \DateTime($value);
                                                } else {
                                                    throw new ConvertFailedException(\DateTime::class, $value);
                                                }
                                                break;
                                            case 'Carbon\Carbon':
                                                if (strtotime($value)) {
                                                    $value = new \Carbon\Carbon($value);
                                                } else {
                                                    throw new ConvertFailedException(\Carbon\Carbon::class, $value);
                                                }
                                                break;
                                        }
                                    }
                                }
                            }
                            $toObject->$methodName($value);
                        } catch (\Exception $e) {
                            // 値の移行でエラーが発生した場合は、その値をスキップする
                        }
                    }
                }
            }
        }
        return $toObject;
    }

    /**
     * 指定されたオブジェクトに存在する、指定されたプロパティ名のアクセッサメソッド名を取得する
     *
     * @param null|object $object
     * @param string $propertyName
     * @return null|string
     */
    public static function getAccessorMethodName(?object $object, string $propertyName): ?string
    {
        $getMethodName = "get" . ucfirst($propertyName);
        $isMethodName = "is" . ucfirst($propertyName);
        $hasMethodName = "has" . ucfirst($propertyName);
        if (method_exists($object, $getMethodName)) {
            return $getMethodName;
        } else if (method_exists($object, $isMethodName)) {
            return $isMethodName;
        } else if (method_exists($object, $hasMethodName)) {
            return $hasMethodName;
        }
        return null;
    }
}