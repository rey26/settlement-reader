<?php

namespace App\Service\Factory;

use App\Entity\Settlement;
use Symfony\Component\DomCrawler\Crawler;

class SettlementFactory
{
    public static function createFromCrawler(Crawler $crawler, string $name): Settlement
    {
        $settlement = (new Settlement())
            ->setName($name)
            ->setMayorName(self::getTdValueByPreviousElement($crawler, 'Starosta:', 'Primátor:') ?? 'uknown')
            ->setCityHallAddress(self::getCityHallAddress($crawler))
            ->setPhone(self::getTdValueByPreviousElement($crawler, 'Tel:'))
            ->setFax(self::getTdValueByPreviousElement($crawler, 'Fax:'))
            ->setEmail(self::getTdValueByPreviousElement($crawler, 'Email:'))
            ->setWebAddress(self::getTdValueByPreviousElement($crawler, 'Web:'))
            // ->setCoatOfArmsPath(self::getCoatOfArmsPath($crawler, $name))
        ;

        return $settlement;
    }

    public static function updateFromCrawler(Settlement $settlement, Crawler $crawler): Settlement
    {
        $settlement
            ->setMayorName(self::getTdValueByPreviousElement($crawler, 'Starosta:', 'Primátor:'))
            ->setCityHallAddress(self::getCityHallAddress($crawler))
            ->setPhone(self::getTdValueByPreviousElement($crawler, 'Tel:'))
            ->setFax(self::getTdValueByPreviousElement($crawler, 'Fax:'))
            ->setEmail(self::getTdValueByPreviousElement($crawler, 'Email:'))
            ->setWebAddress(self::getTdValueByPreviousElement($crawler, 'Web:'))
            // ->setCoatOfArmsPath(self::getCoatOfArmsPath($crawler, $name))
        ;

        return $settlement;
    }

    private static function getTdValueByPreviousElement(Crawler $crawler, string $key, ?string $secondaryKey = null): ?string
    {
        $result = $crawler->filter('td')
            ->reduce(function (Crawler $node) use ($key, $secondaryKey) {
                if ($secondaryKey) {
                    return $node->text() === $key || $node->text() === $secondaryKey;
                } else {
                    return $node->text() === $key;
                }
            });

        return $result->count() > 0 ? $result->first()->nextAll()->text() : null;
    }

    private static function getCityHallAddress(Crawler $crawler): string
    {
        return 'empty';
        $email = $crawler->filter('td')
            ->reduce(function (Crawler $node) {
                return $node->text() === 'Email:';
            })
            ->first();

        $street = $email
            ->previousAll()
            ->text();

        $postcodeAndTown = $email
            ->closest('tr')
            ->nextAll()
            ->children()
            ->first()
            ->text();

        return $street . ', ' . $postcodeAndTown;
    }

    private static function getCoatOfArmsPath(Crawler $crawler, string $name): ?string
    {
        $remoteFilePath = self::getCoatOfArmsRemoteUri($crawler, $name);

        if ($remoteFilePath === null) {
            return null;
        }
        $contents = file_get_contents($remoteFilePath);
        $directory = app()->basePath() . '/storage/app/public/images/coat-of-arms/';
        $fileName = Str::uuid()->toString() . '.' . Str::afterLast($remoteFilePath, '.');

        if (is_dir($directory) === false) {
            mkdir($directory, 0777, true);
        }

        return File::put($directory . $fileName, $contents) ? $fileName : null;
    }

    private static function getCoatOfArmsRemoteUri(Crawler $crawler, string $name): ?string
    {
        $imageElement = $crawler->filter('img')
            ->reduce(function (Crawler $node) use ($name) {
                return $node->attr('alt') == 'Erb ' . $name;
            })
            ->first();

        if ($imageElement) {
            return $imageElement->attr('src');
        }

        return null;
    }
}
