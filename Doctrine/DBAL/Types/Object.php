<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Object extends AbstractType
{
    const OBJECT_JSON = 'object_json';

    public function getName ()
    {
        return self::OBJECT_JSON;
    }

    public function convertToDatabaseValue ($object, AbstractPlatform $platform)
    {
        if ($object === null) {
            return null;
        }
        if (!is_object($object)) {
            throw new \Exception('Not valid object.');
        }

        $serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
        $arrayObject = $serializer->normalize($object);
        $array['class_name'] = get_class($object);
        $array['object'] = $arrayObject;
        $json = $serializer->encode($array, JsonEncoder::FORMAT);
        if ($json === false) {
            throw new \Exception('Not valid object.');
        }
        return $json;
    }

    public function convertToPHPValue ($json, AbstractPlatform $platform)
    {
        if ($json === null) {
            return null;
        }

        $serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
        $array = $serializer->decode($json, JsonEncoder::FORMAT);
        $object = $serializer->denormalize($array['object'], $array['class_name']);
        if ($object === false) {
            throw new \Exception('Not valid json.');
        }
        return $object;
    }
}
