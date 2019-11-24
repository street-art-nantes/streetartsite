<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class RegionFilter.
 */
final class RegionFilter extends AbstractContextAwareFilter
{
    /**
     * @param string                      $property
     * @param mixed                       $value
     * @param QueryBuilder                $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string                      $resourceClass
     * @param string|null                 $operationName
     *
     * @throws \Exception
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ('region' !== $property) {
            return;
        }

        if (!isset($value['bounds'])) {
            return;
        }

        if (!$value['bounds']) {
            throw new \Exception('An error occurred while processing the request.');
        }

        $bounds = $value['bounds'];

        try {
            $boundsArray = explode(',', $value['bounds']);
        } catch (\Exception $exception) {
            throw new \Exception('An error occurred while processing the request.');
        }

        if (!\is_array($boundsArray) || 4 !== \count($boundsArray)) {
            throw new \Exception('An error occurred while processing the request.');
        }

        //var_dump($queryBuilder->getAllAliases());
        $queryBuilder
            ->where("ST_Intersects(Geography(ST_SetSRID(poi_a1.point, 4326)), ST_MakeEnvelope($bounds, 4326)) = true");
    }

    /**
     * @param string $resourceClass
     *
     * @return array
     */
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["region[$property]"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
        }

        return $description;
    }
}
