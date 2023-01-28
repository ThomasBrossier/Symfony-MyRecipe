<?php

namespace App\Factory;

use App\Entity\Recipe;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseFactory
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function create(object $data, int $status = 200, array $headers = []): Response
    {
        return new Response(
            $this->serializer->serialize( $data, JsonEncoder::FORMAT),
            $status,
            array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }
    /*public function update( $data, $entity, $class): Recipe
    {
        $normalizers = array(new ObjectNormalizer());
        return $this->serializer->deserialize($data,$class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $entity, AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE =>true ] );
    }*/
}