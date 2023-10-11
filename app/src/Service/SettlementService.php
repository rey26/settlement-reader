<?php

namespace App\Service;

use App\Entity\Settlement;
use App\Enum\County;
use App\Repository\SettlementRepository;
use App\Service\Factory\SettlementFactory;
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
        protected array $settlements = [],
    ) {
        foreach ($settlementRepository->findAll() as $settlement) {
            $key = $settlement->getName();

            if ($settlement->getParent()) {
                $key .= '__' . $settlement->getParent()->getName();
                $this->settlements[$key] = $settlement;
            } else {
                $this->districtSettlements[$key] = $settlement;
            }
        }
    }

    public function getAllDistricts(): array
    {
        $districtUrls = [];

        foreach (County::cases() as $county) {
            $nodes = $this->client->request('GET', "{$this->url}/kraj/{$county->name}.html")
                ->filter('a.okreslink')->getIterator();

            foreach ($nodes as $node) {
                $districtUrls[$node->nodeValue] = $node->getAttribute('href');
            }
        }

        return $districtUrls;
    }

    public function getSettlementsForDistrict(string $districtUrl): array
    {
        $settlementUrls = [];

        $urls = $this->client->request('GET', $districtUrl)
            ->filter('td[width="33%"]')->getIterator();

        foreach ($urls as $url) {
            dd($url->nodeValue);
        }
        dd($urls->firstChild);
            // ->each(function (Crawler $node) {
            //     return $node->link()->getUri();
            // })
        // ;

        return $settlementUrls;
    }

    public function saveSettlement(string $districtName, string $url): static
    {
        $crawler = $this->client->request('GET', $url);
        $name = $crawler->filter('td[class="obecmenuhead"]')->text();
        $districtSettlement = $this->findDistrictSettlementByName($districtName);

        if ($districtName === $name) {
            $settlement = $districtSettlement;
        } else {
            $settlement = $this->findSettlementByNameAndDistrict($name, $districtName);
        }

        if ($settlement) {
            $settlement = SettlementFactory::updateFromCrawler($settlement, $crawler);
        } else {
            $settlement = SettlementFactory::createFromCrawler($crawler, $name);
        }

        if ($districtName !== $name) {
            $settlement->setParent($districtSettlement);
        }

        $this->settlementRepository->save($settlement);

        return $this;
    }

    private function findDistrictSettlementByName(string $name): ?Settlement
    {
        if (array_key_exists($name, $this->districtSettlements)) {
            return $this->districtSettlements[$name];
        }

        return null;
    }

    private function findSettlementByNameAndDistrict(string $name, string $districtName): ?Settlement
    {
        $key = $districtName . '__' . $name;

        if (array_key_exists($key, $this->settlements)) {
            return $this->settlements[$key];
        }

        return null;
    }
}
