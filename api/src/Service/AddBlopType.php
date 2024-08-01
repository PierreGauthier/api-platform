<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\GraphQl\Type\ContextAwareTypeBuilderInterface;
use ApiPlatform\GraphQl\Type\TypeNotFoundException;
use ApiPlatform\GraphQl\Type\TypesContainerInterface;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GraphQl\Operation;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use Symfony\Component\PropertyInfo\Type;

/**
 * Add aggregations in graphql search document response type.
 */
class AddBlopType implements ContextAwareTypeBuilderInterface
{
    public function __construct(
        private TypesContainerInterface $typesContainer,
        private ContextAwareTypeBuilderInterface $decorated,
    ) {
    }

    public function getResourceObjectType(
        ResourceMetadataCollection $resourceMetadataCollection,
        Operation $operation,
        ?ApiProperty $propertyMetadata = null,
        array $context = []
    ): GraphQLType {
        return $this->decorated->getResourceObjectType($resourceMetadataCollection, $operation, $propertyMetadata, $context);
    }

    public function getNodeInterface(): InterfaceType
    {
        return $this->decorated->getNodeInterface();
    }

    public function getPaginatedCollectionType(GraphQLType $resourceType, Operation $operation): GraphQLType
    {
        $type = $this->decorated->getPaginatedCollectionType($resourceType, $operation);
        $fields = $type->getFields(); // @phpstan-ignore-line
        if (!\array_key_exists('blop', $fields)) {
            $fields['blop'] = $this->getBlopType($resourceType);
            $configuration = [
                'name' => $type->name,
                'description' => "Connection for {$type->name}.",
                'fields' => $fields,
            ];

            $type = new ObjectType($configuration);
            $this->typesContainer->set($type->name, $type);
        }

        return $type;
    }

    public function getEnumType(Operation $operation): GraphQLType
    {
        return $this->decorated->getEnumType($operation);
    }

    public function isCollection(Type $type): bool
    {
        return $this->decorated->isCollection($type);
    }

    private function getBlopType(GraphQLType $resourceType): GraphQLType
    {
        try {
            $aggregationType = $this->typesContainer->get('Blop'); // @phpstan-ignore-line
        } catch (TypeNotFoundException) {
            $aggregationType = new ObjectType(
                [
                    'name' => 'Blop',
                    'fields' => [
                        'field' => GraphQLType::nonNull(GraphQLType::string()),
                        'count' => GraphQLType::int(),
                    ],
                ]
            );
            $this->typesContainer->set('Blop', $aggregationType);
        }

        return GraphQLType::listOf($aggregationType);
    }
}
