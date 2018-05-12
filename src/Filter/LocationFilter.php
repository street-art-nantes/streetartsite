<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class LocationFilter.
 */
final class LocationFilter extends AbstractFilter
{
    /**
     * @param string $property
     * @param $value
     * @param QueryBuilder                $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string                      $resourceClass
     * @param string|null                 $operationName
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $values = explode(';', $value);
        $latitude = $values[0];
        $longitude = $values[1];
        $distanceInMeters = $values[2];

        $pointFrom = 'Geography(ST_SetSRID(ST_Point(o.longitude, o.latitude), 4326))';
        $pointTo = 'Geography(ST_SetSRID(ST_Point(:longitude, :latitude), 4326))';
        $queryBuilder
            ->addSelect("ST_Distance($pointFrom, $pointTo) as distance")
            ->where($queryBuilder->expr()->eq("ST_DWithin($pointFrom, $pointTo, :distance_in_meters)", $queryBuilder->expr()->literal(true)))
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude)
            ->setParameter('distance_in_meters', $distanceInMeters)
            ->orderBy('distance', 'ASC');
    }

    /**
     * @param string $resourceClass
     *
     * @return array
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description['regexp_'.$property] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter using a lat;long;distance.',
                    'name' => 'position',
                    'type' => 'lat;long;distance',
                ],
            ];
        }

        return $description;
    }
}
