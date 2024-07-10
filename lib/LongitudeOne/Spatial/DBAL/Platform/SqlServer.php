<?php

namespace LongitudeOne\Spatial\DBAL\Platform;

use LongitudeOne\Spatial\DBAL\Types\AbstractSpatialType;
use LongitudeOne\Spatial\DBAL\Types\GeographyType;

class SqlServer extends AbstractPlatform{
    /**
     * Get an array of database types that map to this Doctrine type.
     *
     * @param AbstractSpatialType $type the spatial type
     *
     * @return string[]
     */
    public function getMappedDatabaseTypes(AbstractSpatialType $type): array
    {
        $sqlType = mb_strtolower($type->getSQLType());

        if ($type instanceof GeographyType && 'geography' !== $sqlType) {
            $sqlType = sprintf('geography::%s', $sqlType);
        }

        return [$sqlType];
    }

    public function convertToDatabaseValueSql(AbstractSpatialType $type, $sqlExpr): string
    {
        if ($type instanceof GeographyType) {
            return sprintf('ST_GeographyFromText(%s)', $sqlExpr);
        }

        return sprintf('ST_GeomFromEWKT(%s)', $sqlExpr);
    }

    public function convertToPhpValueSql(AbstractSpatialType $type, $sqlExpr): string
    {
        if ($type instanceof GeographyType) {
            return sprintf('ST_AsEWKT(%s)', $sqlExpr);
        }

        return sprintf('ST_AsEWKB(%s)', $sqlExpr);
    }

    public function getSqlDeclaration(array $column, ?AbstractSpatialType $type = null, ?int $srid = null): string
    {
        $type = parent::checkType($column, $type);
        $srid = parent::checkSrid($column, $srid);
        if($type instanceof GeographyType){
            return 'GEOGRAPHY';
        }else {
            return 'GEOMETRY';
        }
    }
}
