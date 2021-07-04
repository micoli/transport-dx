<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence;

use App\Core\Domain\Entity\Header;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

final class HeadersType extends JsonType
{
    public function getName(): string
    {
        return 'headers';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        /**
         * @var Header[] $value
         */
        $encoded = json_encode($value);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw ConversionException::conversionFailedSerialization($value, 'json', json_last_error_msg());
        }

        return $encoded;
    }

    /**
     * @return array<int, Header>
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?array
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        /** @var array<int, array <string,string>> $vals */
        $vals = json_decode($value, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return array_map(fn (array $val) => new Header($val['key'], $val['value']), $vals);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
