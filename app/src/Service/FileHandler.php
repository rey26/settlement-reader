<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Polyfill\Intl\Normalizer\Normalizer;

class FileHandler
{
    public function __construct(#[Autowire(param: 'coat_of_arms_directory')] protected string $coatOfArmsDirectory)
    {
    }

    public function downloadFile(?string $url, string $settlementName): ?string
    {
        if (empty($url)) {
            return null;
        }
        $extension = pathinfo(basename($url), PATHINFO_EXTENSION);
        $localFileName = self::generateSlug($settlementName) . '.' . $extension;
        $localPath = $this->coatOfArmsDirectory . '/' . $localFileName;

        // Download the file from the URL
        $fileContents = file_get_contents($url);

        if ($fileContents === false) {
            return null; // Failed to download the file
        }

        // Save the file locally
        file_put_contents($localPath, $fileContents);

        return $localFileName;
    }

    public static function generateSlug(string $string): string
    {
        // Create an instance of the AsciiSlugger
        $slugger = new AsciiSlugger();

        // Normalize the input string (remove accents)
        $normalizedString = Normalizer::normalize($string);

        // Use the AsciiSlugger to create a slug from the normalized string
        return $slugger->slug($normalizedString)->lower()->toString();
    }
}
