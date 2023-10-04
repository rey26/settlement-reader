<?php

namespace App\Service;

use App\Entity\Settlement;
use App\Enum\County;
use App\Repository\SettlementRepository;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DomCrawler\Crawler;

class SettlementService
{
    public function __construct(
        #[Autowire(env: 'SETTLEMENT_SOURCE_URL')] protected string $url,
        protected SettlementRepository $settlementRepository,
        protected HttpBrowser $client = new HttpBrowser(),
        protected array $districtSettlements = [],
    ) {
        foreach ($settlementRepository->findDistrictSettlements() as $districtSettlement) {
            $this->districtSettlements[$districtSettlement->getName()] = $districtSettlement;
        }
    }

    public function getAllDistricts(): array
    {
        $districtUrls = [];

        foreach (County::cases() as $county) {
            $districtUrls = $this->client->request('GET', "{$this->url}/kraj/{$county->name}.html")
                ->filter('a.okreslink')
                ->each(function (Crawler $node) {
                    return $node->link()->getUri();
                })
            ;
        }

        return $districtUrls;
    }

    public function getSettlementsForDistrict(string $districtUrl): array
    {
        $settlementUrls = [];

        $this->client->request('GET', $districtUrl)
            ->filter('td[width="33%"]')
            ->each(function (Crawler $node) {
                return $node->link()->getUri();
            })
        ;

        return $settlementUrls;
    }

    public function saveSettlement(string $url): static
    {
        // create settlement
        // find district settlement

        return $this;
    }

    private function findDistrictSettlementByName(string $name): ?Settlement
    {
        if (array_key_exists($name, $this->districtSettlements)) {
            return $this->districtSettlements[$name];
        }

        return null;
    }
}
